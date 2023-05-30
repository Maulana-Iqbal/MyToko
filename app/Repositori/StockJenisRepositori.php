<?php

namespace App\Repositori;

use App\Models\StockJenis;

class StockJenisRepositori
{
    protected $stockJenis;

    public function __construct()
    {
        $this->stockJenis = new StockJenis();
    }

    public function getId($id)
    {
        return $this->stockJenis->find($id);
    }

    public function getAll()
    {
        return $this->stockJenis->get();
    }

    public function getWhere($data)
    {
        return $this->stockJenis->where($data);
    }

    public function store($request)
    {
        $query = $this->stockJenis->create($request);
        return $query->id;
    }

    public function update($id, $request)
    {
        $query = $this->getId($id)->update($request);
        return $query;
    }

    public function delete($id)
    {
        $query = $this->stockJenis->delete($id);
        return true;
    }

    public function deleteWhere($where)
    {
        return $this->stockJenis->where($where)->withTrashed()->forceDelete();
    }

}
