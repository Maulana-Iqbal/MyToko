<?php

namespace App\Repositori;

use App\Models\KategoriAkun;

class KategoriAkunRepositori
{
    protected $kategoriAkun;
    public function __construct()
    {
        $this->kategoriAkun =new KategoriAkun();
    }

    public function getAll()
    {
        return $this->kategoriAkun->get();
    }

    public function getId($id)
    {
        return $this->kategoriAkun->find($id);
    }

    public function getWhere($data){
        return $this->kategoriAkun->where($data);
    }

    public function getAllTrashed(){
        return $this->kategoriAkun->onlyTrashed()->get();
    }

    public function store($request)
    {
        $save = $this->kategoriAkun->updateOrCreate([
            'id' => $request['id']
        ], [
            'kode' => $request['kode'],
            'name' => $request['name'],
            'website_id'=>website()->id,
        ]);
        return $save->id;
    }
}
