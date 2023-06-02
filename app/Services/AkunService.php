<?php

namespace App\Services;

use App\Http\Requests\AkunRequest;
use App\Repositori\AkunRepositori;

use DataTables;

class AkunService
{
    protected $akunRepo;

    public function __construct()
    {
        $this->akunRepo = new AkunRepositori();
    }

    public function getAll()
    {
        return $this->akunRepo->getAll();
    }

    public function getId($id)
    {
        return $this->akunRepo->getId($id);
    }

    public function getWhere($data)
    {
        return $this->akunRepo->getWhere($data);
    }

    public function getAllTrashed()
    {
        return $this->akunRepo->getAllTrashed();
    }

    public function store($request)
    {
        $id = $request->id_akun;
        $kode = $request->kode;
        $name = strtoupper($request->name);
        $data = [
            'id' => $id,
            'tipe' => $request->tipe,
            'induk' => $request->induk,
            'kategori_akun_id' => $request->kategori_akun_id,
            'kode' => $kode,
            'name' => $name,
            'website_id' => website()->id,
        ];

        return $this->akunRepo->store($data);
    }

    public function getDatatable($akun, $type)
    {
        try {
            return Datatables::of($akun)
                ->addIndexColumn()
                // ->addColumn('select', function ($row) {
                //     return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                // })
                ->addColumn('tipe', function ($row) {
                    $tipe = '';
                    if ($row->tipe <> null) {
                        $tipe = $row->tipe;
                    }
                    return $tipe;
                })
                ->addColumn('name', function ($row) {
                    $name = $row->name;
                    if ($row->tipe == 'detail' and !empty($row->induk)) {
                        $name = '<ul><li>' . $row->name . '</li></ul>';
                    }
                    return $name;
                })
                ->addColumn('kategori', function ($row) {
                    $kategori = '';
                    if ($row->kategori_akun <> null) {
                        $kategori = $row->kategori_akun->name;
                    }
                    return $kategori;
                })
                ->addColumn('saldo', function ($row) {
                    if ($row->tipe == 'detail') {
                        $debit = $row->kas->where('akun_id', $row->id)->sum('debit');
                        $kredit = $row->kas->where('akun_id', $row->id)->sum('kredit');
                        $saldo = (float)$debit - (float)$kredit;
                    } else {
                        return '';
                    }
                    return 'Rp. ' . number_format($saldo, 0, ',', '.');
                })
                ->addColumn('action', function ($row) use ($type) {
                    if ($type == 1) {
                        $btn = "";
                        // if($row->tipe=='header'){
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_akun="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editAkun"> <i class="mdi mdi-square-edit-outline"></i></a>';

                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_akun="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteAkun"> <i class="mdi mdi-delete"></i></a>';
                        // }
                        return $btn;
                    } else {
                        $btn = "";

                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_akun="' . enc($row->id) . '" data-original-title="Restore" class="btn btn-outline-primary restoreAkun"> Restore</a>';
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_akun="' . enc($row->id) . '" data-status="delete" data-original-title="Hapus" class="btn btn-outline-danger deleteAkun"> Permanent Delete</a>';

                        return $btn;
                    }
                })
                ->rawColumns(['tipe', 'name', 'kategori', 'saldo', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            return false;
        }
    }


    public function getDatatableAkun($akun)
    {
        try {
            return Datatables::of($akun)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $name = $row->name;
                    if ($row->tipe == 'detail' and !empty($row->induk)) {
                        $name = '<ul><li>' . $row->name . '</li></ul>';
                    }
                    return $name;
                })
                ->addColumn('kategori', function ($row) {
                    $kategori = '';
                    if ($row->kategori_akun <> null) {
                        $kategori = $row->kategori_akun->name;
                    }
                    return $kategori;
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    if ($row->tipe == 'detail') {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-kode="' . $row->kode . '" data-name="' . $row->name . '" class="btn btn-outline-primary btn-xs pilih"> Pilih</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['name', 'kategori', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            return false;
        }
    }
}
