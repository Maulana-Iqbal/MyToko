<?php

namespace App\Repositori;

use App\Models\Rekening;

class RekeningRepositori
{
    protected $rekening;
    public function __construct()
    {
        $this->rekening =new Rekening();
    }

    public function getAll()
    {
        return $this->rekening->get();
    }

    public function getId($id)
    {
        return $this->rekening->find($id);
    }

    public function getWhere($data){
        return $this->rekening->where($data);
    }

    public function getAllTrashed(){
        return $this->rekening->onlyTrashed()->get();
    }

    public function store($request)
    {
        $save = $this->rekening->updateOrCreate([
            'id' => $request['id']
        ], [
            'akun_id'=>$request['akun_id'],
            'nama_bank' => $request['nama_bank'],
            'nama_rek' => $request['nama_rek'],
            'no_rek' => $request['no_rek'],
            'jenis_rek' => $request['jenis_rek'],
            'user_id' => auth()->user()->id,
            'website_id' => website()->id,
            'isActive' => $request['isActive'],
        ]);
        return $save->id;
    }
}
