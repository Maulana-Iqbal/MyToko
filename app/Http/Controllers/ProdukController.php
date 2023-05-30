<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Repositori\GaleriRepositori;
use App\Repositori\GudangTransRepositori;
use App\Repositori\KategoriRepositori;
use App\Repositori\ProdukRepositori;
use App\Repositori\StockRepositori;
use App\Services\ProdukService;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    protected $produkService;
    protected $produkRepo;
    protected $kategoriRepo;
    protected $stockService;
    protected $galeriRepo;
    protected $gudangTransRepo;
    protected $stockRepo;

    public function __construct()
    {
        $this->gudangTransRepo = new GudangTransRepositori();
        $this->produkService = new ProdukService();
        $this->produkRepo = new ProdukRepositori();
        $this->kategoriRepo = new KategoriRepositori();
        $this->stockService = new StockService();
        $this->galeriRepo = new GaleriRepositori();
        $this->stockRepo = new StockRepositori();

        $this->middleware('permission:produk|produk-list|produk-create|produk-edit|produk-trash|produk-delete', ['only' => ['index', 'show', 'trashTable']]);
        $this->middleware('permission:produk|produk-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:produk|produk-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:produk|produk-trash', ['only' => ['trash', 'restore', 'bulkDelete']]);
        $this->middleware('permission:produk|produk-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $produk = $this->produkRepo->getWhere($q)->latest();

        if ($request->produk) {
            $produk->whereIn('id', $request->produk);
        }
        if ($request->kategori) {
            $produk->whereIn('kategori_id', $request->kategori);
        }
        if ($request->satuan) {
            $produk->whereIn('satuan_id', $request->satuan);
        }
        
        if (auth()->user()->hasPermissionTo('show-all')) {
            if ($request->website) {
                $produk->whereIn('website_id', $request->website);
            }
        }
        $produk=$produk->get();
        if ($request->ajax()) {
            return $this->produkService->getDatatable($produk, 1);
        }
        return view('produk/produk');
    }

    public function produkPilih(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $produk = $this->gudangTransRepo->getWhere($q)->latest();
        if ($request->gudang) {
            $produk = $produk->where('gudang_id', $request->gudang);
        }

        $produk->get();
        if ($request->ajax()) {
            return $this->produkService->getDatatablePilih($produk);
        }
        return view('produk/produkPilih');
    }


    public function trashTable(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $produk = $this->produkRepo->getAllTrashed();
        if ($request->ajax()) {
            return $this->produkService->getDatatable($produk, 2);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:produk,kode_produk',
            'slug' => 'required',
            'kategori' => 'required',
            'harga' => 'required',
            'harga_jual' => 'required',
            'berat' => 'required',
            'keterangan' => 'required',
            'file' => 'required|image|mimes:png,jpeg,jpg|max:1024',
        ], [
            'required' => 'Data Belum Lengkap.',
            'file' => 'Format Gambar di Izinkan png,jpeg,jpg',
            'max' => 'Maximal ukuran file 1024KB/1MB',
        ]);

        $response = $this->produkService->store($request);
        return response()->json($response, 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:produk,kode_produk,' . $request->id_produk,
            'nama_produk' => 'required',
            'slug' => 'required',
            'kategori' => 'required',
            'berat' => 'required',
            'keterangan' => 'required',
        ], [
            'kode_produk.unique' => 'Kode Produk Sudah Ada...!!!',
            'required' => 'Data Belum Lengkap.',
        ]);
        $response = $this->produkService->update($request->id_produk, $request);
        return response()->json($response, 200);
    }

    public function show($username, $id)
    {
        $toko = Website::where('username', $username)->select('id')->first();
        $produk = $this->produkRepo->getWhere(['slug' => $id, 'website_id' => $toko->id])->first();
        $galeri =   $this->galeriRepo->getWhere(['data_id' => $produk->id])->get();
        // dd($produk);
        if (auth()->user() <> null and auth()->user()->level <> 'CUSTOMER') {
            return view('produk/detail', compact('produk', 'galeri'));
        } else {
            $dilihat = $produk->dilihat + 1;
            $produk->dilihat = $dilihat;
            $produk->save();

            $produkPage = true;
            return view('eshop.detail', compact('produk', 'galeri', 'produkPage'));
        }
    }


    public function getId($id)
    {
        $produk = $this->produkRepo->getWhere(['id' => $id])->with('kategori')->with('stock')->first();
        $response = [
            'success' => true,
            'message' => 'Berhasil Mengambil Data',
            'data' => $produk,
        ];
        return response()->json($response, 200);
    }



    public function edit($id)
    {
        $produk = $this->produkRepo->getWhere(['id' => $id])->with('stock')->with('kategori')->with('satuan')->with('gudang')->first();
        $response = [
            'success' => true,
            'message' => 'Berhasil Mengambil Data',
            'data' => $produk,
        ];
        return response()->json($response, 200);
    }

    public function restore($id)
    {
        $response = DB::transaction(function () use ($id) {
            $produk = $this->produkRepo->getWhere(['id' => $id])->withTrashed()->restore();
            $stock = $this->stockService->getWhere(['produk_id' => $id])->withTrashed()->restore();
            $response = [
                'success' => true,
                'message' => 'Berhasil Dikembalikan.',
            ];
            return $response;
        });

        return response()->json($response, 200);
    }

    public function trash($id)
    {
        $cekStock = $this->stockService->getWhere(['produk_id' => $id])->first();
        if ($cekStock->jumlah > 0) {
            $response = [
                'success' => false,
                'message' => 'Data stock produk masih ada, Produk tidak dapat dihapus.',
            ];
            return response()->json($response, 200);
        }

        $cekOrder =
            // $result = DB::transaction(function () use ($id) {
            $this->produkRepo->getId($id)->delete();
        // $this->stockService->getWhere(['produk_id'=> $id])->delete();
        // Order::where('produk_id', $id)->delete();
        // return true;
        // });
        // return response
        // if ($result) {
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        // } else {
        //     $response = [
        //         'success' => false,
        //         'message' => 'Data Produk gagal dihapus.',
        //     ];
        // }
        return response()->json($response, 200);
    }

    public function delete($id)
    {
        $result = DB::transaction(function () use ($id) {
            // $dijual = Order::where('produk_id', $id)->count();
            // if ($dijual > 0) {
            //     $response = [
            //         'success' => false,
            //         'message' => 'Produk yang pernah dijual tidak dapat dihapus.',
            //     ];
            // } else {
            $this->produkRepo->getWhere(['id' => $id])->withTrashed()->forceDelete();
            // $this->stockService->getWhere(['produk_id'=> $id])->withTrashed()->forceDelete();
            $response = [
                'success' => true,
                'message' => 'Berhasil Dihapus.',
            ];
            // }
            return $response;
        });
        // return response

        return response()->json($result, 200);
    }



    public function copyProduk(Request $request)
    {
        DB::transaction(function () use ($request) {
            $cek = $this->produkRepo->getId($request->id);
            $new = $cek->replicate()->fill([
                'kode_produk' => createCodeProduct(),
                'nama_produk' => $cek->nama_produk . '-copy',
                'slug' => $cek->slug . '-copy',
            ]);
            $new->save();
            $id = $new->id;
            $data = [
                'produk_id' => $id,
                'jumlah' => 0,
                'harga' => $cek->stock->harga,
                'harga_jual' => $cek->stock->harga_jual,
                'harga_grosir' => $cek->stock->harga_grosir,
                'deskripsi' => '',
                'website_id' => website()->id,
            ];
            $this->stockRepo->store($data);
        });
        $response = [
            'success' => true,
            'message' => 'Berhasil Disimpan.',
        ];
        return response()->json($response, 200);
    }

    public function produkPage(Request $request)
    {
        $produks = $this->produkRepo->getWhere(null)->with(['stock' => function ($q) {
            $q->orderBy('harga_jual', 'asc')->where('jumlah', '>=', 1);
        }])->has('stock')->latest()->paginate(20);
        $kategori = $this->kategoriRepo->orderByName();
        $produkSlider = $this->galeriRepo->getWhere(['jenis' => 'produkSlider'])->get();
        $page = 'produk-page';
        $html = '';
        if ($request->ajax()) {
            foreach ($produks as $key => $produk) {
                $stock = $produk->stock->first();
                if ($stock == null) {
                } else {
                    $btn = '';
                    if ($produk->jenis_jual == 1) {
                        $btn = '<a
                    href="/cart/add/' . $stock->id . '"
                    class="btn btn-outline-dark m-2"><i class="fa fa-cart-plus text-danger"></i> Add To Cart</a>';
                    } elseif ($produk->jenis_jual == 2) {
                        $btn = '<a style="font-size:12px"
                    href="https://wa.me/6282283803383?text=Saya%20ingin%20memesan%20produk%20kode%20' . $produk->kode_produk . '%20nama%20produk%20' . $produk->nama_produk . '"
                    class="btn btn-outline-success m-2"><i class="fa fa-whatsapp"></i>
                    Pesan
                    Sekarang</a>';
                    }
                    $harga = '';
                    if ($produk->jenis_jual == 1) {
                        $harga = "Rp " . number_format($stock->harga_jual, 0, ',', '.') . ' / ' . $stock->satuan->name;
                    }
                    $html .= ' <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 aos-init" data-aos="zoom-in"
                data-aos-delay="20">
                <div class="box" style="padding-top: 0px; padding-bottom: 15px; padding-left:0; padding-right:0;">
                    <a style="text-decoration: none;" href="' . url($produk->website->username . '/produk/detail' . '/' . $produk->slug) . '">


                        <img src="/image/produk/large/' . $produk->gambar_utama . '" class="img-fluid"
                            style="padding: 0; width:100%; overflow:hidden;" alt="" />
                        <h3
                            style="color: #2b2a2a; margin-bottom: 5px; font-size: 12px; font-weight:unset; margin-top:10px;">
                            ' . $produk->nama_produk . '</h3>
                    </a>
                    <small style="color: #919191; margin-bottom: 5px; font-size: 12px; font-weight:unset">
                        Kode : ' . $produk->kode_produk . '<br>
                    </small>
                    ' . $harga . '<br>' . $btn . '
                </div>
            </div>';
                }
            }
            return $html;
        }
        return view('website.produk-page', compact('produks', 'kategori', 'produkSlider', 'page'));
    }

    public function produkKategori(Request $request, $id)
    {
        $kategori = $this->kategoriRepo->orderByName();
        $cekId = $this->kategoriRepo->getWhere(['slug' => $id])->first();
        $produks = $this->produkRepo->getWhere(['kategori_id' => $cekId->id])->with(['stock' => function ($q) {
            $q->orderBy('harga_jual', 'asc')->where('jumlah', '>=', 1);
        }])->has('stock');
        $total = $produks->count();
        $produks = $produks->paginate(20);
        $produkSlider = $this->galeriRepo->getWhere(['jenis' => 'produkSlider'])->get();
        $page = 'produk-kategori/' . $id;
        $html = '';
        if ($request->ajax()) {
            foreach ($produks as $key => $produk) {
                $stock = $produk->stock->first();
                if ($stock == null) {
                } else {
                    $btn = '';
                    if ($produk->jenis_jual == 1) {
                        $btn = '<a
                    href="/cart/add/' . $stock->id . '"
                    class="btn btn-outline-dark m-2"><i class="fa fa-cart-plus text-danger"></i> Add To Cart</a>';
                    } elseif ($produk->jenis_jual == 2) {
                        $btn = '<a style="font-size:12px"
                    href="https://wa.me/6282283803383?text=Saya%20ingin%20memesan%20produk%20kode%20' . $produk->kode_produk . '%20nama%20produk%20' . $produk->nama_produk . '"
                    class="btn btn-outline-success m-2"><i class="fa fa-whatsapp"></i>
                    Pesan
                    Sekarang</a>';
                    }
                    $harga = '';
                    if ($produk->jenis_jual == 1) {
                        $harga = "Rp " . number_format($stock->harga_jual, 0, ',', '.') . ' / ' . $stock->satuan->name;
                    }
                    $html .= ' <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 aos-init" data-aos="zoom-in"
                data-aos-delay="20">
                <div class="box" style="padding-top: 0px; padding-bottom: 15px; padding-left:0; padding-right:0;">
                    <a style="text-decoration: none;" href="' . url($produk->website->username . '/produk/detail' . '/' . $produk->slug) . '">


                        <img src="/image/produk/large/' . $produk->gambar_utama . '" class="img-fluid"
                            style="padding: 0; width:100%; overflow:hidden;" alt="" />
                        <h3
                            style="color: #2b2a2a; margin-bottom: 5px; font-size: 12px; font-weight:unset; margin-top:10px;">
                            ' . $produk->nama_produk . '</h3>
                    </a>
                    <small style="color: #919191; margin-bottom: 5px; font-size: 12px; font-weight:unset">
                        Kode : ' . $produk->kode_produk . '<br>
                    </small>
                    ' . $harga . '<br>' . $btn . '
                </div>
            </div>';
                }
            }
            return $html;
        }
        return view('website.produk-page', compact('produks', 'kategori', 'produkSlider', 'total', 'page'));
    }

    public function kode()
    {
        $response = [
            'success' => true,
            'message' => 'Berhasil',
            'data' => createCodeProduct()
        ];
        return response()->json($response, 200);
    }

    public function produkCari(Request $request)
    {
        $produks = $this->produkRepo->getWhere(null)->where('nama_produk', 'LIKE', '%' . $request->id . '%')->orWhere('kode_produk', $request->id)->with(['stock' => function ($q) {
            $q->orderBy('harga_jual', 'asc')->where('jumlah', '>=', 1);
        }])->has('stock');
        $total = $produks->count();
        $produks = $produks->paginate(20);
        $kategori = $this->kategoriRepo->orderByName();
        $produkSlider = $this->galeriRepo->getWhere(['jenis' => 'produkSlider'])->get();
        $searchValue = $request->id;
        $page = 'produk-cari/' . $request->id;
        $html = '';
        if ($request->ajax()) {
            foreach ($produks as $key => $produk) {
                $stock = $produk->stock->first();
                if ($stock == null) {
                } else {
                    $btn = '';
                    if ($produk->jenis_jual == 1) {
                        $btn = '<a
                    href="/cart/add/' . $stock->id . '"
                    class="btn btn-outline-dark m-2"><i class="fa fa-cart-plus text-danger"></i> Add To Cart</a>';
                    } elseif ($produk->jenis_jual == 2) {
                        $btn = '<a style="font-size:12px"
                    href="https://wa.me/6282283803383?text=Saya%20ingin%20memesan%20produk%20kode%20' . $produk->kode_produk . '%20nama%20produk%20' . $produk->nama_produk . '"
                    class="btn btn-outline-success m-2"><i class="fa fa-whatsapp"></i>
                    Pesan
                    Sekarang</a>';
                    }
                    $harga = '';
                    if ($produk->jenis_jual == 1) {
                        $harga = "Rp " . number_format($stock->harga_jual, 0, ',', '.') . ' / ' . $stock->satuan->name;
                    }
                    $html .= ' <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 aos-init" data-aos="zoom-in"
                data-aos-delay="20">
                <div class="box" style="padding-top: 0px; padding-bottom: 15px; padding-left:0; padding-right:0;">
                    <a style="text-decoration: none;" href="' . url($produk->website->username . '/produk/detail' . '/' . $produk->slug) . '">


                        <img src="/image/produk/large/' . $produk->gambar_utama . '" class="img-fluid"
                            style="padding: 0; width:100%; overflow:hidden;" alt="" />
                        <h3
                            style="color: #2b2a2a; margin-bottom: 5px; font-size: 12px; font-weight:unset; margin-top:10px;">
                            ' . $produk->nama_produk . '</h3>
                    </a>
                    <small style="color: #919191; margin-bottom: 5px; font-size: 12px; font-weight:unset">
                        Kode : ' . $produk->kode_produk . '<br>
                    </small>
                    ' . $harga . '<br>' . $btn . '
                </div>
            </div>';
                }
            }
            return $html;
        }
        return view('website.produk-page', compact('produks', 'kategori', 'produkSlider', 'total', 'page', 'searchValue'));
    }


    public function getGambarProduk($id)
    {
        $galeri = $this->galeriRepo->getWhere(['data_id' => $id, 'jenis' => 'produk'])->get();
        foreach ($galeri as $galeri) {
            echo '<div class="col-lg-4 col-md-4 col-4 mb-2">
            <a href="/galeriImage/' . $galeri->gambar . '" class="">
                <img class="img-fluid img-thumbnail" src="/galeriImage/' . $galeri->gambar . '" alt="">
            </a>
            <button data-id_produk="' . $galeri->data_id . '" data-id_gambar="' . $galeri->id . '" class="btn-danger mt-1 deleteGaleri"><i class="mdi mdi-delete"></i> Hapus</button>
        </div>';
        }
    }

    public function simpanGambar(Request $request)
    {


        $request->validate([
            'file.*' => 'required|mimes:png,jpeg,jpg,JPEG,JPG|max:1024',
        ], [
            'max' => 'Maximal ukuran file 1024KB/1MB',
            'mimes' => 'Format Gambar di Izinkan png,jpeg,jpg',

        ]);



        $cek = $this->galeriRepo->getWhere(['data_id' => $request->dataId])->count();
        $jml = count($request->file('file'));
        $total = $cek + $jml;
        if ($total <= 3) {

            if ($request->hasfile('file')) {

                $images = $request->file('file');

                foreach ($images as $image) {
                    $fileName = time() . '-' . $image->getClientOriginalName();
                    $image->move(public_path('galeriImage'), $fileName);
                    $this->galeriRepo->store(['gambar' => $fileName, 'data_id' => $request->dataId, 'jenis' => 'produk', 'deskripsi' => '']);
                }
            }
            $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan.',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Maksimal Hanya 3 Gambar / Produk.',
            ];
            return response()->json($response, 200);
        }
    }


    // public function getWithView($produk)
    // {
    //     $produk = $this->produkRepo->query()->where('nama_produk', 'LIKE', '%' . $produk . '%')->orWhere('kode_produk', $produk);
    //     $jumlah = $produk->count();
    //     $hasil = $produk->limit(10)->get();
    //     echo ' <div class="dropdown-header noti-title">
    //     <h5 class="text-overflow mb-2">Ditemukan <span class="text-danger">' . $jumlah . '</span> hasil</h5>
    //     </div>';
    //     foreach ($hasil as $value) {
    //         echo ' <a href="javascript:void(0);" id="addProduk" data-id="' . $value->id . '" class="dropdown-item">
    //         <div class="d-flex">
    //         <img class="d-flex me-2 rounded-circle" src="/image/produk/small/' . $value->gambar_utama . '" alt="' . $value->nama_produk . '" height="32">
    //         <div class="w-100">
    //         <h5 class="m-0 font-14">' . $value->nama_produk . '</h5>
    //         <span class="font-12 mb-0">' . $value->kategori->nama_kategori . '</span>

    //         </div>
    //         </div>
    //         </a>';
    //     }
    // }

    public function getOne($id)
    {
        $produk = $this->produkRepo->getId($id);
        return view('produk.produkSelect', compact(['produk']));
    }

    public function getHargaJual(Request $request)
    {
        if ($request->stockId) {
            $cekHarga = $this->stockService->getWhere(['id' => $request->stockId])->first();
            $harga = $cekHarga->harga_jual;
            $jumlah = $cekHarga->jumlah;
            $response = [
                'success' => true,
                'message' => 'Berhasil Diperbaharui.',
                'data' => ['harga_jual' => $harga, 'jumlah' => $jumlah]
            ];
            return response()->json($response, 200);
        }
    }

    public function produkList()
    {
        return view('produk.produkList');
    }

    public function select(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $data = $this->produkRepo->getWhere($q)->select('id', 'nama_produk', 'kode_produk')->orderBy('nama_produk', 'asc');
        if ($request->search) {
            $data = $data->where('nama_produk', 'like', '%' . $request->search . '%')->orWhere('kode_produk', $request->search);
        }
        $data = $data->get();
        $response = array();
        foreach ($data as $data) {
            $response[] = array(
                "id" => $data->id,
                "text" => $data->kode_produk . ' - ' . $data->nama_produk,
            );
        }
        return response()->json($response);
    }


    public function apiProduk(Request $request)
    {
        $produk = $this->produkRepo->getApiProduk($request);
        if ($produk) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $produk,
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => [],
            ];
        }
        return response()->json($response, 200);
    }

    public function apiPopular(Request $request)
    {
        $produk = $this->produkRepo->getApiPopular($request);
        if ($produk) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $produk,
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => [],
            ];
        }
        return response()->json($response, 200);
    }

    public function apiProdukId(Request $request)
    {
        $produk = $this->produkRepo->getApiProdukId($request->id);

        if ($produk) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $produk,
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => [],
            ];
        }
        return response()->json($response, 200);
    }

    public function apiProdukSlug(Request $request)
    {
        $produk = $this->produkRepo->getApiProdukSlug($request->slug);

        if ($produk) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $produk,
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => [],
            ];
        }
        return response()->json($response, 200);
    }
}
