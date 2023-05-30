<?php

namespace App\Services;

use App\Repositori\GudangTransRepositori;
use App\Repositori\HistoryStockRepositori;
use App\Repositori\ProdukRepositori;
use App\Repositori\StockRepositori;
use DataTables;
use Illuminate\Support\Facades\DB;

class HistoryStockService
{
    protected $historyStockRepo;
    protected $stockRepo;
    protected $gudangTransRepo;
    protected $produkRepo;
    public function __construct()
    {
        $this->historyStockRepo = new HistoryStockRepositori();
        $this->stockRepo = new StockRepositori();
        $this->gudangTransRepo = new GudangTransRepositori();
        $this->produkRepo = new ProdukRepositori();
    }

    public function getId($id)
    {
        return $this->historyStockRepo->getId($id);
    }

    public function getAll()
    {
        return $this->historyStockRepo->getAll();
    }

    public function getWhere($data)
    {
        return $this->historyStockRepo->getWhere($data);
    }

    public function store($request)
    {
        return  $this->historyStockRepo->store($request);
    }

    public function update($id, $request)
    {
        return $this->historyStockRepo->update($id, $request);
    }

    public function verifikasiPengurangan($request)
    {
        if ($request->id == 1) {

            //Setuju
            $historyStock = $this->historyStockRepo->getId($request->verifikasiId);
            if ($historyStock->verifikasi == 1) {
                $response = [
                    'success' => true,
                    'message' => 'Sudah Diverifikasi.',
                ];
                return response()->json($response, 200);
            } else {
                $id = $historyStock->stock_id;
                $jumlahHapus = $historyStock->jumlah;
                $deskripsi = $request->deskripsi;

                $cekStock = $this->stockRepo->getId($id);
                if ($cekStock->jumlah >= $historyStock->jumlah) {
                    $response = DB::transaction(function () use ($id, $jumlahHapus, $deskripsi, $cekStock, $historyStock) {
                        $cekStockGudang = $this->gudangTransRepo->getWhere(['produk_id' => $cekStock->produk_id, 'gudang_id' => $historyStock->gudang_id])->first();
                        if ($jumlahHapus > $cekStockGudang->jumlah) {
                            $response = [
                                'success' => false,
                                'message' => 'Jumlah Hapus Melebihi Jumlah Stock Gudang',
                            ];
                            return $response;
                        }
                        $stock_awal = $cekStock->jumlah;
                        $stock_akhir = $stock_awal - $jumlahHapus;
                        $data = [
                            'jumlah' => $stock_akhir,
                        ];
                        $this->stockRepo->update($id, $data);

                        $stockGudangAkhir = $cekStockGudang->jumlah - $jumlahHapus;
                        $cekStockGudang->update(['jumlah' => $stockGudangAkhir]);

                        $produk = $this->produkRepo->getId($cekStock->produk_id)->first();
                        $produk->jumlah = $stock_akhir;
                        $produk->save();

                        $historyStock->jumlah = $jumlahHapus;
                        $historyStock->stock_awal = $cekStock->jumlah;
                        $historyStock->verifikasi = 1;
                        $historyStock->deskripsi = $deskripsi;
                        $historyStock->save();
                        $response = [
                            'success' => true,
                            'message' => 'Berhasil Diverifikasi',
                        ];
                        return $response;
                    });
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Jumlah Hapus Melebihi Jumlah Stock',
                    ];
                }
            }

            return response()->json($response, 200);
        } elseif ($request->id == 2) {
            //Tolak
            $historyStock = $this->historyStockRepo->getId($request->verifikasiId);
            $historyStock->update([
                'verifikasi' => 2,
                'deskripsi' => $historyStock->deskripsi . '<br> Dari: ' . strtolower(auth()->user()->name) . '<br>' . $request->deskripsi
            ]);
            // $cekUser = User::whereLevel('STAFF');
            // $cekUser->where('website_id', website()->id);
            // $cekUser = $cekUser->get();

            // foreach ($cekUser as $cekUser) {
            //     $judul = 'Pengurangan Stock Ditolak';
            //     $body = 'Jumlah : ' . $historyStock->jumlah;
            //     $token_1 = $cekUser->userToken;
            //     $comment = new Notifikasi();
            //     $comment->toId = '';
            //     $comment->toLevel = $cekUser->level;
            //     $comment->title = $judul;
            //     $comment->body = $body;
            //     $comment->image = '';
            //     $comment->url = url('/historyStock');
            //     $comment->status = 0;
            //     if (!empty($cekUser->userToken)) {
            //         sendNotif($token_1, $comment);
            //     }
            //     $comment->save();
            // }

            $response = [
                'success' => true,
                'message' => 'Telah Ditolak.',
            ];
            return response()->json($response, 200);
        }
    }

    public function getDatatable($data)
    {
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tgl', function ($row) {
                return tglIndo($row->tgl);
            })
            ->addColumn('kode_produk', function ($row) {
                return $row->produk->kode_produk;
            })
            ->addColumn('nama_produk', function ($row) {
                return '<a href="/produk/detail/' . $row->produk->slug . '" class="text-body">' . $row->produk->nama_produk . '</a>';
            })
            ->addColumn('gudang', function ($row) {
                if ($row->jenis == 4) {
                    $gudang = '-';
                } else {
                    $gudang = $row->gudang->nama;
                }
                return $gudang;
            })
            ->addColumn('harga_modal_lama', function ($row) {
                $rupiah = '-';
                if ($row->jenis <> 2) {
                    $harga = (float)$row->harga_modal_awal;
                    $rupiah = "<del class='text-danger'>Rp " . number_format($harga, 0, ',', '.') . "</del>";
                }
                return $rupiah;
            })
            ->addColumn('harga_jual_lama', function ($row) {
                $rupiah = '-';
                if ($row->jenis <> 2) {
                    $harga = (float)$row->harga_jual_awal;
                    $rupiah = "<del class='text-danger'>Rp " . number_format($harga, 0, ',', '.') . "</del>";
                }
                return $rupiah;
            })
            ->addColumn('harga_grosir_lama', function ($row) {
                $rupiah = '-';
                if ($row->jenis <> 2) {
                    $harga = (float)$row->harga_grosir_awal;
                    $rupiah = "<del class='text-danger'>Rp " . number_format($harga, 0, ',', '.') . "</del>";
                }
                return $rupiah;
            })
            ->addColumn('harga', function ($row) {
                $rupiah = '-';
                if ($row->jenis <> 2) {
                    $harga = (float)$row->harga;
                    $rupiah = "Rp " . number_format($harga, 0, ',', '.');
                }
                return $rupiah;
            })
            ->addColumn('harga_jual', function ($row) {
                $rupiah = '-';
                if ($row->jenis <> 2) {
                    $harga = (float)$row->harga_jual;
                    $rupiah = "Rp " . number_format($harga, 0, ',', '.');
                }
                return $rupiah;
            })
            ->addColumn('harga_grosir', function ($row) {
                $rupiah = '-';
                if ($row->jenis <> 2) {
                    $harga = (float)$row->harga_grosir;
                    $rupiah = "Rp " . number_format($harga, 0, ',', '.');
                }
                return $rupiah;
            })
            ->addColumn('jenis', function ($row) {
                $jenis = '';
                if ($row->jenis == 1) {
                    $jenis = '<span class="text-success">Masuk</span>';
                } elseif ($row->jenis == 2) {
                    $jenis = '<span class="text-primary">Keluar</span>';
                } elseif ($row->jenis == 3) {
                    $jenis = '<span class="text-danger">Hapus</span>';
                } elseif ($row->jenis == 4) {
                    $jenis = '<span class="text-info">Ubah Harga</span>';
                } elseif ($row->jenis == 5) {
                    $jenis = '<span class="text-danger">Transfer Dari</span>';
                } elseif ($row->jenis == 6) {
                    $jenis = '<span class="text-success">Transfer Ke</span>';
                }
                return $jenis;
            })
            ->addColumn('jumlah', function ($row) {
                if ($row->produk->satuan <> null) {
                    $satuan = $row->produk->satuan->name;
                } else {
                    $satuan = '';
                }
                $jumlah = 0;
                if ($row->jenis == 1) {
                    $jumlah = '<span class="text-success">+' . $row->jumlah . '</span> / ' . $satuan;
                } elseif ($row->jenis == 2) {
                    $jumlah = '<span class="text-primary">-' . $row->jumlah . '</span> / ' . $satuan;
                } elseif ($row->jenis == 3) {
                    $jumlah = '<span class="text-info">-' . $row->jumlah . '</span> / ' . $satuan;
                } elseif ($row->jenis == 4) {
                    // $jumlah = '<span class="text-default">' . $row->jumlah . '</span> / ' . $satuan;
                    $jumlah = '-';
                } elseif ($row->jenis == 5) {
                    $jumlah = '<span class="text-default">-' . $row->jumlah . '</span> / ' . $satuan;
                } elseif ($row->jenis == 6) {
                    $jumlah = '<span class="text-default">+' . $row->jumlah . '</span> / ' . $satuan;
                }
                return $jumlah;
            })
            ->addColumn('stock_awal', function ($row) {
                if ($row->jenis == 4) {
                    $stock_awal = '-';
                } else {
                    $stock_awal = $row->stock_awal;
                }
                return $stock_awal;
            })
            ->addColumn('stock_akhir', function ($row) {
                $stockAkhir = 0;
                if ($row->jenis == 1) {
                    $stockAkhir = (int)$row->stock_awal + $row->jumlah;
                } elseif ($row->jenis == 2) {
                    $stockAkhir = (int)$row->stock_awal - $row->jumlah;
                } elseif ($row->jenis == 3) {
                    $stockAkhir = (int)$row->stock_awal - $row->jumlah;
                } elseif ($row->jenis == 4) {
                    // $stockAkhir = (int)$row->stock_awal;
                    $stockAkhir = '-';
                } elseif ($row->jenis == 5) {
                    $stockAkhir = (int)$row->stock_awal - $row->jumlah;
                } elseif ($row->jenis == 6) {
                    $stockAkhir = (int)$row->stock_awal + $row->jumlah;
                }
                return $stockAkhir;
            })
            ->addColumn('deskripsi', function ($row) {
                return $row->deskripsi;
            })
            // ->addColumn('action', function ($row) {
            //     $btn = "";



            //     if ($row->verifikasi == 0) {
            //         if (auth()->user()->hasPermissionTo('mutasi-stock') or auth()->user()->hasPermissionTo('mutasi-stock-verifikasi')) {
            //             $btn = $btn . ' <a href="javascript:void(0)" class="btn btn-outline-info" id="btnVerifikasi" data-id="' . $row->id . '" >Verifikasi</a>';
            //         } elseif (auth()->user()->hasPermissionTo('mutasi-stock') or auth()->user()->hasPermissionTo('mutasi-stock-delete')) {
            //             $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteHistory"> <i class="mdi mdi-delete"></i></a>';
            //         } else {
            //             $btn = '<label class="text-info">Belum Verifikasi</label>';
            //         }
            //     } elseif ($row->verifikasi == 1) {
            //         $btn = '<label class="text-success">DISETUJUI</label>';
            //     } elseif ($row->verifikasi == 2) {
            //         $btn = '<label class="text-danger">DITOLAK</label>';
            //     }


            //     return $btn;
            // })

            // ->addColumn('status', function ($row) {
            //     $status = '';
            //     if ($row->status == 1) {
            //         $status = '<span class="text-success">Tambah Produk</span>';
            //     } elseif ($row->status == 2) {
            //         $status = '<span class="text-primary">Tambah Stock</span>';
            //     } elseif ($row->status == 3) {
            //         $status = '<span class="text-info">Jual</span>';
            //     } elseif ($row->status == 4) {
            //         $status = '<span class="text-warning">Pengurangan</span>';
            //     } elseif ($row->status == 5) {
            //         $status = '<span class="text-danger">Rusak</span>';
            //     }
            //     return $status;
            // })
            // ->addColumn('tanggal', function ($row) {
            //     return date('d-m-Y', strtotime($row->created_at));
            // })

            ->rawColumns(['nama_produk', 'tgl', 'gudang', 'jenis', 'jumlah', 'harga_modal_lama', 'harga_jual_lama', 'harga_grosir_lama', 'stock_akhir', 'harga', 'harga_jual', 'harga_grosir', 'deskripsi'])
            ->make(true);
    }
}
