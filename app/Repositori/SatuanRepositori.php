<?php

namespace App\Repositori;

use App\Models\Satuan;

class SatuanRepositori
{
    protected $satuan;
    public function __construct()
    {
        $this->satuan =new Satuan();
    }

    public function getAll()
    {
        return $this->satuan->get();
    }

    public function getId($id)
    {
        return $this->satuan->find($id);
    }

    public function getWhere($data){
        return $this->satuan->where($data);
    }

    public function getAllTrashed(){
        return $this->satuan->onlyTrashed()->get();
    }

    public function store($request)
    {
        $save = $this->satuan->updateOrCreate([
            'id' => $request['id']
        ], [

            'name' => $request['name'],
            'website_id'=>website()->id,
        ]);
        return $save->id;
    }
}
