<?php

namespace App\Repositori;

use App\Models\StockTransfer;

class StockTransferRepositori
{
    protected $stockTransfer;

    public function __construct()
    {
        $this->stockTransfer = new StockTransfer();
    }

    public function getId($id)
    {
        return $this->stockTransfer->find($id);
    }

    public function getAll()
    {
        return $this->stockTransfer->get();
    }

    public function getWhere($data)
    {
        return $this->stockTransfer->where($data);
    }

    public function store($request)
    {
        $query = $this->stockTransfer->create($request);
        return $query->id;
    }

    public function update($id, $request)
    {
        $query = $this->getId($id)->update($request);
        return $query;
    }


}
