<?php

namespace App\Services;

use App\Repositori\GudangTransRepositori;
use App\Repositori\HistoryStockRepositori;
use App\Repositori\ProdukRepositori;
use App\Repositori\StockJenisRepositori;
use App\Repositori\StockRepositori;
use App\Repositori\StockTransferRepositori;
use App\Repositori\SupplierTransRepositori;
use DataTables;
use Illuminate\Support\Facades\DB;

class StockService
{
    protected $stockRepo;
    protected $historyStockRepo;
    protected $produkRepo;
    protected $gudangTransRepo;
    protected $supplierTransRepo;
    protected $stockJenisRepo;
    protected $stockTransferRepo;
    public function __construct()
    {
        $this->stockRepo = new StockRepositori();
        $this->stockJenisRepo = new StockJenisRepositori();
        $this->stockTransferRepo = new StockTransferRepositori();
        $this->historyStockRepo = new HistoryStockRepositori();
        $this->produkRepo = new ProdukRepositori();
        $this->gudangTransRepo = new GudangTransRepositori();
        $this->supplierTransRepo = new SupplierTransRepositori();
    }

    public function getId($id)
    {
        return $this->stockRepo->getId($id);
    }

    public function getAll()
    {
        return $this->stockRepo->getAll();
    }

    public function getWhere($data)
    {
        return $this->stockRepo->getWhere($data);
    }

