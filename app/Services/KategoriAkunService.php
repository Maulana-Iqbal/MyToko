<?php

namespace App\Services;

use App\Http\Requests\KategoriAkunRequest;
use App\Repositori\KategoriAkunRepositori;

use DataTables;

class KategoriAkunService
{
    protected $kategoriAkunRepo;

    public function __construct()
    {
        $this->kategoriAkunRepo = new KategoriAkunRepositori();
    }

    public function getAll()
    {
        return $this->kategoriAkunRepo->getAll();
    }

    public function getId($id)
    {
        return $this->kategoriAkunRepo->getId($id);
    }

    public function getWhere($data)
    {
        return $this->kategoriAkunRepo->getWhere($data);
    }

    public function getAllTrashed()
    {
        return $this->kategoriAkunRepo->getAllTrashed();
    }

    public function store($request)
    {
        $id = $request->id_kategoriAkun;
        $kode=$request->kode;
        $name = strtoupper($request->name);
        $data = [
            'id' => $id,
            'kode' => $kode,
            'name' => $name,
            'website_id'=>website()->id,
        ];

        return $this->kategoriAkunRepo->store($data);
    }

    public function getDatatable($kategoriAkun, $type)
    {
        try {
            return Datatables::of($kategoriAkun)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                })

                ->addColumn('action', function ($row) use ($type) {
                    // if ($type == 1) {
                    //     $btn = "";
                    //     if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {
                    //         $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editKategoriAkun"> <i class="mdi mdi-square-edit-outline"></i></a>';

                    //         $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteKategoriAkun"> <i class="mdi mdi-delete"></i></a>';
                    //     }

                    //     return $btn;
                    // } else {
                    //     $btn = "";
                    //     if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {

                    //         $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . enc($row->id) . '" data-original-title="Restore" class="btn btn-outline-primary restoreKategoriAkun"> Restore</a>';
                    //         $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . enc($row->id) . '" data-status="delete" data-original-title="Hapus" class="btn btn-outline-danger deleteKategoriAkun"> Permanent Delete</a>';
                    //     }

                    //     return $btn;
                    // }
                })
                ->rawColumns(['select', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            return false;
        }
    }
}
