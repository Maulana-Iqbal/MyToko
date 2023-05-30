<?php

namespace App\Repositori;

use App\Models\Kas;

class KasRepositori
{
    protected $kas;
    public function __construct()
    {
        $this->kas =new Kas();
    }

    public function getAll()
    {
        return $this->kas->get();
    }

    public function getId($id)
    {
        return $this->kas->find($id);
    }

    public function getWhere($data){
        return $this->kas->where($data);
    }

    public function getAllTrashed(){
        return $this->kas->onlyTrashed()->get();
    }

    public function store($request)
    {
        $save = $this->kas->create([
            'sumber' => $request['sumber'],
            'sumber_id' => $request['sumber_id'],
            'nomor' => $request['nomor'],
            'tgl' => $request['tgl'],
            'akun_id' => $request['akun_id'],
            'debit' => $request['debit'],
            'kredit' => $request['kredit'],
            'website_id'=>$request['website_id'],
        ]);
        return $save->id;
    }

    public function update($where,$data){
        $update=$this->getWhere($where)->update($data);
        return $update;
    }
}
