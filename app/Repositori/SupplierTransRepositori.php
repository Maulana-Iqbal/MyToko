<?php

namespace App\Repositori;

use App\Models\SupplierTrans;

class SupplierTransRepositori
{
    protected $supplierTrans;

    public function __construct()
    {
        $this->supplierTrans = new SupplierTrans();
    }
    public function getAll()
    {
        $q = $this->supplierTrans->get();
        return $q;
    }

    public function getId($id)
    {
        return $this->supplierTrans->find($id);
    }

    public function getWhere($data)
    {
        $q = $this->supplierTrans->where($data);
        return $q;
    }

    public function getAllTrashed()
    {
        $q = $this->supplierTrans->onlyTrashed()->get();
        return $q;
    }

    public function store($request)
    {
            $save = $this->supplierTrans->create($request);
            return $save->id;
        
    }

    public function update($id,$data){
        return $this->getId($id)->update($data);
    }

    
}
