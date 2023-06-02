<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use App\Models\Xeninvoice;
use App\Repositori\StockOrderRepositori;
use App\Repositori\TransaksiRepositori;
use App\Repositori\InvoiceRepositori;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DataTables;
use Image;
use PDF;
use Mail;

class InvoiceController extends Controller
{
    protected $transaksiRepo;
    protected $invoiceRepo;
    protected $stockOrderRepo;
    public function __construct()
    {
        $this->stockOrderRepo = new StockOrderRepositori();
        $this->invoiceRepo = new InvoiceRepositori();
        $this->transaksiRepo = new TransaksiRepositori();
        $this->middleware('permission:invoice|invoice-list|invoice-create|invoice-edit|invoice-delete|invoice-laporan', ['only' => ['index', 'show']]);
        $this->middleware('permission:invoice|invoice-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:invoice|invoice-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:invoice|invoice-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $invoice = $this->invoiceRepo->getWhere($q);
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $invoice->whereBetween('tgl', array($request->from_date, $request->to_date));
        }

        if (!empty($request->status_inv)) {
            $invoice->where('status', $request->status_inv);
        }

        $invoice = $invoice->latest();
        $invoice = $invoice->get();
        if ($request->ajax()) {
            return Datatables::of($invoice)
                ->addIndexColumn()
                ->addColumn('no_inv', function ($row) {
                    return '<a target="_blank" href="/invoice/detail/' . $row->no_inv . '" data-original-title="Detail">' . $row->no_inv . '</a>';
                })
                ->addColumn('nomor', function ($row) {
                    return '<a target="_blank" href="/transaksi/detail/' . $row->nomor . '" data-original-title="Detail">' . $row->nomor . '</a>';
                })
                ->addColumn('tgl_jatuh_tempo', function ($row) {
                    return tglIndo($row->tgl_jatuh_tempo);
                })
                ->addColumn('tgl', function ($row) {
                    return tglIndo($row->tgl);
                })
                ->addColumn('preview', function ($row) {
                    return '<a target="_blank" href="/pdf/invoice/' . $row->no_inv . '.pdf" data-original-title="Lampiran" title="Lampiran">Lampiran</a>';
                })
                ->addColumn('status', function ($row) {
                    $status = '';
                    if ($row->status == 0) {
                        $status = '<span class="text-default">Draft</span>';
                    } elseif ($row->status == 1) {
                        $status = '<span class="text-success">Dikirim</span>';
                    } elseif ($row->status == 2) {
                        $status = '<span class="text-primary">Dibayar</span>';
                    } elseif ($row->status == 3) {
                        $status = '<span class="text-primary">Selesai</span>';
                    } elseif ($row->status == 4) {
                        $status = '<span class="text-danger">Dibatalkan</span>';
                    } elseif ($row->status == 5) {
                        $status = '<span class="text-warning">Kedaluwarsa</span>';
                    }
                    return $status;
                })
                ->addColumn('toko', function ($row) {
                    return website($row->website_id)->nama_website;
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    $btn = $btn . ' <a href="/invoice/detail/' . $row->no_inv . '" data-toggle="tooltip"  data-id_invoice="' . $row->id . '" data-original-title="Detail" title="Detail" class="detail"> <i class="mdi mdi-eye"></i></a>';
                    if ($row->status == 0) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_invoice="' . $row->id . '" data-original-title="Kirim" title="Kirim Ke Email" class="ajukanQuotation"> <i class="mdi mdi-email-send"></i></a>';
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_invoice="' . $row->id . '" data-original-title="Edit" title="Ubah" class="editQuotation"> <i class="mdi mdi-square-edit-outline"></i></a>';
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_invoice="' . $row->id . '" data-original-title="Delete" title="Hapus" class="deleteQuotation"> <i class="mdi mdi-delete"></i></a>';
                    }elseif ($row->status >= 2) {

                    } else {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_invoice="' . $row->id . '" data-original-title="Edit" title="Ubah" class="editQuotation"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['no_inv', 'nomor', 'tgl', 'tgl_jatuh_tempo', 'preview', 'status', 'toko', 'action'])
                ->make(true);
        }
        $transaksi = $this->stockOrderRepo->getWhere($q)->where(['status_order' => 'proses', 'jenis' => 'penjualan'])->get();
        return view('invoice/invoice', compact('invoice', 'transaksi'));
    }

    public function kirimEmail($id)
    {
        $invoice = $this->invoiceRepo->getId($id);
        $transaksi = $this->stockOrderRepo->getWhere(['nomor' => $invoice->nomor])->first();
        $data["email"] = $transaksi->pelanggan->email;
        $data["title"] = 'Tagihan Pembayaran';
        // $data["body"] = $body;

        $files = [
            public_path('pdf/invoice/' . $invoice->no_inv . '.pdf'),
        ];
        if ($invoice->status == 0) {
            $this->invoiceRepo->update($id,['status' => 1]);
            $invoice = $this->invoiceRepo->getId($id);
        }

        $this->createPdf($id);

        $kirim = Mail::send('invoice.invoicePrint', compact('invoice', 'transaksi'), function ($message) use ($data, $files) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"]);

            foreach ($files as $file) {
                $message->attach($file);
            }
        });

        if ($kirim) {
            return true;
        }
        return false;
    }

