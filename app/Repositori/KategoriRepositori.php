<?php

namespace App\Repositori;

use App\Models\Kategori;

class KategoriRepositori
{
    protected $kategori;
    public function __construct()
    {
        $this->kategori=new Kategori();
    }

    public function getAll()
    {
        return $this->kategori->get();
    }

    public function orderByName(){
        return $this->kategori->orderBy('nama_kategori','ASC')->get();
    }

    public function getId($id){
        return $this->kategori->find($id);
    }

    public function getWhere($data){
        return $this->kategori->where($data);
    }

    public function getAllTrashed(){
        return $this->kategori->onlyTrashed()->get();
    }

    public function store($request){

        $save=$this->kategori->updateOrCreate([
            'id' => $request['id']
        ], [
            'induk_id' => $request['induk_id'],
            'nama_kategori' => $request['nama_kategori'],
            'slug' => $request['slug'],
            'icon' => $request['icon'],
            'website_id'=>website()->id,
        ]);
        return $save->id;
    }



}
