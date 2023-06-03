<?php

namespace App\Services;

use App\Repositori\ProdukRepositori;
use App\Repositori\StockRepositori;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DataTables;
use Illuminate\Support\Facades\DB;
use Image;

class ProdukService
{
    protected $produkRepo;
    protected $stockService;
    protected $stockRepo;
    public function __construct()
    {
        $this->produkRepo = new ProdukRepositori();
        $this->stockService = new StockService();
        $this->stockRepo = new StockRepositori();
    }

    public function store($request)
    {
        $slug = strtolower(preg_replace("/[^a-zA-Z0-9]/", "-", $request->slug));
        $cekProduk = $this->produkRepo->getWhere(['slug' => $slug, 'website_id' => website()->id])->count();
        if ($cekProduk > 0) {
            $response = [
                'success' => false,
                'message' => 'Slug sudah digunakan',
            ];
            return $response;
        }
        $filename = $this->uploadImage($request->file);

        $data['kode_produk'] = $request->kode_produk;
        $data['nama_produk'] = $request->nama_produk;
        $data['kategori_id'] = $request->kategori;
        $data['satuan_id'] = $request->satuan;
        $data['merek'] = $request->merek;
        $data['min_stock'] = $request->min_Stock;
        $data['berat'] = $request->berat;
        $data['slug'] = $slug;
        $data['min_order'] = $request->min;
        $data['max_order'] = $request->max;
        $data['keterangan'] = $request->keterangan;
        $data['deskripsi'] = $request->deskripsi;
        $data['gambar_utama'] = $filename;
        $data['website_id'] = website()->id;
        $result = DB::transaction(function () use ($request, $data) {
            // for($a=1;$a<500;$a++){
            $query = $this->produkRepo->store($data);

            $harga = str_replace('.', '', $request->harga);
            $harga_jual = str_replace('.', '', $request->harga_jual);
            $harga_grosir = str_replace('.', '', $request->harga_grosir);

            $saveStock = $this->stockRepo->store(['produk_id' => $query, 'jumlah' => 0, 'harga' => $harga, 'harga_jual' => $harga_jual, 'harga_grosir' => $harga_grosir, 'website_id' => website()->id]);
            // }
            if (!$saveStock) {
                DB::rollBack();
                $response = [
                    'success' => false,
                    'message' => 'Gagal Disimpan.',
                ];

                return $response;
            }

            QrCode::format('svg')->size(60)->generate($request->kode_produk, 'image/produk/qr' . '/' . $query . '.svg');
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
        $slug = strtolower(preg_replace("/[^a-zA-Z0-9]/", "-", $request->slug));
        $cekProduk = $this->produkRepo->getWhere(['slug' => $slug, 'website_id' => website()->id])->where('id', '<>', $id)->count();
        if ($cekProduk > 0) {
            $response = [
                'success' => false,
                'message' => 'Slug sudah digunakan',
            ];
            return $response;
        }
        $result = DB::transaction(function () use ($id, $request) {
            $produk = $this->produkRepo->getId($id);
            if ($request->file) {
                $request->validate([
                    'file' => 'required|image|mimes:png,jpeg,jpg|max:1024',
                ], [
                    'file' => 'Format Gambar di Izinkan png,jpeg,jpg',
                    'max' => 'Maximal ukuran file 1024KB',
                ]);
                $filename = $this->uploadImage($request->file);
            } else {
                $filename = $produk->gambar_utama;
            }
            $data['kode_produk'] = $request->kode_produk;
            $data['nama_produk'] = $request->nama_produk;
            // $data['gudang_id'] = $request->gudang;
            $data['kategori_id'] = $request->kategori;
            $data['satuan_id'] = $request->satuan;
            $data['merek'] = $request->merek;
            $data['berat'] = $request->berat;
            $data['slug'] = strtolower(preg_replace("/[^a-zA-Z0-9]/", "-", $request->slug));
            // $data['harga'] = str_replace('.', '', $request->harga);
            // $data['harga_jual'] = str_replace('.', '', $request->harga_jual);
            // $data['harga_grosir'] = str_replace('.', '', $request->harga_grosir);
            $data['min_order'] = $request->min;
            $data['max_order'] = $request->max;
            // $data['jumlah'] = $request->jumlah;
            $data['keterangan'] = $request->keterangan;
            $data['deskripsi'] = $request->deskripsi;
            $data['gambar_utama'] = $filename;
            $data['website_id'] = website()->id;

            $query = $produk->update($data);
            $path='image/produk/qr';
            if (!file_exists($path)) {
                mkdir($path, 755, true);
            }
            QrCode::format('svg')->size(60)->generate($request->kode_produk, $path . '/' . $id . '.svg');
            $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan.',
            ];

            return $response;
        });
        return $result;
    }

    public function getDatatable($produk, $type)
    {

        try {
            return Datatables::of($produk)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($row) {
                    return '<a href="' . url($row->website->username . '/produk/detail' . '/' . $row->slug) . '"><img src="/image/produk/small/' . $row->gambar_utama . '" alt="Produk ' . $row->nama_produk . '" title="Produk ' . $row->nama_produk . '" class="rounded me-2 float-left " height="48" /></a>
            <p class="m-0 text-wrap font-16">
            <a href="' . url($row->website->username . '/produk/detail' . '/' . $row->slug) . '" class="text-body">' . $row->nama_produk . '</a>
            </p>';
                })

                ->addColumn('nama_kategori', function ($row) {
                    if (!empty($row->kategori->nama_kategori)) {
                        return $row->kategori->nama_kategori;
                    } else {
                        return '';
                    }
                })

                ->addColumn('jumlah', function ($row) {
                    return $row->stock->jumlah . ' ' . $row->satuan->name;
                })
                ->addColumn('harga', function ($row) {
                    return "Rp " . number_format($row->stock->harga, 0, ',', '.');
                })
                ->addColumn('harga_jual', function ($row) {
                    return "Rp " . number_format($row->stock->harga_jual, 0, ',', '.');
                })
                ->addColumn('harga_grosir', function ($row) {
                    return "Rp " . number_format($row->stock->harga_grosir, 0, ',', '.');
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
                ->addColumn('qr', function ($row) {
                    // return QrCode::size(60)->generate(url('produk-detail') . '/' . $row->slug);
                    return '<img width="60px" src="/image/produk/qr/' . $row->id . '.svg"/>';
                })

                ->addColumn('action', function ($row) use ($type) {
                    if ($type == 1) {
                        $starttem = '<div class="dropdown  mb-2">
            <a href="#" class="dropdown-toggle arrow-none btn btn-outline-info btn-sm" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="uil uil-cog"></i> Aksi</a><div class="dropdown-menu dropdown-menu-end" style="">';
                        $btn = "";
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-original-title="Gambar Produk" class="dropdown-item gambarProduk"> <i class="mdi mdi-plus-circle"></i> Tambah Gambar</a>';
                        if (auth()->user()->hasPermissionTo('produk') or auth()->user()->hasPermissionTo('produk-edit')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-original-title="Edit" class="dropdown-item editProduk"> <i class="mdi mdi-square-edit-outline"></i> Ubah</a>';
                        }
                        if (auth()->user()->hasPermissionTo('produk') or auth()->user()->hasPermissionTo('produk-trash')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-status="trash" data-original-title="Delete" class="dropdown-item deleteProduk"> <i class="mdi mdi-delete"></i> Hapus</a>';
                        }
                        if (auth()->user()->hasPermissionTo('produk') or auth()->user()->hasPermissionTo('produk-create')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-original-title="Copy" class="dropdown-item copyProduk"> <i class="mdi mdi-content-duplicate"></i> Copy</a>';
                        }

                        $endtem = ' </div></div>';



                        return $starttem . $btn . $endtem;
                    } else {
                        $btn = "";
                        if (auth()->user()->hasPermissionTo('produk') or auth()->user()->hasPermissionTo('produk-trash')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-original-title="Restore" class="btn btn-outline-primary btn-sm restoreProduk"> Kembalikan</a>';
                        }
                        if (auth()->user()->hasPermissionTo('produk') or auth()->user()->hasPermissionTo('produk-delete')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-status="delete" data-original-title="Hapus" class="btn btn-outline-danger mt-1 ml-1 btn-sm deleteProduk"> Hapus</a>';
                        }

                        return $btn;
                    }
                })
                ->rawColumns(['kode_produk', 'nama_produk', 'nama_kategori', 'profit', 'laba', 'qr', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getDatatablePilih($produk)
    {

        try {
            return Datatables::of($produk)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($row) {
                    return '<a href="' . url($row->website->username . '/produk/detail' . '/' . $row->slug) . '"><img src="/image/produk/small/' . $row->produk->gambar_utama . '" alt="Produk ' . $row->produk->nama_produk . '" title="Produk ' . $row->produk->nama_produk . '" class="rounded me-2 float-left " height="48" /></a>
            <p class="m-0 text-wrap font-16">
            <a href="' . url($row->website->username . '/produk/detail' . '/' . $row->slug) . '" class="text-body">' . $row->produk->nama_produk . '</a>
            </p>';
                })

                ->addColumn('nama_kategori', function ($row) {
                    if (!empty($row->produk->kategori->nama_kategori)) {
                        return $row->produk->kategori->nama_kategori;
                    } else {
                        return '';
                    }
                })
                ->addColumn('kode_produk', function ($row) {
                    return $row->produk->kode_produk;
                })
                ->addColumn('jumlah', function ($row) {
                    return $row->jumlah . ' ' . $row->produk->satuan->name;
                })

                ->addColumn('harga_jual', function ($row) {
                    return "Rp " . number_format($row->produk->stock->harga_jual, 0, ',', '.');
                })
                ->addColumn('harga_grosir', function ($row) {
                    return "Rp " . number_format($row->produk->stock->harga_grosir, 0, ',', '.');
                })


                ->addColumn('action', function ($row) {
                    $btn = "";
                    $btn = $btn . ' <a href="javascript:void(0)"  data-id="' . $row->produk_id . '" data-kode="' . $row->produk->kode_produk . '"  data-name="' . $row->produk->nama_produk . '" data-stock="' . $row->jumlah . '" data-gudang="' . $row->gudang_id . '"  class="pilih"> <i class="mdi mdi-plus-circle"></i> Pilih</a>';
                    return $btn;
                })
                ->rawColumns(['kode_produk', 'nama_produk', 'nama_kategori', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function uploadImage($request)
    {
        $filename = time() . '.' . $request->extension();
        $request->move(public_path('image/produk'), $filename);
        $getFile = Image::make('image/produk/' . $filename);
        $patchTumbSmall = 'image/produk/small';
        $patchTumbLarge = 'image/produk/large';
        if ($request->getClientOriginalExtension() == "png") {
            $this->resized($getFile, null, null, $patchTumbSmall, $filename, false);
            $this->resized($getFile, null, null, $patchTumbLarge, $filename, false);
        } else {

            $this->resized($getFile, 300, null, $patchTumbLarge, $filename, true);
            $this->resized($getFile, 60, null, $patchTumbSmall, $filename, true);
        }
        return $filename;
    }

    function resized($file, $height, $width, $path, $filename, $resize)
    {
        $tumb = $file;
        if ($resize) {
            $tumb->resize($height, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if (!file_exists($path)) {
            mkdir($path, 755, true);
        }
        $tumb->save(public_path($path . '/' . $filename));

        return true;
    }
}
