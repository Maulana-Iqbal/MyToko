<?php

namespace App\Repositori;

use App\Models\GudangTrans;

class GudangTransRepositori
{
    protected $gudangTrans;

    public function __construct()
    {
        $this->gudangTrans = new GudangTrans();
    }
    public function getAll()
    {
        $q = $this->gudangTrans->get();
        return $q;
    }

    public function getId($id)
    {
        return $this->gudangTrans->find($id);
    }

    public function getWhere($data)
    {
        $q = $this->gudangTrans->where($data);
        return $q;
    }

    public function getAllTrashed()
    {
        $q = $this->gudangTrans->onlyTrashed()->get();
        return $q;
    }

    public function store($request)
    {
            $save = $this->gudangTrans->create($request);
            return $save->id;
        
    }

    public function update($id,$data){
        return $this->getId($id)->update($data);
    }

    
}
