<?php

namespace App\Repositori;

use App\Models\Galeri;

class GaleriRepositori
{
    protected $galeri;

    public function __construct()
    {
        $this->galeri = new Galeri();
    }

    public function getId($id)
    {
        return $this->galeri->find($id);
    }

    public function getAll()
    {
        return $this->galeri->get();
    }

    public function getWhere($data)
    {
        return $this->galeri->where($data);
    }

    public function store($request)
    {
        $query = $this->galeri->create($request);
        return $query->id;
    }

    public function update($id, $request)
    {
        $query = $this->getId($id)->update($request);
        return $query;
    }



}