    public function store($request, $produkId = null)
    {
        $result = DB::transaction(function () use ($request, $produkId) {
            if ($produkId == null) {
                $produkId = $request->produk_id;
            }

            $nota = $request->nota;
            $supplier = $request->supplier;
            $jumlah = $request->jumlah;
            // $harga = str_replace('.', '', $request->harga);
            // $harga_jual = str_replace('.', '', $request->harga_jual);
            // $harga_grosir = str_replace('.', '', $request->harga_grosir);
            $deskripsi = $request->deskripsi;
            $tgl = date('Y-m-d');
            if ($request->tgl) {
                $tgl = date('Y-m-d', strtotime($request->tgl));
            }


            if ($jumlah < 1) {
                $response = [
                    'success' => false,
                    'message' => 'Masukkan Jumlah Stock.',
                ];
                return $response;
            }

            $cekDataStock = $this->getWhere(['produk_id' => $produkId]);
            $stockModel = $cekDataStock->first();

            $cek = $this->cekStockGudang($produkId, $request->gudang);
            $stock_awal = 0;
            $stockId = '';
            // if ($cekDataStock->count() > 0) {
            $stock_awal = $stockModel->jumlah;
            $stockBaru = $stock_awal + $jumlah;
            $stockId = $stockModel->id;
            $data = [
                'produk_id' => $produkId,
                'jumlah' => $stockBaru,
                'harga' => $stockModel->harga,
                'harga_jual' => $stockModel->harga_jual,
                'harga_grosir' => $stockModel->harga_grosir,
                'deskripsi' => $deskripsi,
                'website_id' => $stockModel->website_id,
            ];
            $this->update($stockId, $data);

            $this->stockJenisRepo->store([
                'jenis' => 1,
                'tgl' => $tgl,
                'nomor' => $nota,
                'produk_id' => $produkId,
                'gudang_id' => $request->gudang,
                'pemasok_id' => $supplier,
                'sales_id' => 0,
                'stock_awal' => (int)$cek->jumlah,
                'jumlah' => $jumlah,
                'harga' => $stockModel->harga,
                'harga_jual' => $stockModel->harga_jual,
                'harga_grosir' => $stockModel->harga_grosir,
                'harga_final'=>$stockModel->harga,
                'grosir'=>2,
                'valid'=>1,
                'deskripsi' => $request->deskripsi,
                'website_id' => website()->id,
            ]);

            $total = (int)$cek->jumlah + $jumlah;
            $this->gudangTransRepo->update($cek->id, ['jumlah' => $total]);
            // $this->supplierTransRepo->store(['supplier_id' => $supplier, 'produk_id' => $produkId, 'gudang_id' => $request->gudang, 'jumlah' => $jumlah, 'website_id' => website()->id]);

            // $this->historyStockRepo->store([
            //     'tgl' => $tgl,
            //     'produk_id' => $produkId,
            //     'no' => $nota,
            //     'jenis' => 1,
            //     'stock_awal' => (int)$cek->jumlah,
            //     'jumlah' => $jumlah,
            //     // 'harga_modal_awal' => $stockModel->harga,
            //     // 'harga_jual_awal' => $stockModel->harga_jual,
            //     // 'harga_grosir_awal' => $stockModel->harga_grosir,
            //     'harga' => $stockModel->harga,
            //     'harga_jual' => $stockModel->harga_jual,
            //     'harga_grosir' => $stockModel->harga_grosir,
            //     'deskripsi' => $deskripsi,
            //     'gudang_id' => $request->gudang,
            //     'pemasok_id' => $supplier,
            //     'sales_id' => 0,
            //     'website_id' => $stockModel->website_id,
            // ]);
            // } else {
            //     $data = [
            //         'produk_id' => $produkId,
            //         'jumlah' => $jumlah,
            //         'harga' => $harga,
            //         'harga_jual' => $harga_jual,
            //         'harga_grosir' => $harga_grosir,
            //         'deskripsi' => '',
            //         'website_id' => website()->id,
            //     ];
            //     $save = $this->stockRepo->store($data);
            //     $stockId = $save;
            //     $this->stockJenisRepo->store([
            //         'jenis' => 1,
            //         'tgl' => $tgl,
            //         'nomor' => $nota,
            //         'produk_id' => $produkId,
            //         'gudang_id' => $request->gudang,
            //         'pemasok_id' => $supplier,
            //         'sales_id' => 0,
            //         'jumlah' => $jumlah,
            //         'deskripsi' => $request->deskripsi,
            //         'website_id' => website()->id,
            //     ]);
            //     $this->gudangTransRepo->update($cek->id, [
            //         'jumlah' => $jumlah,
            //     ]);
            //     $this->supplierTransRepo->store(['supplier_id' => $supplier, 'produk_id' => $produkId, 'gudang_id' => $request->gudang, 'jumlah' => $jumlah, 'website_id' => website()->id]);

            //     $this->historyStockRepo->store([
            //         'tgl' => $tgl,
            //         'produk_id' => $produkId,
            //         'no' => $nota,
            //         'jenis' => 1,
            //         'stock_awal' => $cek->jumlah,
            //         'jumlah' => $jumlah,
            //         'harga' => $harga,
            //         'harga_jual' => $harga_jual,
            //         'harga_grosir' => $harga_grosir,
            //         'deskripsi' => '',
            //         'gudang_id' => $request->gudang,
            //         'pemasok_id' => $supplier,
            //         'sales_id' => 0,
            //         'website_id' => website()->id,
            //     ]);
            // }



            $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan.',
            ];
            return $response;
        });
        return $result;
    }

    public function storeKeluar($request)
    {
        $result = DB::transaction(function () use ($request) {
            $nota = $request->nota;
            $sales = $request->sales;
            $deskripsi = $request->deskripsi;
            $tgl = date('Y-m-d');
            if ($request->tgl) {
                $tgl = date('Y-m-d', strtotime($request->tgl));
            }

            foreach ($request->produk_id as $index => $pro) {
                $produkId = $request->produk_id[$index];
                $jumlah = $request->jumlah[$index];
                $gudang = $request->gudang_id[$index];

                if ($produkId <> '' and $gudang <> '' and $jumlah > 0) {

                    $cekDataStock = $this->getWhere(['produk_id' => $produkId]);
                    if ($cekDataStock->first()->jumlah < $jumlah) {
                        $response = [
                            'success' => false,
                            'message' => 'Stock Tidak Mencukupi.',
                        ];
                        return $response;
                    }

                    $stockModel = $cekDataStock->first();
                    $cek = $this->cekStockGudang($produkId, $gudang);

                    if ($cek->jumlah < $jumlah) {
                        $response = [
                            'success' => false,
                            'message' => 'Stock Gudang Tidak Mencukupi.',
                        ];
                        return $response;
                    }

                    $stock_awal = 0;

                    $stock_awal = $stockModel->jumlah;
                    $stockBaru = $stock_awal - $jumlah;
                    $data = [
                        'produk_id' => $produkId,
                        'jumlah' => $stockBaru,
                        'harga' => $stockModel->harga,
                        'harga_jual' => $stockModel->harga_jual,
                        'harga_grosir' => $stockModel->harga_grosir,
                        'deskripsi' => '',
                        'website_id' => $stockModel->website_id,
                    ];
                    $this->stockRepo->update($produkId, $data);

                    $this->stockJenisRepo->store([
                        'jenis' => 2,
                        'tgl' => $tgl,
                        'nomor' => $nota,
                        'produk_id' => $produkId,
                        'gudang_id' => $gudang,
                        'sales_id' => $sales,
                        'pemasok_id' => 0,
                        'stock_awal' => (int)$cek->jumlah,
                        'jumlah' => $jumlah,
                        'deskripsi' => $deskripsi,
                        'website_id' => website()->id,
                    ]);

                    $total = (int)$cek->jumlah - $jumlah;
                    $this->gudangTransRepo->update($cek->id, ['jumlah' => $total]);
                    // $this->supplierTransRepo->store(['supplier_id' => $supplier, 'produk_id' => $produkId, 'gudang_id' => $request->gudang, 'jumlah' => $jumlah, 'website_id' => website()->id]);

                    $this->historyStockRepo->store([
                        'tgl' => $tgl,
                        'produk_id' => $produkId,
                        'no' => $nota,
                        'jenis' => 2,
                        'stock_awal' => (int)$cek->jumlah,
                        'jumlah' => $jumlah,
                        // 'harga_modal_awal' => $stockModel->harga,
                        // 'harga_jual_awal' => $stockModel->harga_jual,
                        // 'harga_grosir_awal' => $stockModel->harga_grosir,
                        'harga' => $stockModel->harga,
                        'harga_jual' => $stockModel->harga_jual,
                        'harga_grosir' => $stockModel->harga_grosir,
                        'deskripsi' => $deskripsi,
                        'gudang_id' => $gudang,
                        'pemasok_id' => 0,
                        'sales_id' => $sales,
                        'website_id' => $stockModel->website_id,
                    ]);
                }
            }

            $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan.',
            ];
            return $response;
        });
        return $result;
    }

    public function update($id, $request)
    {
        return $this->stockRepo->update($id, $request);
    }

    public function pengurangan($request)
    {
        $id = $request['produk_id'];
        $nomor = $request['nota'];
        $gudang_id = $request['gudang_id'];
        $jumlahHapus = $request['jumlahHapus'];
        $deskripsi = $request['deskripsiHapus'];
        $tgl = date('Y-m-d', strtotime($request['tgl']));

        // $cekStockGudang = $this->gudangTransRepo->getWhere(['produk_id' => $id, 'gudang_id' => $gudang_id])->first();
        $cekStockGudang = $this->cekStockGudang($id, $gudang_id);
        if ($cekStockGudang->jumlah < $jumlahHapus) {
            $response = [
                'success' => false,
                'message' => 'Stock Gudang Tidak Mencukupi',
            ];
            return $response;
        }

        $cekStock = $this->stockRepo->getWhere(['produk_id' => $id])->first();
        if ($cekStock->jumlah >= $jumlahHapus) {
            $response = DB::transaction(function () use ($id, $tgl, $gudang_id, $jumlahHapus, $deskripsi, $cekStock, $cekStockGudang, $nomor) {
                $stock_awal = $cekStock->jumlah;
                $stock_akhir = $stock_awal - $jumlahHapus;
                $stock_gudang_awal = $cekStockGudang->jumlah;
                $stockGudangAkhir = $cekStockGudang->jumlah - $jumlahHapus;
                $cekStockGudang->update(['jumlah' => $stockGudangAkhir]);

                // $produk = $this->produkRepo->getId($cekStock->produk_id)->first();
                // $produk->update(['jumlah' => $stock_akhir]);

                $this->stockJenisRepo->store([
                    'jenis' => 3,
                    'tgl' => $tgl,
                    'nomor' => $nomor,
                    'produk_id' => $id,
                    'gudang_id' => $gudang_id,
                    'sales_id' => 0,
                    'pemasok_id' => 0,
                    'stock_awal' => (int)$stock_gudang_awal,
                    'jumlah' => $jumlahHapus,
                    'deskripsi' => $deskripsi,
                    'website_id' => website()->id,
                ]);

                // $this->historyStockRepo->store([
                //     'tgl' => $tgl,
                //     'produk_id' => $id,
                //     'jenis' => 3,
                //     'gudang_id' => $gudang_id,
                //     'stock_awal' => (int)$stock_gudang_awal,
                //     'jumlah' => $jumlahHapus,
                //     'harga' => $cekStock->harga,
                //     'harga_jual' => $cekStock->harga_jual,
                //     'harga_grosir' => $cekStock->harga_grosir,
                //     'deskripsi' => $deskripsi,
                //     'verifikasi' => 1,
                //     'website_id' => $cekStock->website_id,
                // ]);

                $cekStock->update(['jumlah' => $stock_akhir]);

                $response = [
                    'success' => true,
                    'message' => 'Berhasil Disimpan',
                ];

                return $response;
            });
        } else {
            $response = [
                'success' => false,
                'message' => 'Jumlah Hapus Melebihi Sisa Stock',
            ];
        }

        return $response;
    }

    public function storeTransfer($request)
    {
        $id = $request['produk_id'];
        $nota = $request['nota'];
        $dari = $request['dari_gudang_id'];
        $ke = $request['ke_gudang_id'];
        $jumlah = (int)$request['jumlah'];
        $deskripsi = $request['deskripsi'];
        $tgl = date('Y-m-d', strtotime($request['tgl']));

        $cekStock = $this->stockRepo->getWhere(['produk_id' => $id])->first();
        if ($cekStock->jumlah >= $jumlah) {
            $response = DB::transaction(function () use ($id, $tgl, $dari, $ke, $jumlah, $deskripsi, $cekStock, $nota) {
                $stock_dari = $this->cekStockGudang($id, $dari);
                $stock_dari_awal = $stock_dari->jumlah;
                $stock_ke = $this->cekStockGudang($id, $ke);
                $stock_ke_awal = $stock_ke->jumlah;
                if ($stock_dari->jumlah < $jumlah) {
                    $response = [
                        'success' => false,
                        'message' => 'Stock Gudang Tidak Mencukupi',
                    ];
                    return $response;
                }

                $sisa_stock_dari = $stock_dari->jumlah - $jumlah;
                $sisa_stock_ke = $stock_ke->jumlah + $jumlah;

                $this->gudangTransRepo->update($stock_dari->id, ['gudang_id' => $dari, 'jumlah' => $sisa_stock_dari, 'website_id' => $cekStock->website_id]);
                $this->gudangTransRepo->update($stock_ke->id, ['gudang_id' => $ke, 'jumlah' => $sisa_stock_ke, 'website_id' => $cekStock->website_id]);
                $this->stockTransferRepo->store([
                    'tgl' => $tgl,
                    'nomor' => $nota,
                    'produk_id' => $id,
                    'dari' => $dari,
                    'ke' => $ke,
                    'jumlah' => $jumlah,
                    'deskripsi' => $deskripsi,
                    'website_id' => website()->id,
                ]);

                // $this->historyStockRepo->store([
                //     'tgl' => $tgl,
                //     'produk_id' => $id,
                //     'jenis' => 6,
                //     'gudang_id' => $ke,
                //     'stock_awal' => $stock_ke_awal,
                //     'jumlah' => $jumlah,
                //     'harga' => $cekStock->harga,
                //     'harga_jual' => $cekStock->harga_jual,
                //     'harga_grosir' => $cekStock->harga_grosir,
                //     'deskripsi' => $deskripsi,
                //     'verifikasi' => 1,
                //     'website_id' => $cekStock->website_id,
                // ]);

                // $this->historyStockRepo->store([
                //     'tgl' => $tgl,
                //     'produk_id' => $id,
                //     'jenis' => 5,
                //     'gudang_id' => $dari,
                //     'stock_awal' => $stock_dari_awal,
                //     'jumlah' => $jumlah,
                //     'harga' => $cekStock->harga,
                //     'harga_jual' => $cekStock->harga_jual,
                //     'harga_grosir' => $cekStock->harga_grosir,
                //     'deskripsi' => $deskripsi,
                //     'verifikasi' => 1,
                //     'website_id' => $cekStock->website_id,
                // ]);





                $response = [
                    'success' => true,
                    'message' => 'Transfer Stock Berhasil',
                ];

                return $response;
            });
        } else {
            $response = [
                'success' => false,
                'message' => 'Jumlah Stock Tidak Mencukupi',
            ];
        }

        return $response;
    }

    public function cekStockGudang($produk_id, $gudang_id)
    {
        $cek = $this->gudangTransRepo->getWhere(['produk_id' => $produk_id, 'gudang_id' => $gudang_id])->select('id', 'jumlah');
        if ($cek->count() > 0) {
            return $cek->first();
        } else {
            $this->gudangTransRepo->store([
                'gudang_id' => $gudang_id,
                'produk_id' => $produk_id,
                'jumlah' => 0,
                'website_id' => website()->id,
            ]);
            return $cek = $this->gudangTransRepo->getWhere(['produk_id' => $produk_id, 'gudang_id' => $gudang_id])->select('id', 'jumlah')->first();
        }
    }


    public function ubahHarga($request)
    {

        $response = DB::transaction(function () use ($request) {
            $id = $request['produk_id'];
            $hargaModalBaru = str_replace('.', '', $request['harga_modal_baru']);
            $hargaJualBaru = str_replace('.', '', $request['harga_jual_baru']);
            $hargaGrosirBaru = str_replace('.', '', $request['harga_grosir_baru']);
            $deskripsi = $request['deskripsiHarga'];
            $tgl = date('Y-m-d', strtotime($request['tgl']));

            if ($hargaJualBaru < $hargaModalBaru) {
                $response = [
                    'success' => false,
                    'message' => 'Harga Jual harus lebih besar dari Harga Modal.',
                ];
                return $response;
            }

            $cekStock = $this->stockRepo->getWhere(['produk_id' => $id])->first();

            $hargaModalLama = $cekStock->harga;
            $hargaJualLama = $cekStock->harga_jual;
            $hargaGrosirLama = $cekStock->harga_grosir;

            $data = [
                'harga' => $hargaModalBaru,
                'harga_jual' => $hargaJualBaru,
                'harga_grosir' => $hargaGrosirBaru,
            ];




            $this->historyStockRepo->store([
                'tgl' => $tgl,
                'produk_id' => $id,
                'jenis' => 4,
                'gudang_id' => $cekStock->produk->gudang_id,
                'stock_awal' => $cekStock->jumlah,
                'jumlah' => 0,
                'harga_modal_awal' => $hargaModalLama,
                'harga_jual_awal' => $hargaJualLama,
                'harga_grosir_awal' => $hargaGrosirLama,
                'harga' => $hargaModalBaru,
                'harga_jual' => $hargaJualBaru,
                'harga_grosir' => $hargaGrosirBaru,
                'deskripsi' => $deskripsi,
                'website_id' => website()->id,
            ]);

            $cekStock->update($data);

            $response = [
                'success' => true,
                'message' => 'Berhasil Ubah Harga',
            ];
            return $response;
        });

        return $response;
    }

    public function getDatatable($produk)
    {
        try {
            return Datatables::of($produk)
                ->addIndexColumn()
                ->addColumn('kode_produk', function ($row) {
                    return $row->kode_produk;
                })
                ->addColumn('nama_produk', function ($row) {
                    return '<a href="'.url($row->website->username.'/produk/detail'.'/'.$row->slug).'" class="text-body">' . $row->nama_produk . '</a>';
                })
                ->addColumn('nama_kategori', function ($row) {
                    return $row->kategori->nama_kategori;
                })
                ->addColumn('harga', function ($row) {
                    $rupiah='Rp.0';
                    if($row->stock){
                        $harga = $row->stock->harga;
                        $rupiah = uang($harga);
                    }
                    return $rupiah;
                })
                ->addColumn('harga_jual', function ($row) {
                    $rupiah='Rp.0';
                    if($row->stock){
                        $harga = $row->stock->harga_jual;
                        $rupiah = uang($harga);
                    }
                    return $rupiah;
                })
                ->addColumn('harga_grosir', function ($row) {
                    $rupiah='Rp.0';
                    if($row->stock){
                        $harga = $row->stock->harga_grosir;
                        $rupiah = uang($harga);
                    }
                    return $rupiah;
                })
                ->addColumn('jumlah', function ($row) {
                    $jumlah=0;
                    if($row->stock){
                        $jumlah=$row->stock->jumlah;
                    }
                    return $jumlah;
                })
                ->addColumn('satuan', function ($row) {
                    $satuan = '';
                    if ($row->satuan) {
                        $satuan = $row->satuan->name;
                    }
                    return $satuan;
                })
                ->addColumn('profit', function ($row) {
                    $stock = $row->stock->jumlah;
                    $show = 0;
                    if ($stock) {
                        $profitEceran=0;
                        $profitGrosir=0;
                        $tModal = $stock * $row->stock->harga;
                        $tEceran = $stock * $row->stock->harga_jual;
                        $tGrosir = $stock * $row->stock->harga_grosir;
                        if($tEceran>0 and $tModal>0){
                        $profitEceran = (($tEceran - $tModal) / $tEceran) * 100;
                        }
                        if($tGrosir>0 and $tModal>0){
                        $profitGrosir = (($tGrosir - $tModal) / $tGrosir) * 100;
                        }
                        $show = "Eceran : <span class='text-primary'>" . number_format($profitEceran,0,',','') . "%</span><br>Grosir : <span class='text-success'>" . number_format($profitGrosir,0,',','') . "%</span>";
                    }
                    return $show;
                })

                ->addColumn('laba', function ($row) {
                    $stock = $row->stock->jumlah;
                    $show = 0;
                    if ($stock) {
                        $tModal = $stock * $row->stock->harga;
                        $tEceran = $stock * $row->stock->harga_jual;
                        $tGrosir = $stock * $row->stock->harga_grosir;
                        $labaEceran = $tEceran - $tModal;
                        $labaGrosir = $tGrosir - $tModal;
                        $show = "Eceran : <span class='text-primary'>" . uang($labaEceran) . "</span><br>Grosir : <span class='text-success'>" . uang($labaGrosir) . "</span>";
                    }
                    return $show;
                })
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
                ->addColumn('action', function ($row) {
                    $btn = "";
                    // if (session('level')==1){
                    // if (auth()->user()->hasPermissionTo('mutasi') or auth()->user()->hasPermissionTo('mutasi-list')) {
                    //     $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-original-title="History Stock" class="btn btn-outline-info btn-xs historyStock"> <i class="mdi mdi-eye"></i></a>';
                    // }
                    // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_stock="' . $row->id . '" data-original-title="Ubah Harga" class="btn btn-primary btn-xs ubahHarga"> <i class="mdi mdi-cash-usd-outline"></i></a>';

                    // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_stock="' . $row->id . '" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteStock"> <i class="mdi mdi-minus-circle"></i></a>';
                    //}

                    return $btn;
                })
                ->rawColumns(['kode_produk', 'nama_produk', 'nama_kategori', 'jumlah','profit','laba', 'satuan', 'nama_kategori', 'harga', 'harga_jual', 'harga_grosir', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getDatatableJenis($produk)
    {
        try {
            return Datatables::of($produk)
                ->addIndexColumn()
                ->addColumn('tgl', function ($row) {
                    return tglIndo($row->tgl);
                })
                ->addColumn('kode_produk', function ($row) {
                    return '<b>'.$row->produk->kode_produk.'</b>';
                })
                ->addColumn('gudang', function ($row) {
                    $nama = 'Gudang Umum';
                    if ($row->gudang) {
                        $nama = $row->gudang->nama;
                    }
                    return $nama;
                })
                ->addColumn('nama_produk', function ($row) {
                    return '<a href="'.url($row->produk->website->username.'/produk/detail'.'/'.$row->produk->slug).'" class="text-body">' . $row->produk->nama_produk . '</a>';
                })
                ->addColumn('nama_kategori', function ($row) {
                    return $row->produk->kategori->nama_kategori;
                })
                ->addColumn('kondisi', function ($row) {
                    return strtoupper($row->kondisi);
                })
                ->addColumn('jumlah', function ($row) {
                    return $row->jumlah;
                })
                ->addColumn('stock_akhir', function ($row) {
                    $stock_akhir=0;
                    if($row->jenis==1){
                        $stock_akhir=$row->stock_awal+$row->jumlah;
                    }elseif($row->jenis==2 or $row->jenis==3){
                        $stock_akhir=$row->stock_awal-$row->jumlah;
                    }
                    return $stock_akhir;
                })
                ->addColumn('harga', function ($row) {
                    return uang($row->harga_final);
                })

                ->addColumn('satuan', function ($row) {
                    return $row->produk->satuan->name;
                })
                ->addColumn('deskripsi', function ($row) {
                    return $row->deskripsi;
                })

                ->addColumn('action', function ($row) {
                    $btn = "";
                    // if (session('level')==1){
                    // if (auth()->user()->hasPermissionTo('mutasi') or auth()->user()->hasPermissionTo('mutasi-list')) {
                    //     $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-original-title="History Stock" class="btn btn-outline-info btn-xs historyStock"> <i class="mdi mdi-eye"></i></a>';
                    // }
                    // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_stock="' . $row->id . '" data-original-title="Ubah Harga" class="btn btn-primary btn-xs ubahHarga"> <i class="mdi mdi-cash-usd-outline"></i></a>';

                    // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_stock="' . $row->id . '" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteStock"> <i class="mdi mdi-minus-circle"></i></a>';
                    //}

                    return $btn;
                })
                ->rawColumns(['gudang', 'deskripsi', 'tgl','stock_akhir', 'kode_produk', 'nama_produk', 'nama_kategori', 'harga','jumlah', 'satuan', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getDatatableTransfer($produk)
    {
        try {
            return Datatables::of($produk)
                ->addIndexColumn()
                ->addColumn('tgl', function ($row) {
                    return tglIndo($row->tgl);
                })
                ->addColumn('nomor', function ($row) {
                    $nomor = '<a href="' . url("stock-transfer/detail/". $row->nomor). '" class="text-body fw-bold">' . $row->nomor . '</a>';
                    return $nomor;
                })
                ->addColumn('dari', function ($row) {
                    return $row->dari_gudang->nama;
                })
                ->addColumn('ke', function ($row) {
                    return $row->ke_gudang->nama;
                })
                ->addColumn('kode_produk', function ($row) {
                    return $row->produk->kode_produk;
                })
                ->addColumn('nama_produk', function ($row) {
                    return '<a href="'.url($row->produk->website->username.'/produk/detail'.'/'.$row->produk->slug).'" class="text-body">' . $row->produk->nama_produk . '</a>';
                })
                ->addColumn('nama_kategori', function ($row) {
                    return $row->produk->kategori->nama_kategori;
                })

                ->addColumn('jumlah', function ($row) {
                    return $row->jumlah;
                })
                ->addColumn('satuan', function ($row) {
                    return $row->produk->satuan->name;
                })
                ->addColumn('deskripsi', function ($row) {
                    return $row->deskripsi;
                })

                ->addColumn('action', function ($row) {
                    $btn = "";
                    // if (session('level')==1){
                    // if (auth()->user()->hasPermissionTo('mutasi') or auth()->user()->hasPermissionTo('mutasi-list')) {
                    //     $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-original-title="History Stock" class="btn btn-outline-info btn-xs historyStock"> <i class="mdi mdi-eye"></i></a>';
                    // }
                    // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_stock="' . $row->id . '" data-original-title="Ubah Harga" class="btn btn-primary btn-xs ubahHarga"> <i class="mdi mdi-cash-usd-outline"></i></a>';

                    // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_stock="' . $row->id . '" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteStock"> <i class="mdi mdi-minus-circle"></i></a>';
                    //}

                    return $btn;
                })
                ->rawColumns(['deskripsi', 'tgl','nomor', 'kode_produk', 'nama_produk', 'nama_kategori', 'jumlah', 'satuan', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
