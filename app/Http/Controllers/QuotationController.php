<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Kas;
use App\Models\Notifikasi;
use App\Models\Shipping;
use App\Models\Transaksi;
use App\Models\User;
use App\Repositori\QuotationRepositori;
use App\Repositori\StockOrderRepositori;
use App\Repositori\TransaksiRepositori;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DataTables;
use Image;
use PDF;
use Mail;

class QuotationController extends Controller
{
    protected $transaksiRepo;
    protected $quotationRepo;
    protected $stockOrderRepo;
    public function __construct()
    {
        $this->stockOrderRepo = new StockOrderRepositori();
        $this->quotationRepo = new QuotationRepositori();
        $this->transaksiRepo = new TransaksiRepositori();
    }

    public function index(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $quotation = $this->quotationRepo->getWhere($q);
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $quotation->whereBetween('tgl_dikeluarkan', array($request->from_date, $request->to_date));
        }

        if (!empty($request->status_quo)) {
            $quotation->where('status', $request->status_quo);
        }

        $quotation = $quotation->latest();
        $quotation = $quotation->get();
        if ($request->ajax()) {
            return Datatables::of($quotation)
                ->addIndexColumn()
                ->addColumn('no_quo', function ($row) {
                    return '<a target="_blank" href="/quotation/detail/' . $row->no_quo . '" data-original-title="Detail">' . $row->no_quo . '</a>';
                })
                ->addColumn('kode_trans', function ($row) {
                    return '<a target="_blank" href="/transaksi/detail/' . $row->kode_trans . '" data-original-title="Detail">' . $row->kode_trans . '</a>';
                })
                ->addColumn('tgl_dikeluarkan', function ($row) {
                    $tgl = date('Y-m-d', strtotime($row->tgl_dikeluarkan));
                    return tglIndo($tgl);
                })
                ->addColumn('tgl_kedaluwarsa', function ($row) {
                    $tgl = date('Y-m-d', strtotime($row->tgl_kedaluwarsa));
                    return tglIndo($tgl);
                })
                ->addColumn('preview', function ($row) {
                    return '<a target="_blank" href="/pdf/quotation/' . $row->no_quo . '.pdf" data-original-title="Lampiran" title="Lampiran">Lampiran</a>';
                })
                ->addColumn('status', function ($row) {
                    $status = '';
                    if ($row->status == 0) {
                        $status = '<span class="text-default">Draft</span>';
                    } elseif ($row->status == 1) {
                        $status = '<span class="text-success">Dikirim</span>';
                    } elseif ($row->status == 2) {
                        $status = '<span class="text-primary">Disetujui</span>';
                    } elseif ($row->status == 3) {
                        $status = '<span class="text-danger">Ditolak / Dibatalkan</span>';
                    } elseif ($row->status == 4) {
                        $status = '<span class="text-warning">Kedaluwarsa</span>';
                    }
                    return $status;
                })
                ->addColumn('toko', function ($row) {
                    return website($row->website_id)->nama_website;
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    $btn = $btn . ' <a href="/quotation/detail/' . $row->no_quo . '" data-toggle="tooltip"  data-id_quotation="' . $row->id . '" data-original-title="Detail" title="Detail" class="detail"> <i class="mdi mdi-eye"></i></a>';
                    if ($row->status == 0) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_quotation="' . $row->id . '" data-original-title="Kirim" title="Kirim Ke Email" class="ajukanQuotation"> <i class="mdi mdi-email-send"></i></a>';
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_quotation="' . $row->id . '" data-original-title="Edit" title="Ubah" class="editQuotation"> <i class="mdi mdi-square-edit-outline"></i></a>';
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_quotation="' . $row->id . '" data-original-title="Delete" title="Hapus" class="deleteQuotation"> <i class="mdi mdi-delete"></i></a>';
                    }elseif ($row->status >= 2) {

                    } else {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_quotation="' . $row->id . '" data-original-title="Edit" title="Ubah" class="editQuotation"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['no_quo', 'kode_trans', 'tgl_dikeluarkan', 'tgl_kedaluwarsa', 'preview', 'status', 'toko', 'action'])
                ->make(true);
        }
        $transaksi = $this->stockOrderRepo->getWhere($q)->where(['status_order' => 'proses', 'jenis' => 'penjualan'])->get();
        return view('quotation/quotation', compact('quotation', 'transaksi'));
    }

