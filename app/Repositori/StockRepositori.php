<?php

namespace App\Repositori;

use App\Models\Stock;

class StockRepositori
{
    protected $stock;

    public function __construct()
    {
        $this->stock = new Stock();
    }

    public function getId($id)
    {
        return $this->stock->find($id);
    }

    public function getAll()
    {
        return $this->stock->get();
    }

    public function getWhere($data)
    {
        return $this->stock->where($data);
    }

    public function store($request)
    {
        $query = $this->stock->create($request);
        return $query->id;
    }

    public function update($id, $request)
    {
        $query = $this->getId($id)->update($request);
        return $query;
    }

    public function terbaru(){
        return $this->stock->with(['produk'=>function ($q){ $q->orderBy('created_at','desc');}])->groupBy('produk_id')->get();
    }

}
