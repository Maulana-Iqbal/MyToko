<?php

namespace App\Http\Controllers;

use App\Repositori\GudangTransRepositori;
use App\Repositori\ProdukRepositori;
use App\Repositori\StockRepositori;
use App\Services\StockService;
use Illuminate\Http\Request;

class CartPembelianController extends Controller
{
    protected $produkRepo;
    protected $stockService;
    protected $stockRepo;
    protected $gudangTransRepo;
    public function __construct()
    {
        $this->produkRepo = new ProdukRepositori();
        $this->stockService = new StockService();
        $this->stockRepo = new StockRepositori();
        $this->gudangTransRepo = new GudangTransRepositori();
    }

    public function getCartTable()
    {
        return view('pembelian/cart-table');
    }

    public function add(Request $request)
    {
        // session()->put('cart');
        $id = $request->produkId;
        $gudang_id = $request->gudangId;
        
        $jumlah = 1;
        if($request->jumlah){
            $jumlah=$request->jumlah;
        }
        $cekStock = $this->gudangTransRepo->getWhere(['produk_id' => $id, 'gudang_id' => $gudang_id]);
        if ($cekStock->count() < 1) {
            $this->gudangTransRepo->store([
                'gudang_id' => $gudang_id,
                'produk_id' => $id,
                'jumlah' => 0,
                'website_id' => website()->id,
            ]);
            $cekStock = $this->gudangTransRepo->getWhere(['produk_id' => $id, 'gudang_id' => $gudang_id]);
        }
        $cekStock = $cekStock->first();
        // if ($cekStock->jumlah < $jumlah) {
        //     $response = [
        //         'success' => false,
        //         'message' => 'Stock tidak Cukup, Sisa Stock ' . $cekStock->jumlah,
        //     ];
        //     return response()->json($response, 200);
        // }
        $cartId = $id . $gudang_id;
        $cart = session()->get('cart', []);
        if (isset($cart[$cartId])) {
            $response = [
                'success' => false,
                'message' => 'Gagal, Produk sudah ada di Daftar Order.',
            ];
            return response()->json($response, 200);
        } else {
            $cart[$cartId] = [
                "id" => $cartId,
                'produkId' => $id,
                'gudangId' => $gudang_id,
                'kode'=>$cekStock->produk->kode_produk,
                'gudangName' => $cekStock->gudang->nama,
                "name" => $cekStock->produk->nama_produk,
                "quantity" => $jumlah,
                "price" => $cekStock->produk->stock->harga,
                "grosir" => 2,
                "image" => url('image/produk/small').'/'.$cekStock->produk->gambar_utama
            ];
        }
        session()->put('cart', $cart);

        $response = [
            'success' => true,
            'message' => 'Berhasil Ditambahkan.',
        ];
        return response()->json($response, 200);
    }



    public function removeCart(Request $request)
    {

        if ($request->id) {

            $cart = session()->get('cart');

            if (isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }
            $response = [
                'success' => true,
                'message' => 'Berhasil Dihapus.',
            ];
            return response()->json($response, 200);
        }
    }


    public function updateCartJumlah(Request $request)
    {
        $cart = session()->get('cart');
        $cart[$request->id]["quantity"] = $request->jumlah;
        session()->put('cart', $cart);

        $response = [
            'success' => true,
            'message' => 'Berhasil Diperbaharui',
        ];
        return response()->json($response, 200);
    }


}