    public function kirimEmail($id)
    {
        $quotation = $this->quotationRepo->getId($id);
        $transaksi = $this->stockOrderRepo->getWhere(['nomor' => $quotation->kode_trans])->first();
        $data["email"] = $transaksi->pelanggan->email;
        $data["title"] = 'Surat Penawaran';
        // $data["body"] = $body;

        $files = [
            public_path('pdf/quotation/' . $quotation->no_quo . '.pdf'),
        ];
        if ($quotation->status == 0) {
            $this->quotationRepo->update($id,['status' => 1]);
            $quotation = $this->quotationRepo->getId($id);
        }

        $this->createPdf($id);

        $kirim = Mail::send('quotation.quotationPrint', compact('quotation', 'transaksi'), function ($message) use ($data, $files) {
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
        $quotation = $this->quotationRepo->getId($id);
        $transaksi = $this->stockOrderRepo->getWhere(['nomor' => $quotation->kode_trans])->first();
        // $pdf = PDF::loadview('quotation.quotationPrint', compact('transaksi', 'quotation'))->setPaper('a4', 'landscape');
        // return $pdf->download('detail-quotation-' . $transaksi->nomor);
        $path = 'pdf/quotation';
        if (!file_exists($path)) {
            mkdir($path, 755, true);
        }
        Pdf::loadview('quotation.quotationPrint', compact('transaksi', 'quotation'))
            ->setPaper('a4', 'landscape')
            ->save($path . '/' . $quotation->no_quo . '.pdf');
        // ->stream('laporan_penjualan_' . date('Y-m-d') . '.pdf');
        return true;
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'kode_trans' => 'required',
                'tgl_dikeluarkan' => 'required',
                'tgl_kedaluwarsa' => 'required',
                'kepada' => 'required',
                'di' => 'required',
                'pembuka' => 'required',
                'penutup' => 'required',
                'status' => 'required',
            ],
            [
                'required' => 'Data Belum Lengkap.'
            ]
        );


        $response = DB::transaction(function () use ($request) {
            $transaksi = $this->stockOrderRepo->getWhere(['nomor' => $request->kode_trans, 'status_order' => 'proses']);
            $transaksiDitemukan = $transaksi->count();
            if ($transaksiDitemukan < 1) {
                $response = [
                    'success' => false,
                    'message' => 'Transaksi Tidak Ditemukan',
                ];
                return response()->json($response, 200);
            }
            $transaksi = $transaksi->first();
            $no_quo = createCodeQuotation();
            if ($request->no_quo) {
                $no_quo = $request->no_quo;
            }
            if ($request->id_quotation) {
                $cek = Quotation::find($request->id_quotation);
                if ($request->status < $cek->status) {
                    $response = [
                        'success' => false,
                        'message' => 'Status tidak dapat digunakan',
                    ];
                    return $response;
                }
            }
            $save = Quotation::updateOrCreate([
                'id' => $request->id_quotation,
            ], [
                'no_quo' => $no_quo,
                'kode_trans' => $request->kode_trans,
                'kepada' => $request->kepada,
                'di' => $request->di,
                'tgl_dikeluarkan' => $request->tgl_dikeluarkan,
                'tgl_kedaluwarsa' => $request->tgl_kedaluwarsa,
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
        $quotation = $this->quotationRepo->getWhere(['no_quo' => $request->no])->first();
        $transaksi = $this->stockOrderRepo->getWhere(['nomor' => $quotation->kode_trans])->first();
        $shipping = Shipping::where('transaksi_id', $transaksi->id)->first();
        if (isset($request->print)) {
            $print = true;
            return view('quotation.quotationPrint', compact('transaksi', 'quotation', 'shipping', 'print'));
        }

        if (isset($request->pdf)) {
            $pdf = $request->pdf;
            $pdf = PDF::loadview('quotation.quotationPrint', compact('transaksi', 'quotation', 'shipping', 'pdf'))->setPaper('a4', 'portrait');
            return $pdf->download('detail-quotation-' . $transaksi->nomor);
        }
        return view('quotation.detail', compact('quotation', 'transaksi', 'shipping'));
    }


    public function edit($id)
    {
        $quotation = $this->quotationRepo->getId($id);
        return response()->json($quotation);
    }


    public function update(Request $request, Quotation $quotation)
    {
        //
    }


    public function destroy($id)
    {
        $del = $this->quotationRepo->getId($id)->delete();
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
        $request->move(public_path('image/quotation'), $filename);
        $tumb = Image::make('image/quotation/' . $filename)->resize(80, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $patchTumb = 'image/quotation/tumb';
        if (!file_exists($patchTumb)) {
            mkdir($patchTumb, 775, true);
        }
        $tumb->save(public_path('image/quotation/tumb/' . $filename));
        return $filename;
    }
}
