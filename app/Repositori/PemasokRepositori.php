<?php

namespace App\Repositori;

use App\Models\Pemasok;

class PemasokRepositori
{
protected $pemasok;

public function __construct()
{
    $this->pemasok=new Pemasok();
}
    public function getAll()
    {
        return $this->pemasok->get();
    }

    public function getId($id){
        return $this->pemasok->find($id);
    }

    public function getWhere($data){
        return $this->pemasok->where($data);
    }

    public function getAllTrashed(){
        return $this->pemasok->onlyTrashed()->get();
    }

    public function store($request){
        $save=$this->pemasok->updateOrCreate([
            'id' => $request['id']
        ], [
            'kode' => $request['kode'],
            'nama' => $request['nama'],
            'perusahaan' => $request['perusahaan'],
            'alamat' => $request['alamat'],
            'email' => $request['email'],
            'telepon' => $request['telepon'],
            'aktif'=>$request['aktif'],
            'website_id'=>website()->id,
        ]);
        return $save->id;
    }

}
