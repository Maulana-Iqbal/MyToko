<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Repositori\PermissionRepositori;
use App\Services\PermissionService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    protected $permissionService;
    protected $permissionRepo;
    protected $responseService;
    public function __construct()
    {
        $this->permissionService = new PermissionService();
        $this->permissionRepo=new PermissionRepositori();
        $this->responseService = new ResponseService();
       
        $this->middleware('permission:permission|permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index','show']]);
        $this->middleware('permission:permission|permission-create', ['only' => ['create','store']]);
        $this->middleware('permission:permission|permission-edit', ['only' => ['edit','store']]);
        $this->middleware('permission:permission|permission-delete', ['only' => ['delete','bulkDelete']]);
    }

    
    public function index(Request $request)
    {
        // dd(auth()->user()->hasRole(['SUPERADMIN','SHOW ALL']));
        // dd(auth()->user()->hasPermissionTo('permission-list'));
        $permission=$this->permissionRepo->getWhere(null);
        if (auth()->user()->hasRole('SUPERADMIN')) {
            
        } else {
            $permission->whereNotIn('id',['1','3']);
        }
       $permission->latest()->get();
        if ($request->ajax()) {
            return $this->permissionService->getDatatable($permission,1);
        }
        $permission = $this->permissionService->orderByName();
        return view('permission/permission', compact('permission'));
    }


   

    public function store(PermissionRequest $request)
    {
        $this->permissionService->store($request);
        return $this->responseService->response(true);
    }

    public function edit($id)
    {
        $id = dec($id);
        $permission = $this->permissionRepo->getId($id);
        return response()->json($permission);
    }


   

    public function delete($id)
    {
        $id = dec($id);
      
            $this->permissionRepo->getId($id)->delete();
            $response = $this->responseService->response(true);
      

        return $response;
    }



    public function bulkDelete(Request $request)
    {
        $result = DB::transaction(function () use ($request) {

            $id = $request->id;
            $jml_pilih = count($id);
            $dihapus = 0;
            $response = [];
            foreach ($request->id as $del) {
              
                    $this->permissionRepo->getId($del->id)->delete();
                    $dihapus = $dihapus + 1;
               
            }

            if ($dihapus == $jml_pilih) {
                $response = $this->responseService->response(true);
            } else {
                $response = $this->responseService->response(true, 'Beberapa Permission tidak dapat dihapus.');
            }

            return $response;
        });
        // return response

        return $result;
    }

    public function select(Request $request){
        $search = $request->search;
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q=null;
            } else {
                $q=['website_id'=>website()->id];
            }
        if($search == ''){
           $data = $this->permissionRepo->getWhere($q)->orderBy('name','asc')->select('id','name')->get();
        }else{
           $data = $this->permissionRepo->getWhere($q)->orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->get();
        }

        $response = array();
        foreach($data as $data){
           $response[] = array(
                "id"=>$data->id,
                "text"=>$data->name
           );
        }
        return response()->json($response);
     }

}
