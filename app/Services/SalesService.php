<?php

namespace App\Services;

use App\Repositori\SalesRepositori;

class SalesService
{
    protected $salesRepo;

    public function __construct()
    {
        $this->salesRepo = new SalesRepositori();
    }

    public function getAll()
    {
        return $this->salesRepo->getAll();
    }

    public function getId($id)
    {
        return $this->salesRepo->getId($id);
    }

    public function getWhere($data)
    {
        return $this->salesRepo->getWhere($data);
    }

    public function getAllTrashed()
    {
        return $this->salesRepo->getAllTrashed();
    }

    public function store($request)
    {
        $id = $request->id_sales;
        $request->validate(
            [
                'kode' => 'required',
                'nama' => 'required',
                'alamat' => 'required',
                'jk' => 'required',
                'aktif' => 'required',
                'email' => 'required',
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
            'jk' => $request->jk,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'aktif' => $request->aktif,
            'website_id'=>website()->id,
        ];

        return $this->salesRepo->store($data);
    }
}
