<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Repositori\GudangTransRepositori;
use App\Repositori\ProdukRepositori;
use App\Repositori\StockRepositori;
use App\Services\StockService;
use Illuminate\Http\Request;

class CartOfflineController extends Controller
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
        return view('transaksi/tabelCart');
    }

    // public function add(Request $request)
    // {
    //     $id=$request->produkId;
    //     $stockId=$request->stockId;
    //     $cartId=$id.$stockId;
    //     $jumlah=$request->jumlah;
    //     $hargaJual=$request->harga_jual+$request->biaya;
    //     $produk = $this->produkRepo->getId($id);
    //     $cekStock=Stock::find($stockId);
    //     if ($cekStock->jumlah < $jumlah) {
    //         $response = [
    //             'success' => false,
    //             'message' => 'Stock tidak Cukup, Sisa Stock ' . $cekStock->jumlah,
    //         ];
    //         return response()->json($response, 200);
    //     }
    //     $cart = session()->get('cart', []);
    //     if (isset($cart[$cartId])) {
    //         // $cart[$cartId]['quantity']++;
    //         $response = [
    //             'success' => false,
    //             'message' => 'Gagal, Produk sudah ada di Daftar Order.',
    //         ];
    //         return response()->json($response, 200);
    //     } else {
    //         $cart[$cartId] = [
    //             "id" => $cartId,
    //             'produkId'=>$id,
    //             "stockId"=>$stockId,
    //             "name" => $produk->nama_produk,
    //             "quantity" => $jumlah,
    //             "price" => $cekStock->harga,
    //             "biaya" => $request->biaya,
    //             "harga_jual" => $hargaJual,
    //             "image" => $produk->gambar_utama
    //         ];
    //     }
    //     session()->put('cart', $cart);

    //     $this->removePpn();
    //     $this->removePph();

    //     $response = [
    //         'success' => true,
    //         'message' => 'Berhasil Ditambahkan.',
    //     ];
    //     return response()->json($response, 200);
    // }

    public function add(Request $request)
    {
        // session()->forget('cart');
        $id = $request->produkId;
        $gudang_id = $request->gudangId;
        $jumlah = 1;
        $biaya=0;
        
        if($request->jumlah){
            $jumlah=$request->jumlah;
        }
        if($request->biaya){
            $biaya=$request->biaya;
        }
        $cekStock = $this->gudangTransRepo->getWhere(['produk_id' => $id, 'gudang_id' => $gudang_id]);
        if ($cekStock->count() < 1) {
            $response = [
                'success' => false,
                'message' => 'Produk Tidak Ada Digudang',
            ];
            return response()->json($response, 200);
        }
        $cekStock = $cekStock->first();
        if ($cekStock->jumlah < $jumlah) {
            $response = [
                'success' => false,
                'message' => 'Stock tidak Cukup, Sisa Stock ' . $cekStock->jumlah,
            ];
            return response()->json($response, 200);
        }
        $cartId = $id . $gudang_id;
        $harga_final=$cekStock->produk->stock->harga_jual;
        if($request->harga_final){
            $harga_final=$request->harga_final;
        }
        $cart = session()->get('cart', []);
        if (isset($cart[$cartId])) {
            // $cart[$cartId]['quantity']++;
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
                'gudangName' => $cekStock->gudang->nama,
                "name" => '<b>' . $cekStock->produk->kode_produk . '</b><br>' . $cekStock->produk->nama_produk,
                "jumlah" => $jumlah,
                "harga_modal" => $cekStock->produk->stock->harga,
                "biaya" => $biaya,
                "harga_final" => $harga_final,
                "sub" => $harga_final+$biaya,
                "grosir" => 2,
                "image" => $cekStock->produk->gambar_utama
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


            $this->removePpn();
            $this->removePph();

            $response = [
                'success' => true,
                'message' => 'Berhasil Dihapus.',
            ];
            return response()->json($response, 200);
        }
    }

    public function updateCartHargaJual(Request $request)
    {
        if ($request->id && $request->harga_jual) {

            $cart = session()->get('cart');
            $harga = str_replace('.', '', $request->harga_jual);
            $cart[$request->id]["sub"] = $harga;
            session()->put('cart', $cart);
            $response = [
                'success' => true,
                'message' => 'Ubah Harga Jual Berhasil.',
            ];
            return response()->json($response, 200);
        }

        $response = [
            'success' => false,
            'message' => 'Gagal Diperbaharui.',
        ];
        return response()->json($response, 200);
    }


    public function updateCartBiaya(Request $request)
    {
        if ($request->id) {
            $id = $request->id;
            $harga = $request->harga;
            $biaya = str_replace('.', '', $request->biaya);

            $cart = session()->get('cart');
            
            $hargaJual = $biaya + $harga;
            $cart[$id]["biaya"] = $biaya;
            $cart[$id]["sub"] = $hargaJual;
            session()->put('cart', $cart);
            $response = [
                'success' => true,
                'message' => 'Berhasil Diperbaharui.',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal, Terjadi Kesalahan.',
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
        $cart[$request->id]["jumlah"] = $request->jumlah;
        $min = $cekStock->produk->min_order;
        $max = $cekStock->produk->max_order;
        $biaya = $cart[$request->id]["biaya"];
        $harga_final=$cekStock->produk->stock->harga_jual;
        $cart[$request->id]["harga_final"] = $harga_final;
        $cart[$request->id]["sub"] = $harga_final + $biaya;
        $cart[$request->id]["grosir"] = 2;
        if ($min > 0 and $max > 0 or $min>0 and $max==0) {
            if ($request->jumlah >= $min and $request->jumlah <= $max or $request->jumlah>=$min and $max==0) {
                $cart[$request->id]["grosir"] = 1;
                $cart[$request->id]["harga_final"] = $cekStock->produk->stock->harga_grosir;
                $cart[$request->id]["sub"] = $cekStock->produk->stock->harga_grosir + $biaya;
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

    public function biayaLain(Request $request)
    {
        $biayaLain = str_replace('.', '', $request->biayaLain);
        session()->put('biayaLain', $biayaLain);
        $response = [
            'success' => true,
            'message' => 'Biaya Lain - Lain Ditambahkan',
        ];
        return response()->json($response, 200);
    }

    public function biayaPengiriman(Request $request)
    {
        $biayaPengiriman = str_replace('.', '', $request->biayaPengiriman);
        session()->put('ongkir', $biayaPengiriman);
        $response = [
            'success' => true,
            'message' => 'Biaya Pengiriman Diperbaharui',
        ];
        return response()->json($response, 200);
    }

    public function enablePpn()
    {
        session()->put('ppn', 'true');
        $response = [
            'success' => true,
            'message' => 'PPN ADD',
        ];
        return response()->json($response, 200);
    }

    public function removePpn()
    {
        session()->forget('ppn');
        $response = [
            'success' => true,
            'message' => 'PPN Remove.',
        ];
        return response()->json($response, 200);
    }

    public function enablePph()
    {
        session()->put('pph', 'true');
        $response = [
            'success' => true,
            'message' => 'PPH ADD',
        ];
        return response()->json($response, 200);
    }

    public function removePph()
    {
        session()->forget('pph');
        $response = [
            'success' => true,
            'message' => 'PPH Remove.',
        ];
        return response()->json($response, 200);
    }
}
