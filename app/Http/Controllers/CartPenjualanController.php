<?php

namespace App\Http\Controllers;

use App\Repositori\GudangTransRepositori;
use App\Repositori\ProdukRepositori;
use App\Repositori\StockRepositori;
use App\Services\StockService;
use Illuminate\Http\Request;

class CartPenjualanController extends Controller
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
        return view('pos.cart-table');
    }

    public function add(Request $request)
    {
        // session()->put('cart');
        $id = $request->produkId;
        $gudang_id = $request->gudangId;
        if ($request->ubah) {
        $cekStock = $this->gudangTransRepo->getWhere(['produk_id' => $id, 'gudang_id' => $gudang_id]);
        $cekStock = $cekStock->first();
        $jumlah = 1;
        if($request->jumlah){
            $jumlah=$request->jumlah;
        }
        
        $harga=$cekStock->produk->stock->harga_jual;
        $grosir=2;
        if($request->grosir){
            $grosir=$request->grosir;
        }
        if($request->harga){
            $harga=$request->harga;
        }
    }else{
        $cekStock = $this->gudangTransRepo->getWhere(['produk_id' => $id, 'gudang_id' => $gudang_id]);
        if ($cekStock->count() < 1) {
            $response = [
                'success' => false,
                'message' => 'Produk Tidak Ada Di Gudang',
            ];
            return response()->json($response, 200);
        }
        $cekStock = $cekStock->first();
        $jumlah = 1;
        if($request->jumlah){
            $jumlah=$request->jumlah;
        }
        if ($cekStock->jumlah < $jumlah) {
            $response = [
                'success' => false,
                'message' => 'Stock tidak Cukup, Sisa Stock ' . $cekStock->jumlah,
            ];
            return response()->json($response, 200);
        }
        
        $harga=$cekStock->produk->stock->harga_jual;
        $grosir=2;
        if($request->grosir){
            $grosir=$request->grosir;
        }
        if($request->harga){
            $harga=$request->harga;
        }
    }
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
                "price" => $harga,
                "grosir" => $grosir,
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
        $cekStock = $this->gudangTransRepo->getWhere(['produk_id' => $request->produkId, 'gudang_id' => $request->gudangId]);
        if ($cekStock->count() < 1) {
            $response = [
                'success' => false,
                'message' => 'Produk Tidak Ada Digudang',
            ];
            return response()->json($response, 200);
        }
        $cekStock = $cekStock->first();
        if ($cekStock->jumlah < $request->jumlah) {
            $response = [
                'success' => false,
                'message' => 'Stock Gudang tidak Cukup, Sisa Stock ' . $cekStock->jumlah,
                'data' => ['jumlah' => $cekStock->jumlah]
            ];
            return response()->json($response, 200);
        }

        $message = 'Berhasil Diperbaharui';
        $cart = session()->get('cart');
        $cart[$request->id]["quantity"] = $request->jumlah;
        $min = $cekStock->produk->min_order;
        $max = $cekStock->produk->max_order;
        $cart[$request->id]["price"] = $cekStock->produk->stock->harga_jual;
        $cart[$request->id]["grosir"] = 2;
        if ($min > 0 or $max > 0) {
            if ($request->jumlah >= $min or $request->jumlah >= $min and $request->jumlah <= $max) {

                $cart[$request->id]["grosir"] = 1;
                $cart[$request->id]["price"] = $cekStock->produk->stock->harga_grosir;
                $message = 'Berhasil Diperbaharui, Harga Grosir Diterapkan';
            }
        }

        session()->put('cart', $cart);

        $response = [
            'success' => true,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }


}
