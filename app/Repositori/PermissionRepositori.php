<?php

namespace App\Repositori;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class PermissionRepositori
{
    protected $permission;
    public function __construct()
    {
        $this->permission = new Permission();
    }

    public function getAll()
    {
        return $this->permission->get();
    }

    public function orderByName()
    {
        return $this->permission->orderBy('name', 'ASC')->get();
    }

    public function getId($id)
    {
        return $this->permission->find($id);
    }

    public function getWhere($data)
    {
        return $this->permission->where($data);
    }



    public function store($request)
    {

        $save = $this->permission->updateOrCreate([
            'id' => $request['id']
        ], [
            'induk_id' => $request['induk_id'],
            'name' => $request['name'],
            'guard_name' => Auth::getDefaultDriver(),
            'keterangan'=>$request['keterangan'],
        ]);
        
        return $save->id;
    }
}
