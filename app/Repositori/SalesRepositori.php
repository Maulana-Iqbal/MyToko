<?php

namespace App\Repositori;

use App\Models\Sales;

class SalesRepositori
{
protected $sales;

public function __construct()
{
    $this->sales=new Sales();
}
    public function getAll()
    {
        return $this->sales->get();
    }

    public function getId($id){
        return $this->sales->find($id);
    }

    public function getWhere($data){
        return $this->sales->where($data);
    }

    public function getAllTrashed(){
        return $this->sales->onlyTrashed()->get();
    }

    public function store($request){
        $save=$this->sales->updateOrCreate([
            'id' => $request['id']
        ], [
            'kode' => $request['kode'],
            'nama' => $request['nama'],
            'jk' => $request['jk'],
            'alamat' => $request['alamat'],
            'email' => $request['email'],
            'telepon' => $request['telepon'],
            'aktif'=>$request['aktif'],
            'website_id'=>website()->id,
        ]);
        return $save->id;
    }

}
