<?php

namespace App\Http\Controllers;

use App\Models\StockJenis;
use App\Models\StockOrder;
use App\Repositori\GudangTransRepositori;
use App\Repositori\StockJenisRepositori;
use App\Repositori\StockOrderRepositori;
use App\Repositori\StockRepositori;
use App\Repositori\StockTransferRepositori;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use PDF;
use stdClass;

class StockTransferController extends Controller
{
    protected $stockOrderRepo;
    protected $gudangTransRepo;
    protected $stockRepo;
    protected $stockJenisRepo;
    protected $stockTransferRepo;
    public function __construct()
    {
        $this->stockOrderRepo = new StockOrderRepositori();
        $this->gudangTransRepo = new GudangTransRepositori();
        $this->stockRepo = new StockRepositori();
        $this->stockJenisRepo = new StockJenisRepositori();
        $this->stockTransferRepo = new StockTransferRepositori();
        $this->middleware('permission:stocktransfer|stocktransfer-list|stocktransfer-create|stocktransfer-edit|stocktransfer-delete|stocktransfer-laporan|stocktransfer-terima', ['only' => ['index', 'show']]);
        $this->middleware('permission:stocktransfer|stocktransfer-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:stocktransfer|stocktransfer-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:stocktransfer|stocktransfer-delete', ['only' => ['destroy']]);
        $this->middleware('permission:stocktransfer|stocktransfer-terima', ['only' => ['terima','validTerima']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stocktransfer = $this->stockOrderRepo->getWhere(null)->orderBy('created_at', 'desc');

        if (!empty($request->from_date) and !empty($request->to_date)) {
            $stocktransfer->whereBetween('tgl', array($request->from_date, $request->to_date));
        }
        if ($request->nomor) {
            $stocktransfer->whereIn('nomor', $request->nomor);
        }
        if ($request->status_order) {
            $stocktransfer->whereIn('status_order', $request->status_order);
        }
        if ($request->website) {
            $stocktransfer->whereIn('website_id', $request->website);
        }


        if (auth()->user()->hasPermissionTo('show-all')) {
        } else {
            $stocktransfer->where('website_id', website()->id);
        }


        $stocktransfer->where('jenis', 'stocktransfer');

        $stocktransfer = $stocktransfer->get();
        if ($request->ajax()) {
            return Datatables::of($stocktransfer)
                ->addIndexColumn()
                ->addColumn('nomor', function ($row) {
                    $nomor = '<a href="' . url("stock-transfer/detail/" . $row->nomor) . '" class="text-body fw-bold">' . $row->nomor . '</a>';
                    return $nomor;
                })
                ->addColumn('tgl', function ($row) {
                    $tgl = tglIndo($row->tgl);
                    return $tgl;
                })
                ->addColumn('dari', function ($row) {
                    $data = $row->stock_jenis->where('jenis', 2)->first()->gudang->nama;
                    return $data;
                })
                ->addColumn('ke', function ($row) {
                    $data = $row->stock_jenis->where('jenis', 1)->first()->gudang->nama;
                    return $data;
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
                    $label = 'Detail';
                    if (auth()->user()->hasPermissionTo('stocktransfer') or auth()->user()->hasPermissionTo('stocktransfer-terima')) {
                        if ($row->status_order == 'dikirim') {
                            $label = 'Konfirmasi & Detail';
                        }
                    }
                    $btn = $btn . ' <li><a href="' . url('stock-transfer/detail/' . $row->nomor) . '" class="dropdown-item" id="btnDetail" data-id="' . $row->id . '" >' . $label . '</a></li>';
                    if ($row->status_order <> 'selesai' and $row->status_order <> 'dikirim'  and $row->status_order <> 'batal') {
                        if (auth()->user()->hasPermissionTo('stocktransfer') or auth()->user()->hasPermissionTo('stocktransfer-edit')) {
                            $btn = $btn . ' <li><a href="' . url('stock-transfer/' . enc($row->id) . '/edit') . '" class="dropdown-item" id="btnUbah" data-id="' . $row->id . '" >Ubah</a></li>';
                        }
                        if (auth()->user()->hasPermissionTo('stocktransfer') or auth()->user()->hasPermissionTo('stocktransfer-delete')) {
                            $btn = $btn . ' <li><a onclick="return confirm(`Yakin Hapus Data ini?`);" href="' . url('stock-transfer/delete' . '/' . enc($row->id)) . '" class="dropdown-item" id="btnHapus">Hapus</a></li>';
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
                ->rawColumns(['tgl', 'nomor', 'dari', 'ke', 'tax', 'diskon', 'pengiriman', 'order_total', 'total', 'status_order', 'action'])
                ->make(true);
        }

        if ($request->print or $request->export or $request->pdf) {
            set_time_limit(300);
            $print = '';
            $export = '';

            if (isset($request->print)) {
                $print = 'ya';
            } elseif (isset($request->export)) {
                $export = 'ya';
            } elseif (isset($request->pdf)) {
                $pdf = PDF::loadview('laporan/laporan-stocktransfer', compact('stocktransfer', 'export', 'print'))->setPaper('a4', 'landscape');
                return $pdf->download('laporan_stocktransfer_' . date('Y-m-d') . '.pdf');
            }

            return view('laporan/laporan-stocktransfer', compact('stocktransfer', 'export', 'print'));
        }
        return view('stock-transfer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        session()->forget('cart');
        return view('stock-transfer.create');
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
            $filename = '';
            if ($request->status_order == 'dikirim') {
                if ($request->file) {
                    $request->validate([
                        'file' => 'image|mimes:jpg,jpeg,zip,rar,pdf|max:2024',
                    ], [
                        'max' => 'Maksimal Kapasitas File 2024KB',
                        'mimes' => 'Ekstensi Diizinkan jpg,jpeg,zip,rar,pdf'
                    ]);
                    $filename = 'kirim' . time() . '.' . $request->file->extension();
                    $request->file->move(public_path('image/transfer-stock/kirim'), $filename);
                } else {
                    return redirect()->back()->with('alert', 'Silahkan Upload Bukti Kirim');
                }
            }
            $d['tgl'] = $request->tgl;
            $d['jenis'] = 'stocktransfer';
            $d['nomor'] = $request->nota;
            $d['pemasok_id'] = 0;
            $d['sales_id'] = 0;
            $d['customer_id'] = 0;
            $d['tax'] = $request->ppn;
            $d['diskon'] = $request->diskon;
            $d['pengiriman'] = $request->pengiriman;
            $d['biaya_lain'] = 0;
            $d['order_total'] = $request->sub_total;
            $d['total'] = $request->total;
            $d['status_bayar'] = 0;
            $d['status_order'] = $request->status_order;
            $d['metode_bayar'] = 0;
            $d['bayar'] = 0;
            $d['file_kirim'] = $filename;
            $d['deskripsi'] = $request->deskripsi;
            $d['website_id'] = website()->id;
            $valid = 2;
            if ($request->status_order == 'selesai' or $request->status_order == 'dikirim') {
                $valid = 1;
            }
            foreach (session('cart') as $item) {
                $produkId = $item['produkId'];
                $dari = $item['dari'];
                $ke = $item['ke'];
                $quantity = $item['quantity'];
                $grosir = 2;
                $harga = str_replace('.', '', $item['price']);
                $cekStock = $this->stockRepo->getWhere(['produk_id' => $produkId])->first();
                $stock_dari = $this->cekStockGudang($produkId, $dari, null);
                $stock_dari_awal = $stock_dari->jumlah;
                $stock_ke = $this->cekStockGudang($produkId, $ke, null);
                $stock_ke_awal = $stock_ke->jumlah;

                $sisa_stock_dari = $stock_dari->jumlah - $quantity;
                $sisa_stock_ke = $stock_ke->jumlah + $quantity;

                // $this->gudangTransRepo->update($stock_dari->id, ['gudang_id' => $dari, 'jumlah' => $sisa_stock_dari, 'website_id' => $cekStock->website_id]);
                // $this->gudangTransRepo->update($stock_ke->id, ['gudang_id' => $ke, 'jumlah' => $sisa_stock_ke, 'website_id' => $cekStock->website_id]);



                $dataDari = [
                    'tgl' => $d['tgl'],
                    'jenis' => 2,
                    'nomor' => $d['nomor'],
                    'produk_id' => $produkId,
                    'gudang_id' => $dari,
                    'stock_awal' => $stock_dari_awal,
                    'jumlah' => $quantity,
                    'harga' => $cekStock->harga,
                    'harga_jual' => $cekStock->harga_jual,
                    'harga_grosir' => $cekStock->harga_grosir,
                    'harga_final' => $harga,
                    'grosir' => $grosir,
                    'valid' => 2,
                    'website_id' => $d['website_id'],
                ];
                $saveDari = $this->stockJenisRepo->store($dataDari);

                if ($saveDari) {
                    $this->stockTransferRepo->store([
                        'stock_jenis_id' => $saveDari,
                        'dari' => $dari,
                        'ke' => $ke,
                    ]);
                }

                $dataKe = [
                    'tgl' => $d['tgl'],
                    'jenis' => 1,
                    'nomor' => $d['nomor'],
                    'produk_id' => $produkId,
                    'gudang_id' => $ke,
                    'stock_awal' => $stock_ke_awal,
                    'jumlah' => $quantity,
                    'harga' => $cekStock->harga,
                    'harga_jual' => $cekStock->harga_jual,
                    'harga_grosir' => $cekStock->harga_grosir,
                    'harga_final' => $harga,
                    'grosir' => $grosir,
                    'valid' => 2,
                    'website_id' => $d['website_id'],
                ];
                $saveKe = $this->stockJenisRepo->store($dataKe);

                if ($saveKe) {
                    $this->stockTransferRepo->store([
                        'stock_jenis_id' => $saveKe,
                        'dari' => $ke,
                        'ke' => $dari,
                    ]);
                }
            }
            $this->stockOrderRepo->store($d);
            if ($valid == 1) {
                if ($request->status_order == 'selesai') {
                    $this->validTerima($d['nomor']);
                } elseif ($request->status_order == 'dikirim') {
                    $this->validKirim($d['nomor']);
                }
            }
        });
        session()->forget('cart');
        return redirect('/stock-transfer')->with(['alert' => 'success', 'message' => 'Data Berhasil Disimpan']);
    }

    public function cekStockGudang($produk_id, $gudang_id, $addNew = null)
    {
        $cek = $this->gudangTransRepo->getWhere(['produk_id' => $produk_id, 'gudang_id' => $gudang_id])->select('id', 'jumlah');
        if ($cek->count() > 0) {
            return $cek->first();
        } else {
            if ($addNew) {
                $this->gudangTransRepo->store([
                    'gudang_id' => $gudang_id,
                    'produk_id' => $produk_id,
                    'jumlah' => 0,
                    'website_id' => website()->id,
                ]);
                return $cek = $this->gudangTransRepo->getWhere(['produk_id' => $produk_id, 'gudang_id' => $gudang_id])->select('id', 'jumlah')->first();
            } else {
                $data = new stdClass();
                $data->jumlah = 0;
                return $data;
            }
        }
    }

    public function kode()
    {
        $response = [
            'success' => true,
            'message' => 'Berhasil',
            'data' => createCodeStockTransfer()
        ];
        return response()->json($response, 200);
    }

    public function validKirim($nomor)
    {
        $response = DB::transaction(function () use ($nomor) {
            $stocktransfer = $this->stockOrderRepo->getWhere(['nomor' => $nomor])->first();

            foreach ($stocktransfer->stock_jenis->where('jenis', 2) as $p) {
                $produkId = $p->produk_id;
                $gudangId = $p->gudang_id;
                $jumlah = $p->jumlah;

                // if ($p->jenis == 2) {
                $cekStock = $this->stockRepo->getWhere(['produk_id' => $produkId])->first();

                $cekStockGudang = $this->cekStockGudang($produkId, $gudangId, 1);

                $stockAwal = $cekStock->jumlah;
                $stockBaru = $stockAwal - $jumlah;
                $cekStock->update(['jumlah' => $stockBaru]);

                $stock_gudang_awal = $cekStockGudang->jumlah;
                $total = $stock_gudang_awal - $jumlah;
                $this->gudangTransRepo->update($cekStockGudang->id, ['jumlah' => $total]);

                $updateStockJenis = $this->stockJenisRepo->getId($p->id)->update(['stock_awal' => $stock_gudang_awal, 'valid' => 1]);
                // } elseif ($p->jenis == 1) {
                //     $cekStock = $this->stockRepo->getWhere(['produk_id' => $produkId])->first();

                //     $cekStockGudang = $this->cekStockGudang($produkId, $gudangId, 1);

                //     $stockAwal = $cekStock->jumlah;
                //     $stockBaru = $stockAwal + $jumlah;
                //     $cekStock->update(['jumlah' => $stockBaru]);

                //     $stock_gudang_awal = $cekStockGudang->jumlah;
                //     $total = $stock_gudang_awal + $jumlah;
                //     $this->gudangTransRepo->update($cekStockGudang->id, ['jumlah' => $total]);

                //     $updateStockJenis = $this->stockJenisRepo->getId($p->id)->update(['stock_awal' => $stock_gudang_awal]);
                // }
            }
            return true;
        });
        if ($response) {
            return true;
        }
        return false;
    }

    public function validTerima($nomor)
    {
        $response = DB::transaction(function () use ($nomor) {
            $stocktransfer = $this->stockOrderRepo->getWhere(['nomor' => $nomor])->first();

            foreach ($stocktransfer->stock_jenis->where('jenis', 1) as $p) {
                $produkId = $p->produk_id;
                $gudangId = $p->gudang_id;
                $jumlah = $p->jumlah;

                // if ($p->jenis == 2) {
                //     $cekStock = $this->stockRepo->getWhere(['produk_id' => $produkId])->first();

                //     $cekStockGudang = $this->cekStockGudang($produkId, $gudangId, 1);

                //     $stockAwal = $cekStock->jumlah;
                //     $stockBaru = $stockAwal - $jumlah;
                //     $cekStock->update(['jumlah' => $stockBaru]);

                //     $stock_gudang_awal = $cekStockGudang->jumlah;
                //     $total = $stock_gudang_awal - $jumlah;
                //     $this->gudangTransRepo->update($cekStockGudang->id, ['jumlah' => $total]);

                //     $updateStockJenis = $this->stockJenisRepo->getId($p->id)->update(['stock_awal' => $stock_gudang_awal]);
                // } elseif ($p->jenis == 1) {
                $cekStock = $this->stockRepo->getWhere(['produk_id' => $produkId])->first();

                $cekStockGudang = $this->cekStockGudang($produkId, $gudangId, 1);

                $stockAwal = $cekStock->jumlah;
                $stockBaru = $stockAwal + $jumlah;
                $cekStock->update(['jumlah' => $stockBaru]);

                $stock_gudang_awal = $cekStockGudang->jumlah;
                $total = $stock_gudang_awal + $jumlah;
                $this->gudangTransRepo->update($cekStockGudang->id, ['jumlah' => $total]);

                $updateStockJenis = $this->stockJenisRepo->getId($p->id)->update(['stock_awal' => $stock_gudang_awal, 'valid' => 1]);
                // }
            }
            return true;
        });
        if ($response) {
            return true;
        }
        return false;
    }

    public function terima(Request $request)
    {
        $response = DB::transaction(function () use ($request) {
            $id = dec($request->id);
            $data = $this->stockOrderRepo->getId($id);
            if ($data->status_order <> 'dikirim') {
                return redirect()->back()->with(['alert' => 'error', 'message' => 'Data Gagal Dikonfirmasi']);
            }
            $filename = '';
            if ($request->file) {
                $request->validate([
                    'file' => 'image|mimes:jpg,jpeg,zip,rar,pdf|max:2024',
                ], [
                    'max' => 'Maksimal Kapasitas File 2024KB',
                    'mimes' => 'Ekstensi Diizinkan jpg,jpeg,zip,rar,pdf'
                ]);
                $filename = 'kirim' . time() . '.' . $request->file->extension();
                $request->file->move(public_path('image/transfer-stock/kirim'), $filename);
            } else {
                return redirect()->back()->with('alert', 'Silahkan Upload Bukti Kirim');
            }
            $this->validTerima($data->nomor);
            $data->update(['status_order' => 'selesai', 'file_terima' => $filename]);
            return true;
        });
        if ($response) {
            return redirect()->back()->with(['alert' => 'success', 'message' => 'Data Berhasil Dikonfirmasi']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockTransfer  $stockOrder
     * @return \Illuminate\Http\Response
     */
    public function show($nomor)
    {
        $data = $this->stockOrderRepo->getWhere(['nomor' => $nomor])->first();
        $jenis = $this->stockJenisRepo->getWhere(['nomor' => $nomor])->selectRaw('*, sum(jumlah) as jumlah_total')->groupBy('gudang_id')->groupBy('produk_id')->get();
        return view('stock-transfer.detail', compact('data', 'jenis'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockTransfer  $stockOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = dec($id);
        $data = $this->stockOrderRepo->getId($id);
        if ($data->status_order == 'selesai' or $data->status_order == 'dikirim' or $data->status_order == 'batal') {
            return redirect()->back()->with('alert', 'Data Tidak Dapat Diubah');
        }

        session()->put('cart');
        foreach ($data->stock_jenis->where('jenis', 2) as $d) {
            if ($d->gudang_id == $d->stock_transfer->dari) {
                $request = new Request([
                    'produkId'   => $d->produk_id,
                    'dari' => $d->stock_transfer->dari,
                    'ke' => $d->stock_transfer->ke,
                    'jumlah' => $d->jumlah,
                    'harga' => $d->harga_final,
                    'grosir' => $d->grosir,
                ]);
            }

            $cart = new CartStockTransferController();
            $cart->add($request);
        }
        return view('stock-transfer.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockTransfer  $stockOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockOrder $stockOrder)
    {
        if (!session('cart')) {
            return redirect()->back()->with(['alert' => 'error', 'message' => 'Data Produk Masih Kosong']);
        }

        $response = DB::transaction(function () use ($request) {
            $filename = '';
            if ($request->status_order == 'dikirim') {
                if ($request->file) {
                    $request->validate([
                        'file' => 'image|mimes:jpg,jpeg,zip,rar,pdf|max:2024',
                    ], [
                        'max' => 'Maksimal Kapasitas Foto 2024KB',
                        'mimes' => 'Ekstensi Diizinkan jpg,jpeg,zip,rar,pdf'
                    ]);
                    $filename = 'kirim' . time() . '.' . $request->file->extension();
                    $request->file->move(public_path('image/transfer-stock/kirim'), $filename);
                } else {
                    return redirect()->back()->with('error', 'Upload Bukti Kirim');
                }
            }
            $d['tgl'] = $request->tgl;
            $d['jenis'] = 'stocktransfer';
            $d['nomor'] = $request->nota;
            $d['pemasok_id'] = 0;
            $d['sales_id'] = 0;
            $d['customer_id'] = 0;
            $d['tax'] = $request->ppn;
            $d['diskon'] = $request->diskon;
            $d['pengiriman'] = $request->pengiriman;
            $d['biaya_lain'] = 0;
            $d['order_total'] = $request->sub_total;
            $d['total'] = $request->total;
            $d['status_bayar'] = 0;
            $d['status_order'] = $request->status_order;
            $d['metode_bayar'] = 0;
            $d['bayar'] = 0;
            $d['file_kirim'] = $filename;
            $d['deskripsi'] = $request->deskripsi;
            $d['website_id'] = website()->id;
            $lastOrder = $this->stockJenisRepo->getWhere(['nomor' => $d['nomor']])->get();
            foreach ($lastOrder as $st) {
                $this->stockTransferRepo->getWhere(['stock_jenis_id' => $st->id])->delete();
            }
            $this->stockJenisRepo->deleteWhere(['nomor' => $d['nomor']]);
            $valid = 2;
            if ($request->status_order == 'selesai' or $request->status_order == 'dikirim') {
                $valid = 1;
            }
            foreach (session('cart') as $item) {
                $produkId = $item['produkId'];
                $dari = $item['dari'];
                $ke = $item['ke'];
                $quantity = $item['quantity'];
                $grosir = 2;
                $harga = str_replace('.', '', $item['price']);
                $cekStock = $this->stockRepo->getWhere(['produk_id' => $produkId])->first();
                $stock_dari = $this->cekStockGudang($produkId, $dari, null);
                $stock_dari_awal = $stock_dari->jumlah;
                $stock_ke = $this->cekStockGudang($produkId, $ke, null);
                $stock_ke_awal = $stock_ke->jumlah;
                // if ($stock_dari->jumlah < $quantity) {
                //     $response = [
                //         'success' => false,
                //         'message' => 'Stock Gudang Tidak Mencukupi',
                //     ];
                //     return $response;
                // }

                $sisa_stock_dari = $stock_dari->jumlah - $quantity;
                $sisa_stock_ke = $stock_ke->jumlah + $quantity;

                // $this->gudangTransRepo->update($stock_dari->id, ['gudang_id' => $dari, 'jumlah' => $sisa_stock_dari, 'website_id' => $cekStock->website_id]);
                // $this->gudangTransRepo->update($stock_ke->id, ['gudang_id' => $ke, 'jumlah' => $sisa_stock_ke, 'website_id' => $cekStock->website_id]);



                $dataDari = [
                    'tgl' => $d['tgl'],
                    'jenis' => 2,
                    'nomor' => $d['nomor'],
                    'produk_id' => $produkId,
                    'gudang_id' => $dari,
                    'stock_awal' => $stock_dari_awal,
                    'jumlah' => $quantity,
                    'harga' => $cekStock->harga,
                    'harga_jual' => $cekStock->harga_jual,
                    'harga_grosir' => $cekStock->harga_grosir,
                    'harga_final' => $harga,
                    'grosir' => $grosir,
                    'valid' => 2,
                    'website_id' => $d['website_id'],
                ];
                $saveDari = $this->stockJenisRepo->store($dataDari);

                if ($saveDari) {
                    $this->stockTransferRepo->store([
                        'stock_jenis_id' => $saveDari,
                        'dari' => $dari,
                        'ke' => $ke,
                    ]);
                }

                $dataKe = [
                    'tgl' => $d['tgl'],
                    'jenis' => 1,
                    'nomor' => $d['nomor'],
                    'produk_id' => $produkId,
                    'gudang_id' => $ke,
                    'stock_awal' => $stock_ke_awal,
                    'jumlah' => $quantity,
                    'harga' => $cekStock->harga,
                    'harga_jual' => $cekStock->harga_jual,
                    'harga_grosir' => $cekStock->harga_grosir,
                    'harga_final' => $harga,
                    'grosir' => $grosir,
                    'valid' => 2,
                    'website_id' => $d['website_id'],
                ];
                $saveKe = $this->stockJenisRepo->store($dataKe);

                if ($saveKe) {
                    $this->stockTransferRepo->store([
                        'stock_jenis_id' => $saveKe,
                        'dari' => $dari,
                        'ke' => $ke,
                    ]);
                }
            }
            $this->stockOrderRepo->update($request->id, $d);
            if ($valid == 1) {
                if ($request->status_order == 'selesai') {
                    $this->validTerima($d['nomor']);
                } elseif ($request->status_order == 'dikirim') {
                    $this->validKirim($d['nomor']);
                }
            }
        });
        session()->forget('cart');
        return redirect('/stock-transfer')->with(['alert' => 'success', 'message' => 'Data Berhasi Diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockTransfer  $stockOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = dec($id);
        $response = DB::transaction(function () use ($id) {
            $cek = $this->stockOrderRepo->getId($id);
            $nomor = $cek->nomor;
            $cek_transfer = $this->stockJenisRepo->getWhere(['nomor' => $nomor])->get();
            foreach ($cek_transfer as $ct) {
                $this->stockTransferRepo->getWhere(['stock_jenis_id' => $ct->id])->delete();
            }
            $this->stockJenisRepo->deleteWhere(['nomor' => $nomor]);
            $delete = $cek->delete();
            return true;
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
            $data = $this->stockOrderRepo->getWhere($q)->where('jenis', 'stocktransfer')->orderBy('nomor', 'asc')->select('nomor')->groupBy('nomor')->get();
        } else {
            $data = $this->stockOrderRepo->getWhere($q)->where('jenis', 'stocktransfer')->where('nomor', $search)->orderby('nomor', 'asc')->select('nomor')->groupBy('nomor')->first();
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
