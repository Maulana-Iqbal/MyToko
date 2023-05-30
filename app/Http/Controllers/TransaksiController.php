<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kas;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\Xeninvoice;
use App\Models\DistrictOngkir;
use App\Models\Order;
use App\Models\Pembayaran;
use App\Models\ProvinceOngkir;
use App\Models\RegencyOngkir;
use App\Repositori\GudangTransRepositori;
use App\Repositori\HistoryStockRepositori;
use App\Repositori\OrderRepositori;
use App\Repositori\PelangganRepositori;
use App\Repositori\StockJenisRepositori;
use App\Repositori\StockOrderRepositori;
use App\Repositori\StockRepositori;
use App\Repositori\TransaksiRepositori;
use App\Services\HistoryStockService;
use App\Services\ProdukService;
use App\Services\StockService;
use App\Services\TransaksiService;
use Illuminate\Support\Facades\DB;
use PDF;
use DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TransaksiController extends Controller
{
    protected $transaksiService;
    protected $historyStockRepo;
    protected $pelangganRepo;
    protected $orderRepo;
    protected $stockService;
    protected $transaksiRepo;
    protected $historyStockService;
    protected $gudangTransRepo;
    protected $stockRepo;
    protected $stockOrderRepo;
    protected $stockJenisRepo;
    public function __construct()
    {
        $this->transaksiService = new TransaksiService();
        $this->historyStockRepo = new HistoryStockRepositori();
        $this->pelangganRepo = new PelangganRepositori();
        $this->orderRepo = new OrderRepositori();
        $this->stockService = new StockService();
        $this->transaksiRepo = new TransaksiRepositori();
        $this->historyStockService = new HistoryStockService();
        $this->gudangTransRepo = new GudangTransRepositori();
        $this->stockRepo = new StockRepositori();
        $this->stockOrderRepo = new StockOrderRepositori();
        $this->stockJenisRepo = new StockJenisRepositori();
        $this->middleware('permission:transaksi|transaksi-list|transaksi-create|transaksi-edit|transaksi-show|transaksi-invoice|transaksi-pembayaran|transaksi-shipping|transaksi-verifikasi', ['only' => ['index']]);
        $this->middleware('permission:transaksi|transaksi-create', ['only' => ['baru', 'store']]);
        $this->middleware('permission:transaksi|transaksi-edit', ['only' => ['ubah', 'update']]);
        $this->middleware('permission:transaksi|transaksi-show', ['only' => ['detailTransaksi']]);
        $this->middleware('permission:transaksi|transaksi-invoice', ['only' => ['invoice', 'createInvoiceOffline']]);
        $this->middleware('permission:transaksi|transaksi-shipping', ['only' => ['savePengiriman', 'updatePengiriman']]);
        $this->middleware('permission:transaksi|transaksi-pembayaran', ['only' => ['prosesPembayaran']]);
        $this->middleware('permission:transaksi|transaksi-verifikasi', ['only' => ['verifikasiTrans']]);
        $this->middleware('permission:transaksi|transaksi-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $transaksi = $this->stockOrderRepo->getWhere(null)->orderBy('created_at', 'desc');

        if (!empty($request->from_date) and !empty($request->to_date)) {
            $transaksi->whereBetween('tgl', array($request->from_date, $request->to_date));
        }
        if ($request->nomor) {
            $transaksi->whereIn('nomor', $request->nomor);
        }
        if ($request->supplier) {
            $transaksi->whereIn('pemasok_id', $request->supplier);
        }
        if ($request->status_bayar) {
            $transaksi->whereIn('status_bayar', $request->status_bayar);
        }
        if ($request->status_order) {
            $transaksi->whereIn('status_order', $request->status_order);
        }
        if ($request->website) {
            $transaksi->whereIn('website_id', $request->website);
        }


        if (auth()->user()->hasPermissionTo('show-all')) {
        } else {
            $transaksi->where('website_id', website()->id);
        }


        $transaksi->where('jenis', 'penjualan');

        $transaksi = $transaksi->get();
        if ($request->ajax()) {
            return Datatables::of($transaksi)
                ->addIndexColumn()
                ->addColumn('nomor', function ($row) {

                    $kode_trans = '<span class="text-body fw-bold"><a href="/transaksi/detail/' . $row->nomor . '">' . $row->nomor . '</a></span>';
                    return $kode_trans;
                })
                ->addColumn('tgl', function ($row) {
                    $tgl = tglIndo($row->tgl);
                    return $tgl;
                })
                ->addColumn('customer', function ($row) {
                    $data = $row->pelanggan->nama_depan.' '.$row->pelanggan->nama_belakang;
                    return $data;
                })
                ->addColumn('perusahaan', function ($row) {
                    $data = $row->pelanggan->perusahaan;
                    return $data;
                })
                ->addColumn('subtotal', function ($row) {
                    $data = uang($row->subtotal);
                    return $data;
                })
                ->addColumn('ppn', function ($row) {
                    $data = uang($row->ppn);
                    return $data;
                })
                ->addColumn('pph', function ($row) {
                    $data = uang($row->pph);
                    return $data;
                })
                ->addColumn('pengiriman', function ($row) {
                    $data = uang($row->pengiriman);
                    return $data;
                })
                ->addColumn('biayaLain', function ($row) {
                    $data = uang($row->biaya_lain);
                    return $data;
                })
                ->addColumn('total', function ($row) {
                    $data = uang($row->total);
                    return $data;
                })
                ->addColumn('metode_bayar', function ($row) {
                    $data = strtoupper($row->metode_bayar);
                    return $data;
                })
                ->addColumn('status_order', function ($row) {
                    if ($row->status_order == 'proses') {
                        return '<span class="badge badge-info-lighten">Proses</span>';
                    } elseif ($row->status_order == 'selesai') {
                        return '<span class="badge badge-success-lighten">Selesai</span>';
                    } elseif ($row->status_order == 'batal') {
                        return '<span class="badge badge-danger-lighten">Batal</span>';
                    } elseif ($row->status_order == 'dikirim') {
                        return '<span class="badge badge-primary-lighten">Dikirim</span>';
                    }
                })
                ->addColumn('deskripsi', function ($row) {
                    return $row->deskripsi;
                })
                ->addColumn('toko', function ($row) {
                    return website($row->website_id)->nama_website;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn2 = '';
                    $btn = $btn . ' <li><a href="'.url('transaksi/detail/'.$row->nomor).'" class="dropdown-item">Detail</a></li>';
                    if ($row->status_order == 'proses') {
                        if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-edit')) {
                            $btn2 = $btn2 . ' <li><a href="/transaksi/ubah/' . $row->id . '" class="dropdown-item" >Ubah</a></li>';
                        }
                        if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-delete')) {
                            $btn2 = $btn2 . ' <li><a onclick="return confirm(`Yakin Hapus Data ini?`);"  href="' . url('transaksi/delete' . '/' . $row->id) . '" class="dropdown-item" id="btnHapus" data-id="' . $row->id . '" >Hapus</a></li>';
                        }
                    }
                    if ($row->status_order == 'dikirim') {
                        if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-edit')) {
                            $btn2 = $btn2 . ' <li><a href="/transaksi/ubah/' . $row->id . '" class="dropdown-item" >Ubah</a></li>';
                        }
                    }

               


                    if ($btn <> '' or $btn2 <> '') {
                        $btn = '<div class="btn-group dropmiddle">
                                 <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Aksi
                                </button>
                                <ul class="dropdown-menu">
                            ' . $btn . '
                            <li><hr class="dropdown-divider"></li>
                            ' . $btn2 . '
                            </ul>
                            </div>';
                    }
                    return $btn;
                })
                ->rawColumns(['nomor', 'tgl', 'customer','perusahaan','subtotal', 'pph', 'ppn', 'toko','pengiriman', 'biayaLain', 'total', 'metode_bayar', 'status_order', 'deskripsi', 'action'])
                ->make(true);
        }
        return view('transaksi/transaksi');
    }

    public function show($nomor)
    {
        $data = $this->stockOrderRepo->getWhere(['nomor' => $nomor])->first();
        return view('transaksi.detail', compact('data'));
    }

    public function baru()
    {
        session()->forget('cart');
        $title = 'Penjualan';
        return view('transaksi.transaksiBaru', compact(['title']));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if (!session('cart')) {
            return redirect()->back()->with(['alert' => 'error', 'message' => 'Data Produk Masih Kosong']);
        }

        $response = DB::transaction(function () use ($request) {
            $jumlahBayar = cleanUang($request->bayar);
            $d['tgl'] = $request->tgl;
            $d['jenis'] = 'penjualan';
            $d['nomor'] = $request->nota;
            $d['pemasok_id'] = 0;
            $d['sales_id'] = $request->sales;
            $d['customer_id'] = $request->customer;
            $d['tax'] = $request->input_ppn;
            $d['ppn'] = $request->input_ppn;
            $d['pph'] = $request->input_pph;
            $d['diskon'] = cleanUang($request->input_diskon);
            $d['biaya_lain'] = cleanUang($request->biayaLain);
            $d['total_biaya'] = $request->total_biaya;
            $d['total_harga'] = $request->total_harga;
            $d['pengiriman'] = cleanUang($request->biayaPengiriman);
            // $d['resi'] = $request->resi;
            // $d['kurir'] = $request->kurir;
            $d['order_total'] = cleanUang($request->sub_total);
            $d['total'] = cleanUang($request->total);
            if ($jumlahBayar >= cleanUang($request->total)) {
                $d['status_bayar'] = 1;
            } elseif ($jumlahBayar <= 0) {
                $d['status_bayar'] = 3;
            } elseif ($jumlahBayar < $request->total) {
                $d['status_bayar'] = 2;
            }
            $d['status_order'] = $request->status_order;
            $d['metode_bayar'] = $request->metode_bayar;
            $d['deskripsi'] = $request->deskripsi;
            $d['bayar'] = $jumlahBayar;
            $d['website_id'] = website()->id;

            foreach (session('cart') as $item) {
                $produkId = $item['produkId'];
                $gudangId = $item['gudangId'];
                $quantity = $item['jumlah'];
                $grosir = $item['grosir'];
                $harga = cleanUang($item['sub']);
                $biaya = cleanUang($item['biaya']);

                $cekStock = $this->stockRepo->getWhere(['produk_id' => $produkId])->first();
                $stockGudang = $this->gudangTransRepo->getWhere(['produk_id' => $produkId, 'gudang_id' => $gudangId])->first();
                $valid = 2;
                if ($d['status_order'] == 'selesai' or $d['status_order'] == 'dikirim') {
                    $valid = 1;
                }
                $data = [
                    'tgl' => $d['tgl'],
                    'jenis' => 2,
                    'nomor' => $d['nomor'],
                    'produk_id' => $produkId,
                    'gudang_id' => $gudangId,
                    'stock_awal' => $stockGudang->jumlah,
                    'jumlah' => $quantity,
                    'harga' => $cekStock->harga,
                    'harga_jual' => $cekStock->harga_jual,
                    'harga_grosir' => $cekStock->harga_grosir,
                    'harga_final' => $harga,
                    'biaya' => $biaya,
                    'grosir' => $grosir,
                    'valid' => $valid,
                    'website_id' => $d['website_id'],
                ];
                $this->stockJenisRepo->store($data);
            }
            $save = $this->stockOrderRepo->store($d);
            $biayaPengiriman = 0;
            if (!empty($request->biayaPengiriman) or !empty($request->resi) or !empty($request->kurir) or !empty($request->file)) {
                if ($request->file) {
                    $request->validate([
                        'file' => 'required|mimes:png,jpg,jpeg|max:1024',
                    ], [
                        'mimes' => 'Ekstensi Gambar Diizinkan .png / .jpg / .jpeg',
                        'required' => 'Silahkan Upload Gambar',
                        'max' => 'Maksimal Size 1024KB/1MB'
                    ]);
                    $filename = time() . '.' . $request->file->extension();
                    $request->file->move(public_path('image/transaksi/resi'), $filename);
                } else {
                    $cekPengiriman = Shipping::where('transaksi_id', $save)->first();
                    if (!empty($cekPengiriman->file)) {
                        $filename = $cekPengiriman->file;
                    } else {
                        $filename = '';
                    }
                }
                $biaya = 0;
                if (!empty($request->biayaPengiriman)) {
                    $biaya = cleanUang($request->biayaPengiriman);
                }
                $pengiriman = Shipping::updateOrCreate([
                    'transaksi_id' => $save
                ], [
                    'kurir' => $request->kurir ?? '',
                    'resi' => $request->resi ?? '',
                    'biaya' => $biaya,
                    'file' => $filename ?? '',
                ]);
            }

            if ($valid == 1) {
                $this->validOrder($d['nomor']);
            }
        });
        session()->forget('cart');
        return redirect('/transaksi')->with(['alert' => 'success', 'message' => 'Data Berhasi Disimpan']);


    }

    public function ubah($id)
    {
        session()->forget('cart');
        $transaksi = $this->stockOrderRepo->getId($id);
        foreach ($transaksi->stock_jenis as $item) {
            $cartId = $item->produk_id . $item->gudang_id;
            $cart = session()->get('cart', []);
    $harga_final=$item->harga_final-$item->biaya;
            $cart[$cartId] = [
                "id" => $cartId,
                'produkId' => $item->produk_id,
                'gudangId' => $item->gudang_id,
                'gudangName' => $item->gudang->nama,
                "name" => $item->produk->nama_produk,
                "jumlah" => $item->jumlah,
                "harga_modal" => $item->produk->stock->harga,
                "biaya" => $item->biaya,
                "harga_final" => $harga_final,
                "sub" => $item->harga_final,
                "grosir" => $item->grosir,
                "image" => $item->produk->gambar_utama
            ];
            session()->put('cart', $cart);
        }
        $title = 'Ubah Penjualan';
        return view('transaksi.transaksiUbah', compact(['title', 'transaksi']));
    }



    public function update(Request $request)
    {
        $cekData = $this->stockOrderRepo->getId($request->id);
        if ($cekData->status_order == 'dikirim') {
            $jumlahBayar = str_replace('.', '', $request->bayar);
            $total=$cekData->total-$cekData->pengiriman;
            $pengiriman=cleanUang($request->biayaPengiriman);
            $total=$total+$pengiriman;
            if (!empty($request->biayaPengiriman) or !empty($request->resi) or !empty($request->kurir) or !empty($request->file)) {
                if ($request->file) {
                    $request->validate([
                        'file' => 'required|mimes:png,jpg,jpeg|max:1024',
                    ], [
                        'mimes' => 'Ekstensi Gambar Diizinkan .png / .jpg / .jpeg',
                        'required' => 'Silahkan Upload Gambar',
                        'max' => 'Maksimal Size 1024KB/1MB'
                    ]);
                    $filename = time() . '.' . $request->file->extension();
                    $request->file->move(public_path('image/transaksi/resi'), $filename);
                } else {
                    $cekPengiriman = Shipping::where('transaksi_id', $cekData->id)->first();
                    if (!empty($cekPengiriman->file)) {
                        $filename = $cekPengiriman->file;
                    } else {
                        $filename = '';
                    }
                }
                
                $pengiriman = Shipping::updateOrCreate([
                    'transaksi_id' => $cekData->id
                ], [
                    'kurir' => $request->kurir ?? '',
                    'resi' => $request->resi ?? '',
                    'biaya' => $pengiriman,
                    'file' => $filename ?? '',
                ]);
            }
            if ($jumlahBayar >= $request->total) {
                $d['status_bayar'] = 1;
            } elseif ($jumlahBayar <= 0) {
                $d['status_bayar'] = 3;
            } elseif ($jumlahBayar < $request->total) {
                $d['status_bayar'] = 2;
            }
            $d['status_order'] = $request->status_order;
            $d['metode_bayar'] = $request->metode_bayar;
            $d['pengiriman'] = cleanUang($request->biayaPengiriman);
            $d['total'] = $total;
            $d['bayar'] = $jumlahBayar;
            $this->stockOrderRepo->update($cekData->id, $d);

            return redirect('/transaksi')->with(['alert' => 'success', 'message' => 'Data Berhasi Diperbarui']);
        }
        if (!session('cart')) {
            return redirect()->back()->with(['alert' => 'error', 'message' => 'Data Produk Masih Kosong']);
        }

        $response = DB::transaction(function () use ($request,$cekData) {
            $jumlahBayar = cleanUang($request->bayar);
            $d['tgl'] = $request->tgl;
            $d['jenis'] = 'penjualan';
            $d['nomor'] = $request->nota;
            $d['pemasok_id'] = 0;
            $d['sales_id'] = $request->sales;
            $d['customer_id'] = $request->customer;
            $d['tax'] = $request->input_ppn;
            $d['ppn'] = $request->input_ppn;
            $d['pph'] = $request->input_pph;
            $d['diskon'] = cleanUang($request->input_diskon);
            $d['biaya_lain'] = cleanUang($request->biayaLain);
            $d['total_biaya'] = $request->total_biaya;
            $d['total_harga'] = $request->total_harga;
            $d['pengiriman'] = cleanUang($request->biayaPengiriman);
            // $d['resi'] = $request->resi;
            // $d['kurir'] = $request->kurir;
            $d['order_total'] = cleanUang($request->sub_total);
            $d['total'] = cleanUang($request->total);
            if ($jumlahBayar >= cleanUang($request->total)) {
                $d['status_bayar'] = 1;
            } elseif ($jumlahBayar <= 0) {
                $d['status_bayar'] = 3;
            } elseif ($jumlahBayar < $request->total) {
                $d['status_bayar'] = 2;
            }
            $d['status_order'] = $request->status_order;
            $d['metode_bayar'] = $request->metode_bayar;
            $d['deskripsi'] = $request->deskripsi;
            $d['bayar'] = $jumlahBayar;
            $d['website_id'] = website()->id;
            $deleteLastOrder = $this->stockJenisRepo->deleteWhere(['nomor' => $cekData->nomor]);
            foreach (session('cart') as $item) {
                $produkId = $item['produkId'];
                $gudangId = $item['gudangId'];
                $quantity = $item['jumlah'];
                $grosir = $item['grosir'];
                $harga = cleanUang($item['sub']);
                $biaya = cleanUang($item['biaya']);

                $cekStock = $this->stockRepo->getWhere(['produk_id' => $produkId])->first();
                $stockGudang = $this->gudangTransRepo->getWhere(['produk_id' => $produkId, 'gudang_id' => $gudangId])->first();
                $valid = 2;
                if ($d['status_order'] == 'selesai' or $d['status_order'] == 'dikirim') {
                    $valid = 1;
                }
                $data = [
                    'tgl' => $d['tgl'],
                    'jenis' => 2,
                    'nomor' => $d['nomor'],
                    'produk_id' => $produkId,
                    'gudang_id' => $gudangId,
                    'stock_awal' => $stockGudang->jumlah,
                    'jumlah' => $quantity,
                    'harga' => $cekStock->harga,
                    'harga_jual' => $cekStock->harga_jual,
                    'harga_grosir' => $cekStock->harga_grosir,
                    'harga_final' => $harga,
                    'biaya' => $biaya,
                    'grosir' => $grosir,
                    'valid' => $valid,
                    'website_id' => $cekData->website_id,
                ];
                $this->stockJenisRepo->store($data);
            }
            $save = $this->stockOrderRepo->update($cekData->id,$d);
            $biayaPengiriman = 0;
            if (!empty($request->biayaPengiriman) or !empty($request->resi) or !empty($request->kurir) or !empty($request->file)) {
                if ($request->file) {
                    $request->validate([
                        'file' => 'required|mimes:png,jpg,jpeg|max:1024',
                    ], [
                        'mimes' => 'Ekstensi Gambar Diizinkan .png / .jpg / .jpeg',
                        'required' => 'Silahkan Upload Gambar',
                        'max' => 'Maksimal Size 1024KB/1MB'
                    ]);
                    $filename = time() . '.' . $request->file->extension();
                    $request->file->move(public_path('image/transaksi/resi'), $filename);
                } else {
                    $cekPengiriman = Shipping::where('transaksi_id', $cekData->id)->first();
                    if (!empty($cekPengiriman->file)) {
                        $filename = $cekPengiriman->file;
                    } else {
                        $filename = '';
                    }
                }
                $biaya = 0;
                if (!empty($request->biayaPengiriman)) {
                    $biaya = cleanUang($request->biayaPengiriman);
                }
                $pengiriman = Shipping::updateOrCreate([
                    'transaksi_id' => $cekData->id
                ], [
                    'kurir' => $request->kurir ?? '',
                    'resi' => $request->resi ?? '',
                    'biaya' => $biaya,
                    'file' => $filename ?? '',
                ]);
            }

            if ($valid == 1) {
                $this->validOrder($cekData->id);
            }
        });
        session()->forget('cart');
        return redirect('/transaksi')->with(['alert' => 'success', 'message' => 'Data Berhasi Diperbarui']);

       
    }

    public function validOrder($id)
    {
        $response = DB::transaction(function () use ($id) {
            $penjualan = $this->stockOrderRepo->getId($id)->first();
            foreach ($penjualan->stock_jenis as $p) {
                $produkId = $p->produk_id;
                $gudangId = $p->gudang_id;
                $jumlah = $p->jumlah;
                $cekStock = $this->stockRepo->getWhere(['produk_id' => $produkId])->first();

                $fStockGudang = new StockService();
                $cekStockGudang = $fStockGudang->cekStockGudang($produkId, $gudangId);

                $stockAwal = $cekStock->jumlah;
                $stockBaru = $stockAwal - $jumlah;
                $this->stockRepo->update($produkId, ['jumlah' => $stockBaru]);

                $stock_gudang_awal = $cekStockGudang->jumlah;
                $total = $stock_gudang_awal - $jumlah;
                $this->gudangTransRepo->update($cekStockGudang->id, ['jumlah' => $total]);

                $updateStockJenis = $this->stockJenisRepo->getId($p->id)->update(['stock_awal' => $stock_gudang_awal]);
            }
            return true;
        });
        if ($response) {
            return true;
        }
        return false;
    }





    public function edit($id)
    {
        $transaksi = $this->transaksiRepo->getId($id);
        return response()->json($transaksi);
    }

    
    private function sendNotif($level, $title, $body, $url = null)
    {
        $cekUser = User::whereLevel($level)->where('website_id', website()->id)->get();
        foreach ($cekUser as $cekUser) {
            $token_1 = $cekUser->userToken;
            $comment = new Notifikasi();
            $comment->toId = '';
            $comment->toLevel = $cekUser->level;
            $comment->title = $title;
            $comment->body = $body;
            $comment->image = '';
            $comment->url = $url;
            $comment->status = 0;
            if (!empty($cekUser->userToken)) {
                sendNotif($token_1, $comment);
            }
            $comment->save();
        }
    }

    public function destroy($id)
    {
        $response = DB::transaction(function () use ($id) {
            $cek = $this->stockOrderRepo->getId($id);
            $nomor = $cek->nomor;
            $this->stockJenisRepo->deleteWhere(['nomor' => $nomor]);
            Shipping::where('transaksi_id',$cek->id)->delete();
            return $cek->delete();
        });
        if ($response) {
            return redirect()->back()->with(['alert' => 'success', 'message' => 'Berhasil Dihapus']);
        }
        return redirect()->back()->with(['alert' => 'error', 'message' => 'Gagal Dihapus']);
    }
}
