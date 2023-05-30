<?php

namespace App\Repositori;

use App\Models\KasBank;

class KasBankRepositori
{
    protected $kasBank;
    public function __construct()
    {
        $this->kasBank =new KasBank();
    }

    public function getAll()
    {
        return $this->kasBank->get();
    }

    public function getId($id)
    {
        return $this->kasBank->find($id);
    }

    public function getWhere($data){
        return $this->kasBank->where($data);
    }

    public function getAllTrashed(){
        return $this->kasBank->onlyTrashed()->get();
    }

    public function store($request)
    {
        $save = $this->kasBank->updateOrCreate([
            'id' => $request['id']
        ], [
            // 'tipe' => $request['tipe'],
            // 'induk' => $request['induk'],
            'akun_id' => $request['akun_id'],
            'website_id'=>website()->id,
        ]);
        return $save->id;
    }
}
