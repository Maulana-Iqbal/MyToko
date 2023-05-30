<?php

namespace App\Services;

use App\Http\Requests\PekerjaanRequest;
use App\Repositori\PekerjaanRepositori;

use DataTables;

class PekerjaanService
{
    protected $pekerjaanRepo;

    public function __construct()
    {
        $this->pekerjaanRepo = new PekerjaanRepositori();
    }

    public function store($request)
    {
        $id = $request->id_pekerjaan;
        $name = strtoupper($request->name);
        $biaya = str_replace('.', '', $request->biaya);
        $data = [
            'id' => $id,
            'name' => $name,
            'biaya' => $biaya,
            'website_id'=>website()->id,
        ];

        return $this->pekerjaanRepo->store($data);
    }

    public function getDatatable($pekerjaan, $type)
    {
        try {
            return Datatables::of($pekerjaan)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                })->addColumn('biaya', function ($row) {
                    $biaya = (float)$row->biaya;
                    $biaya = "Rp " . number_format($biaya, 0, ',', '.');
                    return $biaya;
                })
                ->addColumn('action', function ($row) use ($type) {
                    if ($type == 1) {
                        $btn = "";
                        if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pekerjaan="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editPekerjaan"> <i class="mdi mdi-square-edit-outline"></i></a>';

                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pekerjaan="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deletePekerjaan"> <i class="mdi mdi-delete"></i></a>';
                        }

                        return $btn;
                    } else {
                        $btn = "";
                        if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {

                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pekerjaan="' . enc($row->id) . '" data-original-title="Restore" class="btn btn-outline-primary restorePekerjaan"> Restore</a>';
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pekerjaan="' . enc($row->id) . '" data-status="delete" data-original-title="Hapus" class="btn btn-outline-danger deletePekerjaan"> Permanent Delete</a>';
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
