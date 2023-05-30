<?php

namespace App\Repositori;

use App\Models\Gudang;

class GudangRepositori
{
    protected $gudang;

    public function __construct()
    {
        $this->gudang = new Gudang();
    }
    public function getAll()
    {
        $q = $this->gudang->get();
        return $q;
    }

    public function getId($id)
    {
        return $this->gudang->find($id);
    }

    public function getWhere($data)
    {
        $q = $this->gudang->where($data);
        return $q;
    }

    public function getAllTrashed()
    {
        $q = $this->gudang->onlyTrashed()->get();
        return $q;
    }

    public function store($request)
    {
        $save = $this->gudang->updateOrCreate([
            'id' => $request['id']
        ], [
            'kode' => $request['kode'],
            'user_id' => $request['user'],
            'nama' => $request['nama'],
            'alamat' => $request['alamat'],
            'jenis' => $request['jenis'],
            'status' => $request['status'],
            'website_id' => website()->id,
        ]);
        return $save->id;
    }
}
