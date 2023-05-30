<?php

namespace App\Services;

use App\Repositori\GudangRepositori;

class GudangService
{
    protected $gudangRepo;

    public function __construct()
    {
        $this->gudangRepo = new GudangRepositori();
    }

    public function getAll()
    {
        return $this->gudangRepo->getAll();
    }

    public function getId($id)
    {
        return $this->gudangRepo->getId($id);
    }

    public function getWhere($data)
    {
        return $this->gudangRepo->getWhere($data);
    }

    public function getAllTrashed()
    {
        return $this->gudangRepo->getAllTrashed();
    }

    public function store($request)
    {
        $id = $request->id_gudang;
        $request->validate(
            [
                'kode' => 'required|unique:gudang,kode,'.$id,
                'nama' => 'required',
                'user' => 'required',
                'jenis' => 'required',
                'alamat' => 'required',
                'aktif' => 'required',
            ],
            [
                'unique' => 'Gudang Sudah Ada...!!!',
                'required' => 'Data Belum Lengkap',
            ]
        );


        $data = [
            'id' => $id,
            'user' => $request->user,
            'kode' => $request->kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'jenis' => $request->jenis,
            'status' => $request->aktif,
            'website_id'=>website()->id,
        ];

        return $this->gudangRepo->store($data);
    }
}
