<?php

namespace App\Repositori;

use App\Models\Pelanggan;

class PelangganRepositori
{
    protected $pelanggan;
    public function __construct()
    {
        $this->pelanggan=new Pelanggan();
    }

    public function getAll()
    {
        return $this->pelanggan->get();
    }

    public function getId($id)
    {
        return $this->pelanggan->find($id);
    }

    public function getWhere($data)
    {
        return $this->pelanggan->where($data);
    }

    public function store($request)
    {
        $pelanggan = $this->pelanggan->create($request);

        return $pelanggan->id;
    }

    public function update($id,$data){
        return $this->getId($id)->update($data);
    }
}
