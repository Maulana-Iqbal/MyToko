<?php

namespace App\Repositori;

use App\Models\Transaksi;
class TransaksiRepositori
{
    protected $transaksi;
    public function __construct()
    {
        $this->transaksi=new Transaksi();
    }

    public function getAll()
    {
        return $this->transaksi->get();
    }

    public function getId($id){
        return $this->transaksi->find($id);
    }

    public function getWhere($data){
        return $this->transaksi->where($data);
    }

    public function store($request){
        return $this->transaksi->create($request);
    }

    public function update($id,$data){
      return $this->getId($id)->update($data);
    }

}
