<?php

namespace App\Repositori;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
class OrderRepositori
{
    protected $order;

    public function __construct()
    {
        $this->order = new Order();
    }

    public function getId($id)
    {
        return $this->order->find($id);
    }

    public function getAll()
    {
        return $this->order->get();
    }

    public function getWhere($data)
    {
        return $this->order->where($data);
    }

    public function store($request)
    {
        $query = $this->order->create($request);
        return $query->id;
    }

    public function update($id, $request)
    {
        $query = $this->getId($id)->update($request);
        return $query;
    }

    public function terlaris(){
        // $query=DB::select('select order.stock_id,sum(order.jumlah) as jumlah,produk.nama_produk,produk.slug,satuan.name as satuan from `order` join stock on stock.id=order.stock_id join produk on produk.id=stock.produk_id join satuan on satuan.id=stock.satuan_id group by order.stock_id');
        $data=$this->order->selectRaw('*,sum(jumlah) as terjual')->groupBy('stock_id')->orderBy('terjual','desc')->get();
        return $data;
    }

}
