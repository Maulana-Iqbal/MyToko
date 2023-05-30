<?php

namespace App\Http\Controllers;

use App\Repositori\GudangRepositori;
use App\Repositori\GudangTransRepositori;
use App\Repositori\ProdukRepositori;
use App\Repositori\StockRepositori;
use App\Services\StockService;
use Illuminate\Http\Request;

class CartStockTransferController extends Controller
{
    protected $produkRepo;
    protected $stockService;
    protected $stockRepo;
    protected $gudangTransRepo;
    protected $gudangRepo;
    public function __construct()
    {
        $this->produkRepo = new ProdukRepositori();
        $this->stockService = new StockService();
        $this->stockRepo = new StockRepositori();
        $this->gudangTransRepo = new GudangTransRepositori();
        $this->gudangRepo=new GudangRepositori();
    }

    public function getCartTable()
    {
        return view('stock-transfer.cart-table');
    }

    public function add(Request $request)
    {
        // session()->put('cart');
        $id = $request->produkId;
        $dari = $request->dari;
        $ke=$request->ke;
        
        $cekStock = $this->gudangTransRepo->getWhere(['produk_id' => $id, 'gudang_id' => $dari]);
        if ($cekStock->count() < 1) {
            $response = [
                'success' => false,
                'message' => 'Produk Tidak Ada Di Gudang',
            ];
            return response()->json($response, 200);
        }

        $cekStock = $cekStock->first();

        $cekKe = $this->gudangRepo->getId($ke);
       
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
        
        
        $grosir=2;
        if($request->grosir){
            $grosir=$request->grosir;
        }
        $harga=$cekStock->produk->stock->harga;
        if($request->harga){
            $harga=$request->harga;
        }
        $cartId = $id . $dari.$ke;
        $cart = session()->get('cart', []);
        if (isset($cart[$cartId])) {
            $response = [
                'success' => false,
                'message' => 'Gagal, Produk sudah ada di Daftar Transfer.',
            ];
            return response()->json($response, 200);
        } else {
            $cart[$cartId] = [
                "id" => $cartId,
                'produkId' => $id,
                'dari' => $dari,
                'ke'=>$ke,
                'kode'=>$cekStock->produk->kode_produk,
                'gudangDari' => $cekStock->gudang->nama,
                'gudangKe' => $cekKe->nama,
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
        $cekStock = $this->gudangTransRepo->getWhere(['produk_id' => $request->produkId, 'gudang_id' => $request->dari]);
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
        session()->put('cart', $cart);

        $response = [
            'success' => true,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }


}
