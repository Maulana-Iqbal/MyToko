<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Stock;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cartList()
    {
        // $provinces = OngkirProvince::pluck('name', 'province_id');
        $this->tax();
        $cartItems = \Cart::getContent();
        $subTotal = \Cart::getSubTotal();
        $condition = \Cart::getCondition('ppn');
        $ppn = $condition->parsedRawValue;
        return view('eshop.cart', compact('cartItems', 'ppn'));
    }

    public function checkout()
    {
        if (\Cart::isEmpty()) {
            \Cart::clear();
            return redirect('/');
        }
        $this->tax();
        $cartItems = \Cart::getContent();
        $subTotal = \Cart::getSubTotal();
        $condition = \Cart::getCondition('ppn');
        $ppn = $condition->parsedRawValue;


        $totalBerat = 0;
        foreach ($cartItems as $index => $item) {
            $subTotalBerat = $item->attributes[0]['berat'] * $item->quantity;
            $totalBerat += $subTotalBerat;
        }
        return view('eshop.checkout', compact('cartItems', 'ppn', 'totalBerat'));
    }


    public function addToCart($id)
    {
        $produk = Produk::find($id);
        if ($produk->stock->jumlah > 0) {
            \Cart::add([
                'id' => $produk->id,
                'name' => $produk->nama_produk,
                'price' => $produk->stock->harga_jual,
                'quantity' => 1,
                'attributes' => array([
                    'harga_modal' => $produk->stock->harga,
                    'image' => $produk->gambar_utama,
                    'berat' => $produk->berat,
                    'keterangan' => $produk->keterangan,
                    'satuan' => $produk->satuan->name,
                    'url' =>url($produk->website->username.'/produk/detail'.'/'.$produk->slug),
                ])

            ]);

            session()->flash('success', 'Produk telah ditambahkan ke Keranjang !');
            return redirect()->route('cart.list');
        } else {
            session()->flash('alert', 'Stock tidak tersedia !');
            return redirect()->back();
        }
    }

    public function updateCart(Request $request)
    {
        $produk = Produk::find($request->id);
        if ($produk->stock->jumlah >= $request->quantity) {
            \Cart::update(
                $request->id,
                [
                    'quantity' => [
                        'relative' => false,
                        'value' => $request->quantity
                    ],
                ]
            );
            session()->flash('success', 'Item telah diperbaharui !');
        } else {
            session()->flash('alert', 'Stock tidak mencukupi, sisa stock ' . $produk->stock->jumlah . ' ' . $produk->satuan->name);
        }
        return redirect()->route('cart.list');
    }

    public function removeCart(Request $request)
    {
        \Cart::remove($request->id);
        session()->flash('success', 'Item telah dihapus !');

        return redirect()->route('cart.list');
    }

    public function clearAllCart()
    {
        \Cart::clear();

        session()->flash('success', 'Keranjang belanja telah dikosongkan !');

        return redirect()->route('cart.list');
    }


    public function tax()
    {
        $condition = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'ppn',
            'type' => 'tax',
            'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => website()->trx_ppn . '%',
            'attributes' => array( // attributes field is optional
                'description' => 'Value added tax',
                'more_data' => 'more data here'
            )
        ));

        \Cart::condition($condition);
    }



    public function addOngkir(Request $request)
    {
        $condition = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'biaya',
            'type' => 'shipping',
            'target' => '', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => $request->ongkir,
            'order' => 1
        ));
        $condition = \Cart::condition($condition);
        if ($condition) {
            $response = [
                'success' => true,
                'message' => 'Berhasil Ditambahkan.',
                'data'=>['ongkir'=>uang($request->ongkir)],
            ];
            return response()->json($response, 200);
        }
    }
}