    public function createPdf($id)
    {
        set_time_limit(300);
        $invoice = $this->invoiceRepo->getId($id);
        $transaksi = $this->stockOrderRepo->getWhere(['nomor' => $invoice->nomor])->first();
        $qrcode='';
        if($transaksi->xeninvoice){
            if($transaksi->xeninvoice->xen_status=='PENDING'){
                $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($transaksi->xeninvoice->xen_invoice_url));
            }
        }
        // $pdf = PDF::loadview('invoice.invoicePrint', compact('transaksi', 'invoice'))->setPaper('a4', 'landscape');
        // return $pdf->download('detail-invoice-' . $transaksi->nomor);
        $path = 'pdf/invoice';
        if (!file_exists($path)) {
            mkdir($path, 755, true);
        }
        Pdf::loadview('invoice.invoicePrint', compact('transaksi', 'invoice','qrcode'))
            ->setPaper('a4', 'landscape')
            ->save($path . '/' . $invoice->no_inv . '.pdf');
        // ->stream('laporan_penjualan_' . date('Y-m-d') . '.pdf');
        return true;
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nomor' => 'required|unique:invoice,nomor,'.$request->id_invoice,
                'tgl' => 'required',
                'tgl_jatuh_tempo' => 'required',
                'status' => 'required',
            ],
            [
                'required' => 'Data Belum Lengkap.',
                'nomor.unique'=>'Tagihan Sudah Ada',
            ]
        );


        $response = DB::transaction(function () use ($request) {
            $transaksi = $this->stockOrderRepo->getWhere(['nomor' => $request->nomor])->first();
            $no_inv = createCodeInvoice();
            if ($request->no_inv) {
                $no_inv = $request->no_inv;
            }
            if ($request->id_invoice) {
                $cek = $this->invoiceRepo->getId($request->id_invoice);
                if ($request->status < $cek->status) {
                    $response = [
                        'success' => false,
                        'message' => 'Status tidak dapat digunakan',
                    ];
                    return $response;
                }
            }
            $save = $this->invoiceRepo->updateOrCreate([
                'id' => $request->id_invoice,
            ], [
                'no_inv' => $no_inv,
                'nomor' => $request->nomor,
                'kepada' => $request->kepada,
                'di' => $request->di,
                'tgl' => $request->tgl,
                'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
                'pembuka' => $request->pembuka,
                'penutup' => $request->penutup,
                'catatan' => $request->catatan,
                'status' => $request->status,
                'website_id' => website()->id,
            ]);
            if ($save) {
                $this->createPdf($save->id);
                if ($request->status == 1) {
                    $this->kirimEmail($save->id);
                }
                $response = [
                    'success' => true,
                    'message' => 'Data Berhasil Disimpan',
                ];
            }

            return $response;
        });
        if ($response) {

            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Data Gagal Disimpan',
            ];
            return response()->json($response, 200);
        }
    }


    public function show(Request $request)
    {
        $invoice = $this->invoiceRepo->getWhere(['no_inv' => $request->no])->first();
        $transaksi = $this->stockOrderRepo->getWhere(['nomor' => $invoice->nomor])->first();
        $shipping = Shipping::where('transaksi_id', $transaksi->id)->first();
        if (isset($request->print)) {
            $print = true;
            return view('invoice.invoicePrint', compact('transaksi', 'invoice', 'shipping', 'print'));
        }

        if (isset($request->pdf)) {
            $pdf = $request->pdf;
            $pdf = PDF::loadview('invoice.invoicePrint', compact('transaksi', 'invoice', 'shipping', 'pdf'))->setPaper('a4', 'portrait');
            return $pdf->download('detail-invoice-' . $transaksi->nomor);
        }
        return view('invoice.detail', compact('invoice', 'transaksi', 'shipping'));
    }


    public function edit($id)
    {
        $invoice = $this->invoiceRepo->getId($id);
        return response()->json($invoice);
    }


    public function update(Request $request, Quotation $invoice)
    {
        //
    }


    public function destroy($id)
    {
        $del = $this->invoiceRepo->getId($id)->delete();
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }


    public function uploadImage($request)
    {
        $filename = time() . '.' . $request->extension();
        $request->move(public_path('image/invoice'), $filename);
        $tumb = Image::make('image/invoice/' . $filename)->resize(80, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $patchTumb = 'image/invoice/tumb';
        if (!file_exists($patchTumb)) {
            mkdir($patchTumb, 775, true);
        }
        $tumb->save(public_path('image/invoice/tumb/' . $filename));
        return $filename;
    }

    public function createInvoiceXendit($id)
    {
        $transaksi = $this->stockOrderRepo->getWhere(['id'=>$id]);
        if ($transaksi->count() >0) {
            $transaksi=$transaksi->first();
            $xen = Xeninvoice::where('transaksi_id', $transaksi->id)->count();
            if ($xen>0) {
                $xen=$xen->first();
                if($xen->status=='PENDING'){
                return true;
                }
            }
            $pelanggan = $transaksi->pelanggan;
            $order = $transaksi->stock_jenis;
        } else {
            $response = [
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
            ];
            return response()->json($response, 200);
        }

        $result = DB::transaction(function () use ($pelanggan, $order, $transaksi) {
            $ppn = $transaksi->ppn;
            $pphRp=0;
            if($ppn>0){
                if($transaksi->total_harga>0){
                $ppnRp = ($transaksi->total_harga / 100) * $ppn;
                }
            }
            $pph = $transaksi->pph;
            $pphRp=0;
            if($pph>0){
                if($transaksi->total_biaya>0){
                $pphRp = ($transaksi->total_biaya / 100) * $pph;
                }
            }
            $ongkir = $transaksi->pengiriman??0;
            $biaya_lain = $transaksi->biaya_lain??0;
            $diskon = $transaksi->diskon??0;
            $total = $transaksi->total;
            // $total = $transaksi->total + $shipping->biaya;
            $duration = 100000;
            if (!empty(website()->trx_duration_offline)) {
                $duration = website()->trx_duration_offline;
            }
            $jatuh_tempo= date('Y-m-d', strtotime('+7 days', strtotime(date('Y-m-d'))));
            if($transaksi->invoice){
                $jatuh_tempo=$transaksi->invoice->tgl_jatuh_tempo??$jatuh_tempo;
                $tgl1 = new DateTime(date('Y-m-d'));
                $tgl2 = new DateTime($jatuh_tempo);
                $jarak = $tgl2->diff($tgl1);
                $duration=$jarak->s??$duration;
            }
            dd($duration);
            $invoice = $transaksi->nomor;
            $trans = [
                'invoice' => $invoice,
                'amount' => $total,
                'deskription' => 'Tagihan Pembelian Barang di ' . website($transaksi->website_id)->nama_website,
                'duration' => $duration,
            ];


            $provinsi = $pelanggan->provinsi->name;
            $kota = $pelanggan->kota->name;
            $kecamatan = $pelanggan->kecamatan->name;
            $hp = gantiformat($pelanggan->hp);
            $customer = [
                'given_names' => $pelanggan->nama_depan,
                'surname' => $pelanggan->nama_belakang,
                'email' => $pelanggan->email,
                'mobile_number' => $hp,
                'address' => [
                    [
                        'city' => $kota,
                        'country' => 'Indonesia',
                        'postal_code' => $pelanggan->pos,
                        'state' => $provinsi,
                        'street_line1' => $pelanggan->alamat,
                        'street_line2' => $kecamatan,
                    ]
                ]
            ];

            $successUrl = config('app.url') . '/transaksi/confirs/' . $invoice;
            $failurUrl = config('app.url') . '/transaksi/failed/' . $invoice;
            // dd($order);
            $data = array();
            foreach ($order as $index => $i) {
                $data[] = array(
                    'name' => $i->produk->nama_produk,
                    'quantity' => $i->jumlah,
                    'price' => $i->harga_final,
                );
            }

            $item = $data;


            $xendit = new XenditController();
            $proses = $xendit->createInvoice($trans, $customer, $item, $ongkir, $ppnRp, $pphRp,$diskon,$biaya_lain, $successUrl, $failurUrl);
            if ($proses['status'] == 'PENDING') {
                $xenvoice = Xeninvoice::create([
                    'transaksi_id' => $transaksi->id,
                    'xen_id' => $proses['id'],
                    'xen_user_id' => $proses['user_id'],
                    'xen_external_id' => $proses['external_id'],
                    'xen_status' => $proses['status'],
                    'xen_invoice_url' => $proses['invoice_url'],
                    'xen_expiry_date' => $proses['expiry_date'],
                ]);
                $path = 'image/transaksi/qrbayar';
                if (!file_exists($path)) {
                    mkdir($path, 775, true);
                }
                QrCode::format('svg')->size(60)->generate($proses['invoice_url'], 'image/transaksi/qrbayar' . '/' . $invoice . '.svg');


                return true;
            } else {
                return false;
            }
        });
        if ($result == false) {
            return redirect('/transaksi')->with(['alert' => 'error', 'message' => 'Gagal Membuat Tagihan']);
        } else {
            return redirect('/transaksi')->with(['alert' => 'success', 'message' => 'Berhasil Membuat Tagihan']);
        }
    }
}
