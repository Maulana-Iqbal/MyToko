<?php
    
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission as ModelsPermission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // $user=User::find(1);
        // $role = Role::where(['name' => 'SUPERADMIN'])->first();
     
        // $permissions = Permission::pluck('id','id')->all();
   
        // $role->syncPermissions($permissions);
     
        // $user->assignRole([$role->id]);
       
         $this->middleware('permission:role|role-list|role-create|role-edit|role-delete', ['only' => ['index']]);
         $this->middleware('permission:role|role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role|role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role|role-delete', ['only' => ['destroy']]);
       
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles=Role::query();
        if(auth()->user()->hasRole('SUPERADMIN')){
        }else{
        $roles->where('website_id',website()->id);
        }
        $roles=$roles->orderBy('id','DESC')->paginate(5);
        return view('roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission=ModelsPermission::orderBy('name');
        if(auth()->user()->hasRole('SUPERADMIN')){
        }else{
        $permission->whereNotIn('id',[1,3]);
        }
        $permission = $permission->get();
        return view('roles.create',compact('permission'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $cekRole=Role::where(['alias'=>$request->input('name'),'website_id'=>website()->id])->count();
        if($cekRole>0){
            return redirect()->route('roles.index')
                        ->with('failed','Role Sudah Ada');
        }
        $role = Role::create(['website_id'=>website()->id,'name' => $request->input('name').'-'.website()->id,'alias'=>$request->input('name')]);
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
                        ->with('success','Berhasi Disimpan');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $permission=ModelsPermission::orderBy('name');
        if(auth()->user()->hasRole('SUPERADMIN')){
        }else{
        $permission->whereNotIn('id',[1,3]);
        }
        $permission = $permission->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
    
        return view('roles.show',compact('role','rolePermissions','permission'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission=ModelsPermission::orderBy('name');
        if(auth()->user()->hasRole('SUPERADMIN')){
        }else{
        $permission->whereNotIn('id',[1,3]);
        }
        $permission = $permission->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return view('roles.edit',compact('role','permission','rolePermissions'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($id);

        if(auth()->user()->hasRole('SUPERADMIN')){
            $where=['alias'=>$request->input('name'),'website_id'=>$role->id];
        }else{
        $where=['alias'=>$request->input('name'),'website_id'=>website()->id];
        }
        $cekRole=Role::where($where)->where('id','!=',$id)->count();
        if($cekRole>0){
            return redirect()->route('roles.index')
                        ->with('failed','Role Sudah Ada');
        }
       
        $role->alias=$request->input('name');
        $role->save();
    
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
                        ->with('success','Update Role Berhasil');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','Hapus Role Berhasil');
    }
}