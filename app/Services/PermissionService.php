<?php

namespace App\Services;

use App\Repositori\PermissionRepositori;
use DataTables;

class PermissionService
{
    protected $permissionRepo;

    public function __construct()
    {
        $this->permissionRepo = new PermissionRepositori();
    }

    public function getAll()
    {
        return $this->permissionRepo->getAll();
    }

    public function orderByName()
    {
        return $this->permissionRepo->orderByName();
    }

    public function getId($id)
    {
        return $this->permissionRepo->getId($id);
    }

    public function getWhere($data)
    {
        return $this->permissionRepo->getWhere($data);
    }

    public function getAllTrashed()
    {
        return $this->permissionRepo->getAllTrashed();
    }

    public function store($request)
    {
        $id = $request->id_permission;

        $data = [
            'id' => $id,
            'induk_id' => (int)$request->induk,
            'keterangan'=>$request->keterangan,
            'name' => $request->name,
        ];

        return $this->permissionRepo->store($data);
    }



    public function getDatatable($permission, $type)
    {
        try {
            return Datatables::of($permission)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                })
                ->addColumn('group', function ($row) {
                    $group='';
                    if(!empty($row->group)){
                        $group=$row->group->name;
                    }
                    return $group;
                })
                

                ->addColumn('action', function ($row) use ($type) {

                    $btn = "";
                    if (auth()->user()->hasPermissionTo('permission') or auth()->user()->hasPermissionTo('permission-edit')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_permission="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editPermission"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (auth()->user()->hasPermissionTo('permission') or auth()->user()->hasPermissionTo('permission-delete')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_permission="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deletePermission"> <i class="mdi mdi-delete"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['select', 'name', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            return false;
        }
    }
}
