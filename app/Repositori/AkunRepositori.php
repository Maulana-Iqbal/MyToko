<?php

namespace App\Repositori;

use App\Models\Akun;

class AkunRepositori
{
    protected $akun;
    public function __construct()
    {
        $this->akun =new Akun();
    }

    public function getAll()
    {
        return $this->akun->get();
    }

    public function getId($id)
    {
        return $this->akun->find($id);
    }

    public function getWhere($data){
        return $this->akun->where($data);
    }

    public function getAllTrashed(){
        return $this->akun->onlyTrashed()->get();
    }

    public function store($request)
    {
        $save = $this->akun->updateOrCreate([
            'id' => $request['id']
        ], [
            'tipe' => $request['tipe'],
            'induk' => $request['induk'],
            'kategori_akun_id' => $request['kategori_akun_id'],
            'kode' => $request['kode'],
            'name' => $request['name'],
            'website_id'=>website()->id,
        ]);
        return $save->id;
    }
}
