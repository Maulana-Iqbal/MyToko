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
        if ($request->sales) {
            $transaksi->whereIn('sales_id', $request->sales);
        }
        if ($request->customer) {
            $transaksi->whereIn('customer_id', $request->customer);
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
                    $data = $row->pelanggan->nama_depan . ' ' . $row->pelanggan->nama_belakang;
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
                    $btn = $btn . ' <li><a href="' . url('transaksi/detail/' . $row->nomor) . '" class="dropdown-item">Detail</a></li>';
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

                    // if ($row->status_order == 'proses') {
                    //     $status = 1;
                    //     if ($row->xeninvoice) {
                    //         $status = 2;
                    //         //jika sudah buat invoice
                    //     } else {
                    //         $shipping = 1;
                    //         if ($row->shipping) {
                    //             $shipping = 2;
                    //         } else {
                    //             $shipping = 1;
                    //         }
                    //         if ($row->jenis_bayar > 1) {
                    //             if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-invoice')) {
                    //                 $btn = $btn . ' <li><a class="dropdown-item create-invoice" data-shipping="' . $shipping . '" data-id="' . $row->id . '" href="javascript:void(0)">Buat Tagihan</a></li>';
                    //             }
                    //         }
                    //     }
                    //     if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-shipping')) {
                    //         $btn = $btn . ' <li><a href="javascript:void(0)" class="dropdown-item pengiriman" data-status="' . $status . '" data-id="' . $row->id . '" >Input Resi</a></li>';
                    //     }
                    //     if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-edit')) {
                    //         // $btn2 = $btn2 . ' <li><a href="/transaksi/ubah/' . $row->id . '" class="dropdown-item" >Ubah Transaksi</a></li>';
                    //     }
                    //     if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-verifikasi')) {
                    //         $btn = $btn . ' <li><a href="javascript:void(0)" class="dropdown-item" id="btnBatalkan" data-id_transaksi="' . $row->id . '" >Batalkan Transaksi</a></li>';
                    //     }
                    // }

                    // if ($row->status_trans == 3) {
                    // $btn2 = ' <li><a target="_blank" class="dropdown-item" href="/transaksi/create-invoice/' . $row->id . '">Buat Tagihan</a></li>';
                    // $btn2 = ' <li><a target="_blank" class="dropdown-item" href="/transaksi/create-invoice/' . $row->id . '">Invoice</a></li>
                    //         <li><a target="_blank" class="dropdown-item" href="/tanda-terima/' . $row->kode_trans . '">Tanda Terima</a></li>';
                    // }


                    // if ($row->status_trans == 5) {
                    //     $status = 2;
                    //     $biaya = '';
                    //     if (isset($row->shipping->biaya)) {
                    //         $biaya = $row->shipping->biaya;
                    //     }
                    //     if ($row->shipping == null) {
                    //         if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-shipping')) {
                    //             $btn = $btn . ' <li><a href="javascript:void(0)" class="dropdown-item pengiriman" data-biaya="' . $biaya . '" data-status="' . $status . '" data-id="' . $row->id . '" >Input Resi</a></li>';
                    //         }
                    //     }
                    //     // $btn2 = $btn2 . ' <li><a target="_blank" class="dropdown-item" href="/tanda-terima/' . $row->kode_trans . '">Tanda Terima</a></li>';
                    //     if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-verifikasi')) {
                    //         $btn = $btn . ' <li><a href="javascript:void(0)" class="dropdown-item" id="btnSelesai" data-id_transaksi="' . $row->id . '" >Verifikasi</a></li>';
                    //     }
                    // }


                    // if ($row->jenis_bayar == 1 or $row->jenis_bayar == 2) {
                    //     if ($row->status_trans == 6) {
                    //         // $btn2 = $btn2 . ' <li><a target="_blank" class="dropdown-item" href="/tanda-terima/' . $row->kode_trans . '">Tanda Terima</a></li>';
                    //         if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-verifikasi')) {
                    //             $btn = $btn . ' <li><a href="javascript:void(0)" class="dropdown-item" id="btnSelesai" data-id_transaksi="' . $row->id . '" >Verifikasi</a></li>';
                    //         }
                    //     }
                    // } else {
                    //     //jika jenis bayar transfer dan barang sudah dikirim
                    //     if ($row->status_trans == 6) {
                    //         $text = 'Buat Tagihan';
                    //         if ($row->xeninvoice) {
                    //             $text = 'Lihat Tagihan';
                    //             //jika sudah buat invoice
                    //         } else {
                    //             $text = 'Buat Tagihan';
                    //             $shipping = 1;
                    //             if ($row->shipping) {
                    //                 $shipping = 2;
                    //             } else {
                    //                 $shipping = 1;
                    //             }
                    //             if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-shipping')) {
                    //                 $btn = $btn . ' <li><a class="dropdown-item create-invoice" data-shipping="' . $shipping . '" data-id="' . $row->id . '" href="javascript:void(0)">' . $text . '</a></li>';
                    //             }
                    //         }
                    //     }
                    // }

                    // if ($row->status_trans == 6) {
                    //     $status = 1;
                    //     $biaya = '';
                    //     if ($row->xeninvoice) {
                    //         $status = 2;
                    //     }
                    //     if ($row->shipping <> null) {
                    //         if (!empty($shipping->biaya)) {
                    //             $biaya = $row->shipping->biaya;
                    //         }
                    //     }
                    //     if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-shipping')) {
                    //         $btn = $btn . ' <li><a href="javascript:void(0)" class="dropdown-item ubahPengiriman" data-biaya="' . $biaya . '" data-status="' . $status . '" data-id="' . $row->id . '" >Ubah Resi</a></li>';
                    //     }
                    // }

                    // if ($row->status_trans <> 3 and $row->status_trans <> 4 and $row->status_trans <> 5) {
                    //     if (auth()->user()->hasPermissionTo('transaksi') or auth()->user()->hasPermissionTo('transaksi-pembayaran')) {
                    //         if (count($row->pembayaran) < 1) {
                    //             $btn = $btn . ' <li><a href="javascript:void(0)" class="dropdown-item" id="btnBayar" data-total="' . $row->total . '" data-id_transaksi="' . $row->id . '" data-kode_trans="' . $row->kode_trans . '" >Input Pembayaran</a></li>';
                    //         } else {
                    //             $btn = $btn . ' <li><a href="/pembayaran" class="dropdown-item">Verifikasi Pembayaran</a></li>';
                    //         }
                    //     }
                    // }





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
                ->rawColumns(['nomor', 'tgl', 'customer', 'perusahaan', 'subtotal', 'pph', 'ppn', 'toko', 'pengiriman', 'biayaLain', 'total', 'metode_bayar', 'status_order', 'deskripsi', 'action'])
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
                $this->validOrder($save);
            }
        });
        session()->forget('cart');
        return redirect('/transaksi')->with(['alert' => 'success', 'message' => 'Data Berhasil Disimpan']);


        // $request->validate([
        //     'tgl' => 'required',
        //     'jenis_bayar' => 'required',
        // ], [
        //     'tgl.required' => 'Silahkan pilih Tanggal Transaksi',
        //     'jenis_bayar.required' => 'Silahkan pilih Metode Bayar'
        // ]);
        // if (session('cart')) {
        //     $response = DB::transaction(function () use ($request) {
        //         $kode_trans = createCode(2);
        //         $totalModal = 0;
        //         $subTotal = 0;
        //         $total = 0;
        //         $butuhVerifikasi = website()->trx_verifikasi;
        //         if ($butuhVerifikasi == 1) {
        //             $status_trans = 1;
        //             $status_data = 1;
        //         } else {
        //             $status_trans = 2;
        //             $status_data = 2;
        //         }
        //         $totalBiaya = 0;
        //         foreach (session('cart') as $item) {
        //             $produkId = $item['produkId'];
        //             $gudangId = $item['gudangId'];
        //             $quantity = $item['quantity'];
        //             $biaya = $item['biaya'];
        //             $grosir = $item['grosir'];
        //             $harga_modal = $item['price'];
        //             $harga_jual = $item['harga_jual'];
        //             $total = ($item['harga_jual']) * $item['quantity'];
        //             $modal = $item['price'] * $item['quantity'];
        //             $totalModal += $modal;
        //             $jumlahBiaya = $item['biaya'] * $item['quantity'];
        //             $totalBiaya += $jumlahBiaya;

        //             $subTotal += $total;

        //             $dataOrder = [
        //                 'produk_id' => $produkId,
        //                 'gudang_id' => $gudangId,
        //                 'trans_kode' => $kode_trans,
        //                 'biaya' => (double)$biaya,
        //                 'jumlah' => $quantity,
        //                 'harga_modal' => $harga_modal,
        //                 'harga_jual' => $harga_jual,
        //                 'grosir'=>$grosir,
        //                 'total' => $total,
        //                 'status_data' => $status_data,
        //             ];
        //             $order = $this->orderRepo->store($dataOrder);

        //             $cekStock = $this->stockService->getWhere(['produk_id' => $produkId])->first();
        //             $totalStock = $cekStock->jumlah;
        //             $sisa = $totalStock - $quantity;

        //             $stockGudang = $this->gudangTransRepo->getWhere(['produk_id' => $produkId, 'gudang_id' => $gudangId])->first();
        //             $sisaStockGudang = $stockGudang->jumlah - $quantity;
        //             $stockGudang->update(['jumlah' => $sisaStockGudang]);
        //             //1 buat history stock

        //             $stockAwal = $cekStock->jumlah;
        //             $stockModal = $cekStock->harga;
        //             $this->historyStockService->store([
        //                 'tgl' => date('Y-m-d', strtotime($request->tgl)),
        //                 'gudang_id' => $item['gudangId'],
        //                 'produk_id' => $item['produkId'],
        //                 'jenis' => 2,
        //                 'stock_awal' => $stockAwal,
        //                 'jumlah' => $quantity,
        //                 // 'harga' => $stockModal,
        //                 // 'harga_jual' => $harga_jual,
        //                 'deskripsi' => '',
        //                 'website_id' => website()->id,
        //             ]);
        //             //2 update stock
        //             $cekStock->update([
        //                 'jumlah' => $sisa
        //             ]);
        //         }

        //         if (session()->has('ppn')) {
        //             $ppn = ($subTotal / 100) * website()->trx_ppn;
        //         } else {
        //             $ppn = 0;
        //         }
        //         if (session()->has('pph')) {
        //             $pph = ($totalBiaya / 100) * website()->trx_pph;
        //         } else {
        //             $pph = 0;
        //         }

        //         $biayaLain = 0;
        //         if (session()->has('biayaLain')) {
        //             $biayaLain = (float)session('biayaLain');
        //         }

        //         $grandTotal = ($subTotal + $ppn + $biayaLain) - $pph;
        //         $data = [
        //             'kode_trans' => $kode_trans,
        //             'tgl_trans' => $request->tgl,
        //             'pelanggan_id' => $pelanggan_id,
        //             'totalModal' => $totalModal,
        //             'totalBiaya' => $totalBiaya,
        //             'biayaLain' => $biayaLain,
        //             'subTotal' => $subTotal,
        //             'ppn' => $ppn,
        //             'pph' => $pph,
        //             'total' => $grandTotal,
        //             'deskripsi' => $request->deskripsi,
        //             'status_trans' => $status_trans,
        //             'jenis_trans' => 2,
        //             'jenis_bayar' => $request->jenis_bayar,
        //             'sales_id'=>$request->sales,
        //             'website_id' => website()->id,
        //         ];
        //         $this->transaksiRepo->store($data);
        //         QrCode::format('svg')->size(60)->generate(url('transaksi/detail') . '/' . $kode_trans, 'image/transaksi/qrdetail' . '/' . $kode_trans . '.svg');



        //         if ($status_trans == 1) {
        //             $title = 'Transaksi Baru';
        //             $body = $kode_trans . " Rp." . $grandTotal;
        //             $notifUrl = '/transaksi/detail/' . $kode_trans;
        //             $this->sendNotif('CEO', $title, $body, $notifUrl);
        //         }

        //         session()->forget('cart');
        //         session()->forget('ppn');
        //         session()->forget('pph');
        //         session()->forget('biayaLain');
        //         $response = [
        //             'success' => true,
        //             'message' => 'Berhasil Disimpan.',
        //         ];
        //         return $response;
        //     });

        //     return $response;
        // } else {
        //     $response = [
        //         'success' => false,
        //         'message' => 'Transaksi Tidak Ditemukan.',
        //     ];
        // }

        // return response()->json($response, 200);
    }

    public function ubah($id)
    {
        session()->forget('cart');
        $transaksi = $this->stockOrderRepo->getId($id);
        foreach ($transaksi->stock_jenis as $item) {
            $cartId = $item->produk_id . $item->gudang_id;
            $cart = session()->get('cart', []);
            $harga_final = $item->harga_final - $item->biaya;
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
            $total = $cekData->total - $cekData->pengiriman;
            $pengiriman = cleanUang($request->biayaPengiriman);
            $total = $total + $pengiriman;
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

            return redirect('/transaksi')->with(['alert' => 'success', 'message' => 'Data Berhasil Diperbarui']);
        }
        if (!session('cart')) {
            return redirect()->back()->with(['alert' => 'error', 'message' => 'Data Produk Masih Kosong']);
        }

        $response = DB::transaction(function () use ($request, $cekData) {
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
            $save = $this->stockOrderRepo->update($cekData->id, $d);
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
        return redirect('/transaksi')->with(['alert' => 'success', 'message' => 'Data Berhasil Diperbarui']);

        // $request->validate([
        //     'tgl' => 'required',
        //     'jenis_bayar' => 'required',
        // ], [
        //     'tgl.required' => 'Silahkan pilih Tanggal Transaksi',
        //     'jenis_bayar.required' => 'Silahkan pilih Metode Bayar'
        // ]);
        // // dd(session('cart'));
        // if (session('cart')) {
        //     $response = DB::transaction(function () use ($request) {

        //         if (!empty($request->idPelanggan)) {
        //             $pelanggan_id = $request->idPelanggan;
        //         } else {
        //             if ($request->jenis_bayar == 1) {
        //                 $pelanggan_id = null;
        //             } else {
        //                 $response = [
        //                     'success' => false,
        //                     'message' => 'Silahkan pilih Pelanggan',
        //                 ];
        //                 return $response;
        //             }
        //         }
        //         $kode_trans = $request->kode_trans;
        //         $transaksi = $this->transaksiRepo->getWhere(['kode_trans' => $request->kode_trans])->first();
        //         $totalModal = 0;
        //         $subTotal = 0;
        //         $total = 0;
        //         $butuhVerifikasi = website()->trx_verifikasi;
        //         if ($butuhVerifikasi == 1) {
        //             $status_trans = $transaksi->status_trans;
        //             $status_data = 1;
        //         } else {
        //             $status_trans = 2;
        //             $status_data = 2;
        //         }
        //         $totalBiaya = 0;


        //         // if ($transaksi->jenis_trans == 2) {
        //         $orderData = $this->orderRepo->getWhere(['trans_kode' => $transaksi->kode_trans]);
        //         foreach ($orderData->get() as $order) {
        //             $stock = $this->stockService->getWhere(['produk_id' => $order->produk_id])->first();
        //             $stockAwal = $stock->jumlah;
        //             $stockBaru = $stockAwal + $order->jumlah;
        //             $this->stockService->update($order->produk_id, ['jumlah' => $stockBaru]);

        //             $cekStockGudang = $this->gudangTransRepo->getWhere(['produk_id' => $order->produk_id, 'gudang_id' => $order->gudang_id,])->first();
        //             $stockGudangAwal = $cekStockGudang->jumlah;
        //             $stockGudangBaru = $stockGudangAwal + $order->jumlah;
        //             $cekStockGudang->update(['jumlah' => $stockGudangBaru]);

        //             $this->historyStockRepo->store([
        //                 'tgl' => date('Y-m-d', strtotime($request->tgl)),
        //                 'produk_id' => $order->produk_id,
        //                 'gudang_id' => $order->gudang_id,
        //                 'jenis' => 1,
        //                 'stock_awal' => $stockGudangAwal,
        //                 'jumlah' => $order->jumlah,
        //                 // 'harga' => $stock->harga,
        //                 // 'harga_jual' => $stock->harga_jual,
        //                 // 'harga_jual' => $stock->harga_jual,
        //                 'deskripsi' => 'Ubah Transaksi',
        //                 'website_id' => $transaksi->website_id,
        //             ]);
        //         }

        //         $orderData->delete();
        //         // }

        //         foreach (session('cart') as $item) {
        //             $produkId = $item['produkId'];
        //             $gudangId = $item['gudangId'];
        //             $stockId = $item['stockId'];
        //             $quantity = $item['quantity'];
        //             $biaya = $item['biaya'];
        //             $grosir = $item['grosir'];
        //             $harga_jual = $item['harga_jual'];
        //             $total = ($item['harga_jual']) * $item['quantity'];
        //             $modal = $item['price'] * $item['quantity'];
        //             $totalModal += $modal;
        //             $jumlahBiaya = $item['biaya'] * $item['quantity'];
        //             $totalBiaya += $jumlahBiaya;

        //             $subTotal += $total;

        //             $dataOrder = [
        //                 'produk_id' => $produkId,
        //                 'gudang_id' => $gudangId,
        //                 'trans_kode' => $kode_trans,
        //                 'biaya' => (float)$biaya,
        //                 'jumlah' => $quantity,
        //                 'harga_jual' => $harga_jual,
        //                 'total' => $total,
        //                 'grosir' => $grosir,
        //                 'status_data' => $status_data,
        //                 'website_id' => $transaksi->website_id
        //             ];
        //             $order = $this->orderRepo->store($dataOrder);

        //             $cekStock = $this->stockService->getWhere(['produk_id' => $produkId])->first();
        //             $totalStock = $cekStock->jumlah;
        //             $sisa = $totalStock - $quantity;


        //             $cekStockGudang = $this->gudangTransRepo->getWhere(['produk_id' => $produkId, 'gudang_id' => $gudangId,])->first();
        //             $stockGudangAwal = $cekStockGudang->jumlah;
        //             $stockGudangBaru = $stockGudangAwal - $quantity;
        //             $cekStockGudang->update(['jumlah' => $stockGudangBaru]);
        //             //1 buat history stock

        //             $stockAwal = $cekStock->jumlah;
        //             $stockModal = $cekStock->harga;
        //             $this->historyStockService->store([
        //                 'tgl' => date('Y-m-d', strtotime($request->tgl)),
        //                 'produk_id' => $produkId,
        //                 'gudang_id' => $gudangId,
        //                 'jenis' => 2,
        //                 'stock_awal' => $stockGudangAwal,
        //                 'jumlah' => $quantity,
        //                 // 'harga' => $stockModal,
        //                 // 'harga_jual' => $harga_jual,
        //                 'deskripsi' => 'Ubah Transaksi',
        //                 'website_id' => $transaksi->website_id,
        //             ]);
        //             //2 update stock
        //             $cekStock->update([
        //                 'jumlah' => $sisa
        //             ]);
        //         }

        //         if (session()->has('ppn')) {
        //             $ppn = ($subTotal / 100) * website()->trx_ppn;
        //         } else {
        //             $ppn = 0;
        //         }
        //         if (session()->has('pph')) {
        //             $pph = ($totalBiaya / 100) * website()->trx_pph;
        //         } else {
        //             $pph = 0;
        //         }


        //         $biayaPengiriman = 0;
        //         if (!empty($request->resi) or !empty($request->kurir) or !empty($request->biayaPengiriman)) {
        //             if ($request->file) {
        //                 $request->validate([
        //                     'file' => 'required|mimes:png,jpg,jpeg|max:1024',
        //                 ], [
        //                     'mimes' => 'Ekstensi Gambar Diizinkan .png / .jpg / .jpeg',
        //                     'required' => 'Silahkan Upload Gambar',
        //                     'max' => 'Maksimal Size 1024KB/1MB'
        //                 ]);
        //                 $filename = time() . '.' . $request->file->extension();
        //                 $request->file->move(public_path('image/transaksi/resi'), $filename);
        //             } else {
        //                 $cekPengiriman = Shipping::where('transaksi_id', $transaksi->id)->first();
        //                 if (!empty($cekPengiriman->file)) {
        //                     $filename = $cekPengiriman->file;
        //                 } else {
        //                     $filename = '';
        //                 }
        //             }
        //             $biaya = 0;
        //             if (!empty($request->biayaPengiriman)) {
        //                 $biaya = str_replace('.', '', $request->biayaPengiriman);
        //             }
        //             $pengiriman = Shipping::updateOrCreate([
        //                 'transaksi_id' => $transaksi->id
        //             ], [
        //                 'kurir' => $request->kurir,
        //                 'resi' => $request->resi,
        //                 'biaya' => $biaya,
        //                 'file' => $filename,
        //             ]);
        //         }


        //         $biayaLain = 0;
        //         if (session()->has('biayaLain')) {
        //             $biayaLain = str_replace('.', '', (float)session('biayaLain'));
        //         }

        //         $grandTotal = ($subTotal + $ppn + $biayaLain + $biayaPengiriman) - $pph;
        //         $data = [
        //             'kode_trans' => $kode_trans,
        //             'tgl_trans' => $request->tgl,
        //             'pelanggan_id' => $pelanggan_id,
        //             'totalModal' => $totalModal,
        //             'totalBiaya' => $totalBiaya,
        //             'biayaLain' => $biayaLain,
        //             'subTotal' => $subTotal,
        //             'ppn' => $ppn,
        //             'pph' => $pph,
        //             'total' => $grandTotal,
        //             'deskripsi' => $request->deskripsi,
        //             'status_trans' => $status_trans,
        //             'jenis_trans' => 2,
        //             'jenis_bayar' => $request->jenis_bayar,
        //             'sales_id' => $request->sales,
        //             'website_id' => $transaksi->website_id,
        //         ];
        //         $this->transaksiRepo->update($transaksi->id, $data);
        //         // QrCode::format('svg')->size(60)->generate(url('transaksi/detail') . '/' . $kode_trans, 'image/transaksi/qrdetail' . '/' . $kode_trans . '.svg');



        //         if ($status_trans == 1) {
        //             $title = 'Ubah Transaksi';
        //             $body = $kode_trans . " Rp." . $grandTotal;
        //             $notifUrl = '/transaksi/detail/' . $kode_trans;
        //             $this->sendNotif('CEO', $title, $body, $notifUrl);
        //         }

        //         session()->forget('cart');
        //         session()->forget('ppn');
        //         session()->forget('pph');
        //         session()->forget('ongkir');
        //         session()->forget('biayaLain');
        //         $response = [
        //             'success' => true,
        //             'message' => 'Berhasil Diperbaharui.',
        //         ];
        //         return $response;
        //     });

        //     // return $response;
        // } else {
        //     $response = [
        //         'success' => false,
        //         'message' => 'Transaksi Tidak Ditemukan.',
        //     ];
        // }

        // return response()->json($response, 200);
    }

    public function validOrder($id)
    {
        $response = DB::transaction(function () use ($id) {
            $penjualan = $this->stockOrderRepo->getWhere(['id'=>$id])->first();
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



    public function invoice(Request $request)
    {
        $transaksi = $this->transaksiRepo->getId($request->id);
        if ($transaksi->status_trans > 1) {
            $pelanggan = $this->pelangganRepo->getId($transaksi->pelanggan_id);
            $provinsi = ProvinceOngkir::find($pelanggan->provinsi_id);
            $kabupaten = RegencyOngkir::find($pelanggan->kota_id);
            $kecamatan = DistrictOngkir::find($pelanggan->kecamatan_id);
            $order = $this->orderRepo->getWhere(['trans_kode' => $transaksi->kode_trans])->get();
            return view('transaksi.invoice', compact('transaksi', 'order', 'provinsi', 'kabupaten', 'kecamatan', 'pelanggan'));
        } else {
            return 'Transaksi belum diproses';
        }
    }

    public function vCekTransaksi()
    {
        return view('website.cekTransaksi');
    }

    public function detailTransaksi(Request $request)
    {
        $transaksi = $this->transaksiRepo->getWhere(['kode_trans' => $request->id])->with('xeninvoice')->first();
        if (!$transaksi) {
            $pesan = "Transaksi Tidak Ditemukan, Silahkan hubungi customer service";
            return view('website.prosesPage', compact('pesan'));
        }
        $this->cekPembayaran($transaksi->kode_trans);
        $xen = array();
        if ($transaksi->xeninvoice) {
            $xen = new XenditController();
            $xen = $xen->getInvoice($transaksi->xeninvoice->xen_id);
        }
        // $transaksi = $this->transaksiRepo->getWhere(['kode_trans' => $request->id])->with('xeninvoice')->first();
        $pelanggan = '';
        $provinsi = '';
        $kabupaten = '';
        $kecamatan = '';

        if (!empty($transaksi->pelanggan_id)) {
            $pelanggan = $this->pelangganRepo->getId($transaksi->pelanggan_id);
            if ($pelanggan) {
                $provinsi = ProvinceOngkir::find($pelanggan->provinsi_id);
                $kabupaten = RegencyOngkir::find($pelanggan->kota_id);
                $kecamatan = DistrictOngkir::find($pelanggan->kecamatan_id);
            }
        }
        $order = $this->orderRepo->getWhere(['trans_kode' => $transaksi->kode_trans])->get();
        $shipping = Shipping::where('transaksi_id', $transaksi->id)->first();
        if (isset($request->print)) {
            $print = true;
            return view('transaksi.invoicePrint', compact('transaksi', 'order', 'provinsi', 'kabupaten', 'kecamatan', 'pelanggan', 'xen', 'shipping', 'print'));
        }

        if (isset($request->pdf)) {
            $pdf = $request->pdf;
            $pdf = PDF::loadview('transaksi.invoicePrint', compact('transaksi', 'order', 'provinsi', 'kabupaten', 'kecamatan', 'pelanggan', 'xen', 'shipping', 'pdf'))->setPaper('a4', 'landscape');
            return $pdf->download('Detail-Transaksi-' . $transaksi->kode_trans . '.pdf');
        }
        return view('transaksi.getInvoice', compact('transaksi', 'order', 'provinsi', 'kabupaten', 'kecamatan', 'pelanggan', 'xen', 'shipping'));
    }



    public function tandaTerima(Request $request)
    {
        $transaksi = $this->transaksiRepo->getWhere(['kode_trans' => $request->id])->first();
        // $pelanggan = $this->pelangganRepo->getId($transaksi->pelanggan_id);
        $order = $this->orderRepo->getWhere(['trans_kode' => $transaksi->kode_trans])->get();
        if (isset($request->print)) {
            $print = true;
            return view('transaksi.tandaTerimaPrint', compact('transaksi', 'order', 'print'));
        }

        if (isset($request->pdf)) {
            $pdf = $request->pdf;
            $pdf = PDF::loadview('transaksi.tandaTerimaPrint', compact('transaksi', 'order', 'pdf'))->setPaper('a4', 'portrait');
            return $pdf->download('Tanda-Terima-Barang-' . $transaksi->kode_trans);
        }
        return view('transaksi.tandaTerima', compact('transaksi', 'order'));
    }




    public function edit($id)
    {
        $transaksi = $this->transaksiRepo->getId($id);
        return response()->json($transaksi);
    }

    // private function updateKas($jenis, $total, $sumber, $deskripsi = null)
    // {
    //     if (!$jenis or !$total) {
    //         return false;
    //     }
    //     if ($jenis == 1) {
    //         $saldo = sisaKasKecil();
    //     } elseif ($jenis == 2) {
    //         $saldo = sisaKasBesar();
    //     }
    //     if (!empty($saldo)) {
    //         $saldo = $saldo + $total;
    //     } else {
    //         $saldo = 0;
    //         $saldo = $saldo + $total;
    //     }
    //     Kas::Create([

    //         'tgl' => date('Y-m-d'),
    //         'rekening_id' => '',
    //         'jenis' => '2',
    //         'debit' => 0,
    //         'kredit' => $total,
    //         'nominal' => $saldo,
    //         'sumber' => $sumber,
    //         'deskripsi' => $deskripsi,
    //         'website_id' => website()->id,
    //     ]);
    //     return true;
    // }



    private function cekPembayaran($kode_trans)
    {
        $transaksi = $this->transaksiRepo->getWhere(['kode_trans' => $kode_trans])->first();
        $cekInvoice = Xeninvoice::where('xen_external_id', $kode_trans)->first();
        if (!$cekInvoice) {
            return true;
        }
        $getInvoice = new XenditController();
        $getInvoice = $getInvoice->getInvoice($cekInvoice->xen_id);
        if ($getInvoice['status'] == 'PAID') {
            if ($transaksi->jenis_trans == 1 and $transaksi->status_trans == 1) {
                $this->prosesPembayaran($transaksi->kode_trans);
            }
        } elseif ($getInvoice['status'] == 'EXPIRED') {
            if ($transaksi->jenis_trans == 1 and $transaksi->status_trans == 1) {
                $this->trxBatal($transaksi->id);
            }
        } elseif ($getInvoice['status'] == 'PENDING') {
            if ($transaksi->jenis_trans == 1 and $transaksi->status_trans == 1) {
            }
        }
        return true;
    }

    private function trxSelesai($id, $request)
    {
        $result = DB::transaction(function () use ($id, $request) {
            $deskripsi = '';

            $transaksi = $this->transaksiRepo->getId($id);

            if ($request->deskripsi) {
                $deskripsi = $transaksi->$deskripsi . '<br>' . $request->deskripsi;
            }

            //keadaan ini dianggap sudah dibayar kepada admin sehinggan pembayaran melalui xendit dibatalkan
            $xen = Xeninvoice::where('transaksi_id', $id);
            if ($xen->count() > 0) {

                $xen = $xen->first();
                if ($xen->xen_status === 'PENDING') {
                    $inv = new XenditController();
                    $inv->closeInvoice($xen->xen_id);

                    $xen->xen_status = 'EXPIRED';
                    $xen->save();
                }
            }

            $total = $transaksi->total;
            $cekPembayaran = Pembayaran::where('kode_trans', $transaksi->kode_trans)->where('verifikasi', 2);
            if ($cekPembayaran->count() < 1) {
                // $this->updateKas(2, $total, 'Transaksi', '');
            }
            $title = 'Transaksi Selesai';
            $body = $transaksi->kode_trans . " Rp." . $total;
            $notifUrl = '/transaksi/detail/' . $transaksi->kode_trans;
            $this->sendNotif('CEO', $title, $body, $notifUrl);

            $transaksi->update(['status_trans' => '3', 'deskripsi' => $deskripsi]);

            return true;
        });
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    private function trxBatal($id)
    {
        $result = DB::transaction(function () use ($id) {
            $transaksi = $this->transaksiRepo->getId($id);
            $this->transaksiRepo->update($id, [
                'status_trans' => '4',
                'deskripsi' => 'Dibatalkan'
            ]);

            if ($transaksi->jenis_trans == 2) {
                $order = $this->orderRepo->getWhere(['trans_kode' => $transaksi->kode_trans])->get();
                foreach ($order as $order) {
                    $stock = $this->stockService->getWhere(['produk_id' => $order->produk_id])->first();
                    $stockAwal = $stock->jumlah;
                    $stockBaru = $stockAwal + $order->jumlah;

                    $stockGudang = $this->gudangTransRepo->getWhere(['produk_id' => $order->produk_id, 'gudang_id' => $order->gudang_id])->first();
                    $sisaStockGudang = $stockGudang->jumlah + $order->jumlah;
                    $stockGudang->update(['jumlah' => $sisaStockGudang]);

                    $this->historyStockRepo->store([
                        'tgl' => date('Y-m-d'),
                        'produk_id' => $order->produk_id,
                        'gudang_id' => $order->gudang_id,
                        'jenis' => 1,
                        'stock_awal' => $stockAwal,
                        'jumlah' => $order->jumlah,
                        'harga' => $stock->harga,
                        'harga_jual' => $stock->harga_jual,
                        'deskripsi' => 'Transaksi Batal',
                        'website_id' => $transaksi->website_id,
                    ]);
                    $stock->update(['jumlah' => $stockBaru]);
                }
            }

            $xen = Xeninvoice::where('transaksi_id', $id);
            if ($xen->count() > 0) {

                $xen = $xen->first();
                if ($xen->xen_status == 'PENDING') {
                    $inv = new XenditController();
                    $inv->closeInvoice($xen->xen_id);

                    $xen->xen_status = 'EXPIRED';
                    $xen->save();
                }
            }
            $title = 'Transaksi Ditolak / Dibatalkan';
            $body = $transaksi->kode_trans;
            $notifUrl = '/transaksi/detail/' . $transaksi->kode_trans;
            $this->sendNotif('CEO', $title, $body, $notifUrl);
            $this->sendNotif('STAF', $title, $body, $notifUrl);

            return true;
        });
        return $result;
    }

    private function trxProses($id, $request)
    {
        $result = DB::transaction(function () use ($id, $request) {
            $deskripsi = '';
            if (isset($request->deskripsi)) {
                $deskripsi = $request->deskripsi;
            } elseif (isset($request->deskripsiCeo)) {
                $deskripsi = $request->deskripsiCeo;
            }
            $transaksi = $this->transaksiRepo->getId($id);
            $transaksi->update([
                'status_trans' => '2',
                'deskripsi' => $transaksi->deskripsi . '<br>' . auth()->user()->name . '<br>' . $deskripsi . '<br>',
            ]);

            $title = 'Transaksi telah Diproses';
            $body = $transaksi->kode_trans;
            $notifUrl = '/transaksi/detail/' . $transaksi->kode_trans;
            $this->sendNotif('STAFF', $title, $body, $notifUrl);
            return true;
        });
        return $result;
    }

    public function verifikasiTrans(Request $request)
    {
        if ($request->id == 1) {
            //Selesai
            $id = $request->verifikasiId;
            $result = $this->trxSelesai($id, $request);
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Transaksi Selesai.',
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Verifikasi Transaksi Gagal',
                ];
            }
            return response()->json($response, 200);
        } elseif ($request->id == 2) {
            //Dibatalkan
            $id = $request->verifikasiId;
            $result = $this->trxBatal($id);
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Telah Dibatalkan.',
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Transaksi gagal dibatalkan.',
                ];
            }
            return response()->json($response, 200);
        } elseif ($request->id == 3) {
            //Proses CEO
            $id = $request->verifikasiIdCeo;
            $result = $this->trxProses($id, $request);

            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Transaksi Proses.',
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Transaksi Proses Gagal.',
                ];
            }
            return response()->json($response, 200);
        }
    }



    public function apiDataTransaksi($id)
    {
        $transaksi = $this->transaksiRepo->getWhere(null)->latest();
        $transaksi->with('pelanggan');
        $transaksi->where('status_trans', $id);
        // $transaksi->select('transaksi.*','pelanggan.nama_lengkap');
        $transaksi = $transaksi->get();
        return response()
            ->json(['message' => 'Berhasil mengambil data transaksi', 'token_type' => 'Bearer', 'data' => $transaksi]);
    }

    public function apiVerifikasiTransaksi(Request $request)
    {
        if ($request->status_trans == 3) {
            $id = $request->id;
            $this->trxSelesai($id, $request);
            $response = [
                'success' => true,
                'message' => 'Transaksi Selesai.',
            ];
            return response()->json($response, 200);
        } elseif ($request->status_trans == 4) {
            //Tolak
            $id = $request->id;
            $result = $this->trxBatal($id);

            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Telah Dibatalkan.',
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Transaksi gagal dibatalkan.',
                ];
            }
            return response()->json($response, 200);
        } elseif ($request->status_trans == 2) {
            //Proses CEO
            $id = $request->id;
            $result = $this->trxProses($id, $request);

            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Transaksi Proses.',
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Transaksi Proses Gagal.',
                ];
            }
            return response()->json($response, 200);
        }
    }

    public function prosesPembayaran($kode_trans)
    {
        $result = DB::transaction(function () use ($kode_trans) {
            //Dibayar
            $transaksi = $this->transaksiRepo->getWhere(['kode_trans' => $kode_trans])->first();
            if (!empty($transaksi->pelanggan_id)) {
                $pelanggan = $this->pelangganRepo->getId($transaksi->pelanggan_id);
                $pelanggan->status_data = 2;
                $pelanggan->save();
            }

            $transaksi->update([
                'status_trans' => '5',
            ]);

            $order = $this->orderRepo->getWhere(['trans_kode' => $transaksi->kode_trans]);
            if ($transaksi->jenis_jual == 1) {
                foreach ($order->get() as $u) {

                    $stock = $this->stockService->getId($u->produk_id);
                    $totalStock = $stock->jumlah;
                    $sisa = $totalStock - $u->jumlah;

                    //1 buat history stock
                    $cekStock = $this->stockService->getId($stock->id);
                    $stockAwal = $cekStock->jumlah;
                    $stockModal = $cekStock->harga;
                    $this->historyStockRepo->store([
                        'tgl' => date('Y-m-d'),
                        'sales_id' => '',
                        'pemasok_id' => '',
                        'faktur' => '',
                        'produk_id' => $stock->id,
                        'jenis' => 2,
                        'stock_awal' => $stockAwal,
                        'jumlah' => $u->jumlah,
                        'harga' => $stockModal,
                        'harga_jual' => $u->harga_jual,
                        'deskripsi' => '',
                        'website_id' => website()->id,
                    ]);
                    //2 update stock
                    $stock->update([
                        'jumlah' => $sisa
                    ]);
                }
            }
            $order->update(['status_data' => 2]);

            $total = $transaksi->total;
            // $this->updateKas(2, $total, 'Transaksi Online', 'Transaksi Dibayar');

            $title = 'Transaksi Dibayar';
            $body = $transaksi->kode_trans . " Rp." . $total;
            $notifUrl = '/transaksi/detail/' . $transaksi->kode_trans;
            $this->sendNotif('STAFF', $title, $body, $notifUrl);
            return $kode_trans;
        });
        if ($result) {
            return $result;
        }
    }


    public function confirs($kode_trans)
    {
        $transaksi = $this->transaksiRepo->getWhere(['kode_trans' => $kode_trans])->first();
        if ($transaksi->status_trans >= 3) {
            $status = $transaksi->status_trans;
            if ($status == 3) {
                $pesan = "Transaksi telah Selesai";
            } elseif ($status == 4) {
                $pesan = "Transaksi Dibatalkan";
            } elseif ($status == 5) {
                $pesan = "Pesananmu sedang Diproses";
            } elseif ($status == 6) {
                $pesan = "Pesananmu dalam Proses Pengiriman";
            }
            $result = $kode_trans;
            return view('website.prosesPage', compact('result', 'pesan'));
        }
        $cekInvoice = Xeninvoice::where('xen_external_id', $kode_trans)->first();
        if (!$cekInvoice) {
            $pesan = "Transaksi tidak ditemukan";
            return view('website.prosesPage', compact('pesan'));
        }
        $getInvoice = new XenditController();
        $getInvoice = $getInvoice->getInvoice($cekInvoice->xen_id);
        if ($getInvoice['status'] == 'PAID') {
        } elseif ($getInvoice['status'] == 'EXPIRED') {
            $pesan = "Transaksi sudah kedaluwarsa";
            return view('website.prosesPage', compact('pesan'));
        } elseif ($getInvoice['status'] == 'PENDING') {
            $pesan = "Silahkan lakukan pembayaran pada link dibawah ini : <br><h3 class='text-center'>" . $getInvoice['invoice_url'] . "</h3>";
            return view('website.prosesPage', compact('pesan'));
        } else {
            $pesan = "Status transaksi tidak ditemukan, Silahkan hubungi customer service";
            return view('website.prosesPage', compact('pesan'));
        }


        $result = $this->prosesPembayaran($kode_trans);
        if ($result) {
            $pesan = "Pesananmu sedang Diproses";
            return view('website.prosesPage', compact('result', 'pesan'));
        } else {
            $pesan = "Transaksi tidak dapat dikonfirmasi, Silahkan hubungi customer service";
            return view('website.prosesPage', compact('pesan'));
        }
    }

    public function failed($kode_trans)
    {
        $transaksi = $this->transaksiRepo->getWhere(['kode_trans' => $kode_trans])->first();
        if ($transaksi->status_trans == 4) {
            $pesan = "Transaksi Dibatalkan";
            return view('website.prosesPage', compact('pesan'));
        }

        $this->trxBatal($transaksi->id);
        $title = 'Transaksi Dibatalkan';
        $body = $transaksi->kode_trans;
        $notifUrl = '/transaksi/detail/' . $transaksi->kode_trans;
        $this->sendNotif('STAFF', $title, $body, $notifUrl);
        $pesan = "Transaksi Dibatalkan";
        return view('website.prosesPage', compact('pesan'));
    }

    public function getPengiriman($transaksi_id)
    {
        return Shipping::where('transaksi_id', $transaksi_id)->first();
    }

    public function savePengiriman(Request $request)
    {
        $request->validate([
            'transaksiId' => 'required',
            'resi' => 'required',
            'kurir' => 'required',
        ]);

        $result = DB::transaction(function () use ($request) {
            $transaksi = $this->transaksiRepo->getId($request->transaksiId);
            if ($transaksi->status_trans == 6) {
                return true;
            }
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
                $cekPengiriman = Shipping::where('transaksi_id', $request->transaksiId)->first();
                if (!empty($cekPengiriman->file)) {
                    $filename = $cekPengiriman->file;
                } else {
                    $filename = '';
                }
            }

            $biaya = 0;
            if (!empty($request->biayaPengiriman)) {
                $biaya = str_replace('.', '', $request->biayaPengiriman);
            }
            $shipping = Shipping::create([
                'transaksi_id' => $request->transaksiId,
                'resi' => $request->resi,
                'kurir' => $request->kurir,
                'biaya' => $biaya,
                'file' => $filename,
            ]);

            if ($shipping) {
                $total = $transaksi->total + $biaya;
                $transaksi->total = $total;
                $transaksi->status_trans = 6;
                $transaksi->save();
                return true;
            } else {
                return false;
            }
        });
        if ($result == true) {
            $response = [
                'success' => true,
                'message' => 'Input Resi Pengiriman Berhasil',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Input Resi Pengiriman Gagal',
            ];
            return response()->json($response, 200);
        }
    }

    public function updatePengiriman(Request $request)
    {
        $request->validate([
            'transaksiId' => 'required',
            'resi' => 'required',
            'kurir' => 'required',
        ]);

        $result = DB::transaction(function () use ($request) {
            $transaksi = $this->transaksiRepo->getId($request->transaksiId);
            $biaya = 0;
            if (!empty($request->biayaPengiriman)) {
                $biaya = str_replace('.', '', $request->biayaPengiriman);
            }
            $shipping = Shipping::where('transaksi_id', $request->transaksiId)->first();

            $total = $transaksi->total - $shipping->biaya;
            $total = $total + $biaya;
            $transaksi->total = $total;
            $transaksi->save();
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
                $cekPengiriman = Shipping::where('transaksi_id', $request->transaksi_id)->first();
                if (!empty($cekPengiriman->file)) {
                    $filename = $cekPengiriman->file;
                } else {
                    $filename = '';
                }
            }
            $shipping->update([
                'resi' => $request->resi,
                'kurir' => $request->kurir,
                'biaya' => $biaya,
                'file' => $filename,
            ]);
            return true;
        });
        if ($result == true) {
            $response = [
                'success' => true,
                'message' => 'Ubah Resi Pengiriman Berhasil',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Ubah Resi Pengiriman Gagal',
            ];
            return response()->json($response, 200);
        }
    }


    


    // public function checkout(Request $request)
    // {

    //     $request->validate([
    //         "nama_depan" => "required",
    //         "nama_belakang" => "required",
    //         "email" => "required|email",
    //         "telpon" => "required",
    //         "provinsi" => "required",
    //         "kabupaten" => "required",
    //         "kecamatan" => "required",
    //         "alamat" => "required",
    //         "kurir" => "required",
    //         "paket" => "required",
    //     ], [
    //         'required' => 'Data belum lengkap',
    //         'email' => 'Email tidak valid',
    //     ]);

    //     if (\Cart::isEmpty()) {
    //         $response = [
    //             'success' => false,
    //             'message' => 'Keranjang Belanja Masih Kosong',
    //         ];
    //         return response()->json($response, 200);
    //     }



    //     $result = DB::transaction(function () use ($request) {
    //         $condition = \Cart::getCondition('biaya');
    //         $biayaKirim = $condition->getValue();

    //         $condition = \Cart::getCondition('ppn');
    //         $subTotal = \Cart::getSubTotal();
    //         $ppn = $condition->parsedRawValue;
    //         $pph = 0;
    //         $total = $subTotal;
    //         $gtotal = $biayaKirim + $subTotal;
    //         $subTotal = $subTotal - $ppn;



    //         $invoice = createCode(1);
    //         $trans = [
    //             'invoice' => $invoice,
    //             'amount' => $gtotal,
    //             'deskription' => 'Tagihan Pembelian Produk di ' . config('app.name'),
    //             'duration' => website()->trx_duration_online,
    //         ];

    //         $provinsi = ProvinceOngkir::find($request->provinsi);
    //         $provinsi = $provinsi->name;
    //         $kota = RegencyOngkir::find($request->kabupaten);
    //         $kota = $kota->name;
    //         $kecamatan = DistrictOngkir::find($request->kecamatan);
    //         $kecamatan = $kecamatan->name;
    //         $hp = gantiformat($request->telpon);
    //         $customer = [
    //             'given_names' => $request->nama_depan,
    //             'surname' => $request->nama_belakang,
    //             'email' => $request->email,
    //             'mobile_number' => $hp,
    //             'address' => [
    //                 [
    //                     'city' => $kota,
    //                     'country' => 'Indonesia',
    //                     'postal_code' => $request->kode_pos,
    //                     'state' => $provinsi,
    //                     'street_line1' => $request->alamat,
    //                     'street_line2' => $kecamatan,
    //                 ]
    //             ]
    //         ];

    //         $ongkir = $request->paket;
    //         $successUrl = config('app.url') . '/transaksi/confirs/' . $invoice;
    //         $failurUrl = config('app.url') . '/transaksi/failed/' . $invoice;


    //         $cartItems = \Cart::getContent();
    //         // $totalBerat=0;
    //         // $totalBelanja=0;
    //         $data = array();
    //         foreach ($cartItems as $index => $i) {
    //             $data[] = array(
    //                 'name' => $i->name,
    //                 'quantity' => $i->quantity,
    //                 'price' => $i->price,
    //                 'image' => url('image/produk/small/' . $i->attributes[0]['image'])
    //             );

    //             //     $berat=$item->berat*$item->quantity;
    //             //     $totalBerat+=$berat;
    //             //     $belanja=$item->price*$item->quantity;
    //             //     $totalBelanja+=$belanja;
    //         }

    //         $item = $data;
    //         //   dd($item);
    //         $dataPelanggan = [
    //             'nama_depan' => $request->nama_depan,
    //             'nama_belakang' => $request->nama_belakang,
    //             'perusahaan' => '',
    //             'email' => $request->email,
    //             'telpon' => $request->telpon,
    //             'hp' => $request->telpon,
    //             'provinsi_id' => $request->provinsi,
    //             'kota_id' => $request->kabupaten,
    //             'kecamatan_id' => $request->kecamatan,
    //             'desa_id' => '0',
    //             'pos' => $request->kode_pos,
    //             'alamat' => $request->alamat,
    //             'logo' => '',
    //             'status_data' => '1',
    //             'website_id' => website()->id,
    //         ];
    //         $pelanggan = $this->pelangganRepo->store($dataPelanggan);
    //         $pelanggan_id = $pelanggan;

    //         $totalModal = 0;
    //         foreach ($cartItems as $index => $i) {
    //             $itemTotal = 0;
    //             $itemTotal = $i->quantity * $i->attributes[0]['harga_modal'];
    //             $totalModal += $itemTotal;
    //             $this->orderRepo->store([
    //                 'produk_id' => $i->id,
    //                 'trans_kode' => $invoice,
    //                 'biaya' => 0,
    //                 'jumlah' => $i->quantity,
    //                 'harga_modal' => $i->attributes[0]['harga_modal'],
    //                 'harga_jual' => $i->price,
    //                 'total' => $itemTotal,
    //                 'status_data' => 1,
    //                 'website_id' => website()->id,
    //             ]);
    //         }


    //         $dataTransaksi = [
    //             'kode_trans' => $invoice,
    //             'tgl_trans' => date('Y-m-d'),
    //             'pelanggan_id' => $pelanggan_id,
    //             'totalModal' => $totalModal,
    //             'totalBiaya' => 0,
    //             'subTotal' => $subTotal,
    //             'ppn' => $ppn,
    //             'pph' => 0,
    //             'total' => $gtotal,
    //             'deskripsi' => $request->catatan,
    //             'status_trans' => 1,
    //             'jenis_trans' => 1,
    //             'jenis_bayar' => 3,
    //             'website_id' => website()->id,
    //         ];
    //         $transaksi = $this->transaksiRepo->store($dataTransaksi);
    //         $path = 'image/transaksi/qrdetail';
    //         if (!file_exists($path)) {
    //             mkdir($path, 775, true);
    //         }
    //         QrCode::format('svg')->size(60)->generate(url('transaksi/detail') . '/' . $invoice, 'image/transaksi/qrdetail' . '/' . $invoice . '.svg');

    //         $shipping = Shipping::create([
    //             'transaksi_id' => $transaksi->id,
    //             'resi' => '',
    //             'kurir' => $request->kurir,
    //             'biaya' => $request->paket,
    //             'ditanggung' => 1,
    //         ]);


    //         $xendit = new XenditController();
    //         $proses = $xendit->createInvoice($trans, $customer, $item, $ongkir, $ppn, $pph, $successUrl, $failurUrl);
    //         if ($proses['status'] == 'PENDING') {
    //             $xenvoice = Xeninvoice::create([
    //                 'transaksi_id' => $transaksi->id,
    //                 'xen_id' => $proses['id'],
    //                 'xen_user_id' => $proses['user_id'],
    //                 'xen_external_id' => $proses['external_id'],
    //                 'xen_status' => $proses['status'],
    //                 'xen_invoice_url' => $proses['invoice_url'],
    //                 'xen_expiry_date' => $proses['expiry_date'],
    //             ]);

    //             \Cart::clear();
    //             $path = 'image/transaksi/qrbayar';
    //             if (!file_exists($path)) {
    //                 mkdir($path, 775, true);
    //             }
    //             QrCode::format('svg')->size(60)->generate($proses['invoice_url'], 'image/transaksi/qrbayar' . '/' . $invoice . '.svg');
    //             return $proses['invoice_url'];
    //         } else {
    //             return false;
    //         }
    //     });
    //     if ($result == false) {
    //         $response = [
    //             'success' => false,
    //             'message' => 'Transaksi Gagal',
    //         ];
    //         return response()->json($response, 200);
    //     } else {
    //         $response = [
    //             'success' => true,
    //             'message' => 'Berhasil',
    //             'data' => ['url' => $result]
    //         ];
    //         return response()->json($response, 200);
    //     }
    // }

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
            Shipping::where('transaksi_id', $cek->id)->delete();
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

        $status=null;
        if($request->status){
            $status=['status_order'=>$request->status];
        }
        if ($search == '') {
            $data = $this->stockOrderRepo->getWhere($q)->where('jenis', 'penjualan')->where($status)->orderBy('nomor', 'asc')->select('nomor')->groupBy('nomor')->get();
        } else {
            $data = $this->stockOrderRepo->getWhere($q)->where('jenis', 'penjualan')->where($status)->where('nomor', $search)->orderby('nomor', 'asc')->select('nomor')->groupBy('nomor')->first();
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
