<?php

namespace App\Services;

use App\Repositori\SatuanRepositori;

use DataTables;

class SatuanService
{
    protected $satuanRepo;

    public function __construct()
    {
        $this->satuanRepo = new SatuanRepositori();
    }

    public function store($request)
    {
        $id = $request->id_satuan;
        $name = strtoupper($request->name);

        $cekSatuan = $this->satuanRepo->getWhere(['name' => $request->name, 'website_id' => website()->id]);
        if ($id) {
            $cekSatuan->where('id', '<>', $id);
        }
        $cekSatuan = $cekSatuan->count();
        if ($cekSatuan > 0) {
            $response = [
                'success' => false,
                'message' => 'Satuan Sudah Digunakan',
                'data' => []
            ];
            return $response;
        }


        $save = $this->satuanRepo->store([
            'id' => $id,
            'name' => $name,
            'website_id' => website()->id,
        ]);
        if ($save) {
            $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan',
                'data'=>$save
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal Disimpan',
                'data' => []
            ];
        }

        return $response;
    }

    public function getDatatable($satuan, $type)
    {
        try {
            return Datatables::of($satuan)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                })
                ->addColumn('action', function ($row) use ($type) {
                    if ($type == 1) {
                        $btn = "";
                        if (auth()->user()->hasPermissionTo('satuan') or auth()->user()->hasPermissionTo('satuan-edit')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_satuan="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editSatuan"> <i class="mdi mdi-square-edit-outline"></i></a>';
                        }
                        if (auth()->user()->hasPermissionTo('satuan') or auth()->user()->hasPermissionTo('satuan-trash')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_satuan="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteSatuan"> <i class="mdi mdi-delete"></i></a>';
                        }

                        return $btn;
                    } else {
                        $btn = "";
                        if (auth()->user()->hasPermissionTo('satuan') or auth()->user()->hasPermissionTo('satuan-trash')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_satuan="' . enc($row->id) . '" data-original-title="Restore" class="btn btn-outline-primary restoreSatuan"> Restore</a>';
                        }
                        if (auth()->user()->hasPermissionTo('satuan') or auth()->user()->hasPermissionTo('satuan-delete')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_satuan="' . enc($row->id) . '" data-status="delete" data-original-title="Hapus" class="btn btn-outline-danger deleteSatuan"> Permanent Delete</a>';
                        }

                        return $btn;
                    }
                })
                ->rawColumns(['select', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            return false;
        }
    }
}
