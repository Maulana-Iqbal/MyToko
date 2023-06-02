<?php

namespace App\Services;

use App\Repositori\PemasokRepositori;

class PemasokService
{
    protected $pemasokRepo;

    public function __construct()
    {
        $this->pemasokRepo = new PemasokRepositori();
    }

    public function getAll()
    {
        return $this->pemasokRepo->getAll();
    }

    public function getId($id)
    {
        return $this->pemasokRepo->getId($id);
    }

    public function getWhere($data)
    {
        return $this->pemasokRepo->getWhere($data);
    }

    public function getAllTrashed()
    {
        return $this->pemasokRepo->getAllTrashed();
    }

    public function store($request)
    {
        $id = $request->id_pemasok;
        $request->validate(
            [
                'kode' => 'required',
                'nama' => 'required',
                'alamat' => 'required',
                'aktif' => 'required',
                'email' => 'required',
                'perusahaan' => 'required',
                'telepon' => 'required',
            ],
            [
                'required' => 'Data Belum Lengkap',
            ]
        );


        $data = [
            'id' => $id,
            'kode' => $request->kode,
            'nama' => $request->nama,
            'perusahaan' => $request->perusahaan,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'aktif' => $request->aktif,
            'website_id'=>website()->id,
        ];

        return $this->pemasokRepo->store($data);
    }
}
