<?php

namespace App\Repositori;

use App\Models\StockOrder;

class StockOrderRepositori
{
    protected $stockOrder;

    public function __construct()
    {
        $this->stockOrder = new StockOrder();
    }

    public function getId($id)
    {
        return $this->stockOrder->find($id);
    }

    public function getAll()
    {
        return $this->stockOrder->get();
    }

    public function getWhere($data)
    {
        return $this->stockOrder->where($data);
    }

    public function store($request)
    {
        $query = $this->stockOrder->create($request);
        return $query->id;
    }

    public function update($id, $request)
    {
        $query = $this->getId($id)->update($request);
        return $query;
    }


}
