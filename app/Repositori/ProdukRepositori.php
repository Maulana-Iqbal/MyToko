<?php

namespace App\Repositori;

use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class ProdukRepositori
{
    protected $produk;
    protected $kategoriRepo;
    public function __construct()
    {
        $this->produk = new Produk();
        $this->kategoriRepo = new KategoriRepositori();
    }

    public function query()
    {
        return $this->produk->query();
    }

    public function getAll()
    {
        return $this->produk->all();
    }

    public function getId($id)
    {
        return $this->produk->with('kategori')->with('gudang')->with('satuan')->with('galeri')->find($id);
    }

    public function getWhere($data)
    {
        return $this->produk->where($data);
    }

    public function getAllTrashed()
    {
        return $this->produk->onlyTrashed()->get();
    }

    public function getApiProduk($request)
    {
        $perPage = 10;

        if ($request->perPage) {
            $perPage = (int)$request->perPage;
        }

        $produk = $this->produk->with('kategori')->with('stock')->with('galeri')->with('gudang')->with('satuan');

        if ($request->search) {
            $produk->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->kodeProduk) {
            $produk->where('kode_produk', $request->kodeProduk);
        }

        if ($request->produkSlug) {
            $produk->where('produk.slug', $request->produkSlug);
        }
        if ($request->sort) {
            $produk->orderBy('produk.created_at', $request->sort);
        }

        if ($request->produkId) {
            $produk->where('produk.id', $request->produkId);
        }

        if ($request->kategoriId) {
            $produk->where('kategori_id', $request->kategoriId);
        }

        if ($request->harga) {
            $produk->where('harga_jual', $request->harga);
        }

        if ($request->kategoriSlug) {
            $kategori = $this->kategoriRepo->getWhere(['slug' => $request->kategoriSlug])->selectRaw('id')->first();
            if (isset($kategori->id)) {
                //
                $produk->where('kategori_id', $kategori->id);
            } else {
                //tidak ditemukan
                $produk->where('kategori_id', 0);
            }
        }

       

        // if ($request->terlaris) {
        //     $produk->leftJoin('order', 'order.produk_id', '=', 'produk.id');
        //     $produk->selectRaw('produk.*,count(order.id) as terjual');
        //     $produk->where('order.status_data', 2);
        //     $produk->groupBy('produk.id');
        // }

        $produk = $produk->paginate($perPage);
        $produk->getCollection()->transform(function ($produk) {
            $produk->stock->harga_jual = 'Rp. '.number_format($produk->stock->harga_jual,'0',',','.');
            $produk->stock->harga_grosir = 'Rp. '.number_format($produk->stock->harga_grosir,'0',',','.');
            $produk->stock->harga = 0;
            if($produk->dilihat==null){
                $produk->dilihat=0;
            }
            return $produk;
        });

        return $produk;
    }

    public function getApiPopular($request){
        
        $limit=5;
        if($request->limit){
            $limit=(int)$request->limit;
        }
        $produk = $this->produk->with('stock')->limit($limit)->orderBy('dilihat','desc')->get();
        //replace key
        $produk->each(function ($data){
            $data->harga_jual='Rp. '.number_format($data->harga_jual,'0',',','.');
            if($data->dilihat==null){
                $data->dilihat=0;
            }
        });
        //for paginate
        // $produk->getCollection()->transform(function ($produk) {
        //     $produk->harga_jual = 'Rp. '.number_format($produk->harga_jual,'0',',','.');
        //     return $produk;
        // });
        
        //select key
        // $produk = $produk->map(function($produk, $key) {									
        //     return [
        //             'harga_jual' => 'Rp. '.number_format($produk->harga_jual,'0',',','.')
        //         ];
        //     });
        return $produk;
    }
    
    public function getApiProdukId($id){
        $produk = $this->produk->with(['stock' => function ($q) {
            $q->with('satuan');
        }])->with('kategori')->with('galeri')->whereId($id)->first();
        return $produk;
    }

    public function getApiProdukSlug($slug){
        $produk = $this->produk->with(['stock' => function ($q) {
            $q->with('satuan');
        }])->with('kategori')->with('galeri')->whereSlug($slug)->first();
        return $produk;
    }


    public function store($request)
    {
        $result = $this->produk->create($request);
        return $result->id;
    }

    public function update($id, $request)
    {
        $produk = $this->produk->find($id)->update($request);
        return $produk;
    }
}
