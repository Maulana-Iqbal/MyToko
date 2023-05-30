<?php

namespace App\Repositori;

use App\Models\HistoryStock;

class HistoryStockRepositori
{
    protected $historyStock;

    public function __construct()
    {
        $this->historyStock = new HistoryStock();
    }

    public function getId($id)
    {
        return $this->historyStock->find($id);
    }

    public function getAll()
    {
        return $this->historyStock->get();
    }

    public function getWhere($data)
    {
        return $this->historyStock->where($data);
    }

    public function store($request)
    {
        $query = $this->historyStock->create($request);
        return $query->id;
    }

    public function update($id, $request)
    {
        $query = $this->getId($id)->update($request);
        return $query;
    }



}
