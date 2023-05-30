<?php

namespace App\Services;

use App\Http\Requests\RekeningRequest;
use App\Repositori\RekeningRepositori;

use DataTables;

class RekeningService
{
    protected $rekeningRepo;

    public function __construct()
    {
        $this->rekeningRepo = new RekeningRepositori();
    }

    public function getAll()
    {
        return $this->rekeningRepo->getAll();
    }

    public function getId($id)
    {
        return $this->rekeningRepo->getId($id);
    }

    public function getWhere($data)
    {
        return $this->rekeningRepo->getWhere($data);
    }

    public function getAllTrashed()
    {
        return $this->rekeningRepo->getAllTrashed();
    }

    public function store($request)
    {
        $id = $request->id_rekening;
        $akun=$request->akun;
        $nama_bank = $request->nama_bank;
        $nama_rek = $request->nama_rek;
        $no_rek = $request->no_rek;
        $jenis_rek = $request->jenis_rek;
        $isActive=$request->isActive;
        $data = [
            'id' => $id,
            'akun_id'=>$akun,
            'nama_bank' => $nama_bank,
            'nama_rek' => $nama_rek,
            'no_rek' => $no_rek,
            'jenis_rek' => $jenis_rek,
            'isActive'=>$isActive,
        ];

        return $this->rekeningRepo->store($data);
    }

    public function getDatatable($rekening, $type)
    {
        try {
            return Datatables::of($rekening)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                })
                ->addColumn('jenis_rek', function ($row) {
                    if($row->jenis_rek==2){
                        $jenis_rek='Rekening Bank';
                    }else{
                        $jenis_rek='Virtual Akun';
                    }
                    return $jenis_rek;
                })
                ->addColumn('status', function ($row) {
                    if($row->isActive==1){
                        $status='<span class="text-success">Aktif</span>';
                    }else{
                        $status='Tidak Aktif';
                    }
                    return $status;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y',strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) use ($type) {
                    if ($type == 1) {
                        $btn = "";
                        if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_rekening="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editRekening"> <i class="mdi mdi-square-edit-outline"></i></a>';

                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_rekening="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteRekening"> <i class="mdi mdi-delete"></i></a>';
                        }

                        return $btn;
                    } else {
                        $btn = "";
                        if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {

                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_rekening="' . enc($row->id) . '" data-original-title="Restore" class="btn btn-outline-primary restoreRekening"> Restore</a>';
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_rekening="' . enc($row->id) . '" data-status="delete" data-original-title="Hapus" class="btn btn-outline-danger deleteRekening"> Permanent Delete</a>';
                        }

                        return $btn;
                    }
                })
                ->rawColumns(['select', 'jenis_rek', 'status', 'created_at', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            return false;
        }
    }
}
