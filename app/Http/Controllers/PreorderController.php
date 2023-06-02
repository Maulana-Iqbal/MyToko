<?php

namespace App\Http\Controllers;

use App\Models\StockJenis;
use App\Models\StockOrder;
use App\Repositori\GudangTransRepositori;
use App\Repositori\StockJenisRepositori;
use App\Repositori\StockOrderRepositori;
use App\Repositori\StockRepositori;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use PDF;
use Mail;

class PreorderController extends Controller
{
    protected $stockOrderRepo;
    protected $gudangTransRepo;
    protected $stockRepo;
    protected $stockJenisRepo;
    public function __construct()
    {
        $this->stockOrderRepo = new StockOrderRepositori();
        $this->gudangTransRepo = new GudangTransRepositori();
        $this->stockRepo = new StockRepositori();
        $this->stockJenisRepo = new StockJenisRepositori();
        $this->middleware('permission:preorder|preorder-list|preorder-create|preorder-edit|preorder-delete|preorder-laporan', ['only' => ['index', 'show']]);
        $this->middleware('permission:preorder|preorder-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:preorder|preorder-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:preorder|preorder-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $preorder = $this->stockOrderRepo->getWhere(null)->orderBy('created_at', 'desc');

        if (!empty($request->from_date) and !empty($request->to_date)) {
            $preorder->whereBetween('tgl', array($request->from_date, $request->to_date));
        }
        if ($request->nomor) {
            $preorder->whereIn('nomor', $request->nomor);
        }
        if ($request->sales) {
            $preorder->whereIn('sales_id', $request->sales);
        }
        if ($request->status_bayar) {
            $preorder->whereIn('status_bayar', $request->status_bayar);
        }
        if ($request->status_order) {
            $preorder->whereIn('status_order', $request->status_order);
        }
        if ($request->website) {
            $preorder->whereIn('website_id', $request->website);
        }


        if (auth()->user()->hasPermissionTo('show-all')) {
        } else {
            $preorder->where('website_id', website()->id);
        }


        $preorder->where('jenis', 'preorder');

        $preorder = $preorder->get();
        if ($request->ajax()) {
            return Datatables::of($preorder)
                ->addIndexColumn()
                ->addColumn('nomor', function ($row) {
                    $nomor = '<a href="' . url("preorder/detail/" . $row->nomor ). '" class="text-body fw-bold">' . $row->nomor . '</a>';
                    return $nomor;
                })
                ->addColumn('tgl', function ($row) {
                    $tgl = tglIndo($row->tgl);
                    return $tgl;
                })
                ->addColumn('supplier', function ($row) {
                    $supplier = $row->pemasok->kode . ' - ' . $row->pemasok->perusahaan;
                    return $supplier;
                })
                ->addColumn('order_total', function ($row) {
                    $rupiah = uang($row->order_total);
                    return $rupiah;
                })
                ->addColumn('tax', function ($row) {
                    $rupiah = uang($row->tax);
                    return $rupiah;
                })
                ->addColumn('diskon', function ($row) {
                    $rupiah = uang($row->diskon);
                    return $rupiah;
                })
                ->addColumn('pengiriman', function ($row) {
                    $rupiah = uang($row->pengiriman);
                    return $rupiah;
                })
                ->addColumn('total', function ($row) {
                    $rupiah = uang($row->total);
                    return $rupiah;
                })
                ->addColumn('preview', function ($row) {
                    return '<a target="_blank" href="/pdf/preorder/' . $row->nomor . '.pdf" data-original-title="Lampiran" title="Lampiran">Lampiran</a>';
                })
                ->addColumn('status_bayar', function ($row) {
                    $status = '';
                    if ($row->status_bayar == 1) {
                        $status = '<label class="badge badge-outline-primary">Lunas</label>';
                    } elseif ($row->status_bayar == 2) {
                        $status = '<label class="badge badge-outline-warning">Belum Lunas</label>';
                    } elseif ($row->status_bayar == 3) {
                        $status = '<label class="badge badge-outline-danger">Belum Bayar</label>';
                    }
                    return $status;
                })
                ->addColumn('status_order', function ($row) {
                    $status = '';
                    if ($row->status_order == 'dikirim') {
                        $status = '<label class="badge badge-outline-success">Dikirim</label>';
                    } elseif ($row->status_order == 'proses') {
                        $status = '<label class="badge badge-outline-info">Proses</label>';
                    } elseif ($row->status_order == 'selesai') {
                        $status = '<label class="badge badge-outline-primary">Selesai</label>';
                    } elseif ($row->status_order == 'batal') {
                        $status = '<label class="badge badge-outline-danger">Batal</label>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn2 = '';
                    $btn = $btn . ' <li><a href="' . url('preorder/detail/' . $row->nomor) . '" class="dropdown-item" id="btnDetail" data-id="' . $row->id . '" >Detail</a></li>';
                    if ($row->status_order <> 'selesai' and $row->status_order <> 'batal') {
                        if (auth()->user()->hasPermissionTo('preorder') or auth()->user()->hasPermissionTo('preorder-edit')) {
                            $btn = $btn . ' <li><a href="' . url('preorder/' . $row->id . '/edit') . '" class="dropdown-item" id="btnUbah" data-id="' . $row->id . '" >Ubah</a></li>';
                        }
                    }
                    if ($row->status_order == 'proses') {
                        if (auth()->user()->hasPermissionTo('preorder') or auth()->user()->hasPermissionTo('preorder-delete')) {
                            $btn = $btn . ' <li><a onclick="return confirm(`Yakin Kirim ke Email Supplier?`);"  href="' . url('preorder/kirim-po' . '/' . $row->id) . '" class="dropdown-item" id="btnKirim" data-id="' . $row->id . '" >Kirim</a></li>';
                            $btn = $btn . ' <li><a onclick="return confirm(`Yakin Hapus Data ini?`);"  href="' . url('preorder/delete' . '/' . $row->id) . '" class="dropdown-item" id="btnHapus" data-id="' . $row->id . '" >Hapus</a></li>';
                        }
                    }
                    if ($btn <> '' or $btn2 <> '') {
                        $btn = '<div class="btn-group dropmiddle">
                             <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <ul class="dropdown-menu">
                        ' . $btn . '
                       
                        </ul>
                        </div>';
                    }
                    return $btn;
                })
                ->rawColumns(['tgl', 'nomor', 'sales', 'customer', 'preview', 'tax', 'diskon', 'pengiriman', 'order_total', 'total', 'status_bayar', 'status_order', 'action'])
                ->make(true);
        }

        if ($request->print or $request->export or $request->pdf) {

            $print = '';
            $export = '';

            if (isset($request->print)) {
                $print = 'ya';
            } elseif (isset($request->export)) {
                $export = 'ya';
            } elseif (isset($request->pdf)) {
                $pdf = PDF::loadview('laporan/laporan-preorder', compact('preorder', 'export', 'print'))->setPaper('a4', 'landscape');
                return $pdf->download('laporan_preorder_' . date('Y-m-d') . '.pdf');
            }

            return view('laporan/laporan-preorder', compact('preorder', 'export', 'print'));
        }
        return view('preorder.index');
    }


    public function kirimEmail(Request $request, $id)
    {
        $data = $this->stockOrderRepo->getWhere(['id' => $id])->first();
        $jenis = $this->stockJenisRepo->getWhere(['nomor' => $data->nomor])->selectRaw('*, sum(jumlah) as jumlah_total')->groupBy('produk_id')->get();
        $d["email"] = $data->pemasok->email;
        $d["title"] = 'Purchase Order';
        // $data["body"] = $body;

        $files = [
            public_path('pdf/preorder/' . $data->nomor . '.pdf'),
        ];
        if ($data->status_order == 'proses') {
            $this->stockOrderRepo->update($id, ['status_order' => 'dikirim']);
            $data = $this->stockOrderRepo->getWhere(['id' => $id])->first();
        }

        $this->createPdf($id);

        $kirim = Mail::send('preorder.preorderPrint', compact('data', 'jenis'), function ($message) use ($d, $files) {
            $message->to($d["email"], $d["email"])
                ->subject($d["title"]);

            foreach ($files as $file) {
                $message->attach($file);
            }
        });


        if ($request->segment(2) == 'kirim-po') {
            return redirect('/preorder')->with(['alert' => 'success', 'message' => 'Data Berhasil Dikirim']);
        } else {
            return true;
        }
        return false;
    }

    public function createPdf($id)
    {
        set_time_limit(300);
        $data = $this->stockOrderRepo->getWhere(['id' => $id])->first();
        $jenis = $this->stockJenisRepo->getWhere(['nomor' => $data->nomor])->selectRaw('*, sum(jumlah) as jumlah_total')->groupBy('produk_id')->get();
        $path = 'pdf/preorder';
        if (!file_exists($path)) {
            mkdir($path, 755, true);
        }
        Pdf::loadview('preorder.preorderPrint', compact('data', 'jenis'))
            ->setPaper('a4', 'landscape')
            ->save($path . '/' . $data->nomor . '.pdf');
        // ->stream('laporan_penjualan_' . date('Y-m-d') . '.pdf');
        return true;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        session()->forget('cart');
        return view('preorder.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!session('cart')) {
            return redirect()->back()->with(['alert' => 'error', 'message' => 'Data Produk Masih Kosong']);
        }
        $response = DB::transaction(function () use ($request) {
            $jumlahBayar = str_replace('.', '', $request->bayar);
            $d['tgl'] = $request->tgl;
            $d['jenis'] = 'preorder';
            $d['nomor'] = $request->nota;
            $d['pemasok_id'] = $request->supplier;
            $d['sales_id'] = 0;
            $d['customer_id'] = 0;
            $d['tax'] = $request->ppn;
            $d['diskon'] = $request->diskon;
            $d['pengiriman'] = $request->pengiriman;
            $d['biaya_lain'] = 0;
            $d['order_total'] = $request->sub_total;
            $d['total'] = $request->total;
            if ($jumlahBayar >= $request->total) {
                $d['status_bayar'] = 1;
            } elseif ($jumlahBayar <= 0) {
                $d['status_bayar'] = 3;
            } elseif ($jumlahBayar < $request->total) {
                $d['status_bayar'] = 2;
            }
            $d['status_order'] = $request->status_order;
            $d['metode_bayar'] = $request->metode_bayar;
            $d['deskripsi'] = $request->deskripsi;
            // $d['bayar'] = $jumlahBayar;
            $d['website_id'] = website()->id;
            $valid = 2;
            if ($d['status_order'] == 'selesai') {
                $valid = 1;
            }
            if (session('cart')) {
                foreach (session('cart') as $item) {
                    $produkId = $item['produkId'];
                    $gudangId = $item['gudangId'];
                    $quantity = $item['quantity'];

                    $cekStock = $this->stockRepo->getWhere(['produk_id' => $produkId])->first();
                    $stockGudang = $this->gudangTransRepo->getWhere(['produk_id' => $produkId, 'gudang_id' => $gudangId])->first();

                    $data = [
                        'tgl' => $d['tgl'],
                        'jenis' => 4,
                        'nomor' => $d['nomor'],
                        'produk_id' => $produkId,
                        'gudang_id' => $gudangId,
                        'stock_awal' => $stockGudang->jumlah,
                        'jumlah' => $quantity,
                        'harga' => $cekStock->harga,
                        'harga_jual' => $cekStock->harga_jual,
                        'harga_grosir' => $cekStock->harga_grosir,
                        'harga_final' => $cekStock->harga,
                        'grosir' => 2,
                        'valid' => $valid,
                        'website_id' => $d['website_id'],
                    ];
                    $this->stockJenisRepo->store($data);
                }
                $save = $this->stockOrderRepo->store($d);
                if ($save) {
                    if ($request->status_order == 'dikirim') {
                        $this->kirimEmail($request,$save);
                    } else {
                        $this->createPdf($save);
                    }
                }
            } else {
                return redirect()->back()->with(['alert' => 'error', 'message' => 'Data Produk Masih Kosong']);
            }
        });
        session()->forget('cart');
        return redirect('/preorder')->with(['alert' => 'success', 'message' => 'Data Berhasil Disimpan']);
    }

    public function kode()
    {
        $response = [
            'success' => true,
            'message' => 'Berhasil',
            'data' => createCodePreorder()
        ];
        return response()->json($response, 200);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Preorder  $stockOrder
     * @return \Illuminate\Http\Response
     */
    public function show($nomor)
    {
        $data = $this->stockOrderRepo->getWhere(['nomor' => $nomor])->first();
        $jenis = $this->stockJenisRepo->getWhere(['nomor' => $nomor])->selectRaw('*, sum(jumlah) as jumlah_total')->groupBy('produk_id')->get();
        return view('preorder.detail', compact('data', 'jenis'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Preorder  $stockOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->stockOrderRepo->getId($id);
        if ($data->status_order == 'selesai' or $data->status_order == 'batal') {
            return redirect()->back()->with('alert', 'Data Tidak Dapat Diubah');
        }

        session()->put('cart');
        foreach ($data->stock_jenis as $d) {
            $request = new Request([
                'produkId'   => $d->produk_id,
                'gudangId' => $d->gudang_id,
                'jumlah' => $d->jumlah,
            ]);

            $cart = new CartPreorderController();
            $cart->add($request);
        }
        return view('preorder.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Preorder  $stockOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockOrder $stockOrder)
    {
        if (!session('cart')) {
            return redirect()->back()->with(['alert' => 'error', 'message' => 'Data Produk Masih Kosong']);
        }
        $response = DB::transaction(function () use ($request) {
            $jumlahBayar = str_replace('.', '', $request->bayar);
            $d['tgl'] = $request->tgl;
            $d['jenis'] = 'preorder';
            $d['nomor'] = $request->nota;
            $d['pemasok_id'] = $request->supplier;
            $d['sales_id'] = 0;
            $d['customer_id'] = 0;
            $d['tax'] = $request->ppn;
            $d['diskon'] = $request->diskon;
            $d['pengiriman'] = $request->pengiriman;
            $d['biaya_lain'] = 0;
            $d['order_total'] = $request->sub_total;
            $d['total'] = $request->total;
            if ($jumlahBayar >= $request->total) {
                $d['status_bayar'] = 1;
            } elseif ($jumlahBayar <= 0) {
                $d['status_bayar'] = 3;
            } elseif ($jumlahBayar < $request->total) {
                $d['status_bayar'] = 2;
            }
            $d['status_order'] = $request->status_order;
            $d['metode_bayar'] = $request->metode_bayar;
            $d['deskripsi'] = $request->deskripsi;
            // $d['bayar'] = $jumlahBayar;
            $d['website_id'] = website()->id;
            $valid = 2;
            if ($d['status_order'] == 'selesai') {
                $valid = 1;
            }
            $deleteLastOrder = $this->stockJenisRepo->deleteWhere(['nomor' => $d['nomor']]);
            if (session('cart')) {
                foreach (session('cart') as $item) {
                    $produkId = $item['produkId'];
                    $gudangId = $item['gudangId'];
                    $quantity = $item['quantity'];

                    $cekStock = $this->stockRepo->getWhere(['produk_id' => $produkId])->first();
                    $stockGudang = $this->gudangTransRepo->getWhere(['produk_id' => $produkId, 'gudang_id' => $gudangId])->first();



                    $data = [
                        'tgl' => $d['tgl'],
                        'jenis' => 4,
                        'nomor' => $d['nomor'],
                        'produk_id' => $produkId,
                        'gudang_id' => $gudangId,
                        'stock_awal' => $stockGudang->jumlah,
                        'jumlah' => $quantity,
                        'harga' => $cekStock->harga,
                        'harga_jual' => $cekStock->harga_jual,
                        'harga_grosir' => $cekStock->harga_grosir,
                        'harga_final' => $cekStock->harga,
                        'grosir' => 2,
                        'valid' => $valid,
                        'website_id' => $d['website_id'],
                    ];
                    $this->stockJenisRepo->store($data);
                }
                $save = $this->stockOrderRepo->update($request->id, $d);
                if ($save) {
                    if ($request->status_order == 'dikirim') {
                        $this->kirimEmail($request,$request->id);
                    } else {
                        $this->createPdf($request->id);
                    }
                }
            } else {
                return redirect()->back()->with(['alert' => 'error', 'message' => 'Data Produk Masih Kosong']);
            }
        });
        session()->forget('cart');
        return redirect('/preorder')->with(['alert' => 'success', 'message' => 'Data Berhasil Diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Preorder  $stockOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = DB::transaction(function () use ($id) {
            $cek = $this->stockOrderRepo->getId($id);
            $nomor = $cek->nomor;
            $this->stockJenisRepo->deleteWhere(['nomor' => $nomor]);
            return $cek->delete();
        });
        if ($response) {
            return redirect()->back()->with(['alert' => 'success', 'message' => 'Berhasil Dihapus']);
        }
        return redirect()->back()->with(['alert' => 'error', 'message' => 'Gagal Dihapus']);
    }

    public function noSelect(Request $request)
    {
        $search = $request->search;
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        if ($search == '') {
            $data = $this->stockOrderRepo->getWhere($q)->where('jenis', 'preorder')->orderBy('nomor', 'asc')->select('nomor')->groupBy('nomor')->get();
        } else {
            $data = $this->stockOrderRepo->getWhere($q)->where('jenis', 'preorder')->where('nomor', $search)->orderby('nomor', 'asc')->select('nomor')->groupBy('nomor')->first();
        }

        $response = array();
        foreach ($data as $data) {
            $response[] = array(
                "id" => $data->nomor,
                "text" => $data->nomor
            );
        }
        return response()->json($response);
    }
}
