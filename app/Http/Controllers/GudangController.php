<?php

namespace App\Http\Controllers;

use App\Repositori\GudangRepositori;
use App\Services\GudangService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    protected $gudangService;
    protected $gudangRepo;
    protected $responseService;
    public function __construct()
    {
        $this->gudangService =new GudangService();
        $this->gudangRepo =new GudangRepositori();
        $this->responseService = new ResponseService();
        $this->middleware('permission:gudang|gudang-list|gudang-create|gudang-edit|gudang-delete|gudang-trash', ['only' => ['index','show','trashTable']]);
        $this->middleware('permission:gudang|gudang-create', ['only' => ['create','store']]);
        $this->middleware('permission:gudang|gudang-edit', ['only' => ['edit','store']]);
        $this->middleware('permission:gudang|gudang-trash', ['only' => ['trash','restore','bulkDelete']]);
        $this->middleware('permission:gudang|gudang-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q=null;
            } else {
                $q=['website_id'=>website()->id];
            }
        $gudang = $this->gudangService->getWhere($q)->latest()->get();
        // dd($gudang);
        if ($request->ajax()) {
            return Datatables::of($gudang)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                })
                ->addColumn('jenis', function ($row) {
                    if($row->jenis){
                        $jenis='Inventory';
                    }else{
                        $jenis='Factory';
                    }
                    return $jenis;
                })
                ->addColumn('status', function ($row) {
                    if($row->status){
                        $status='<span class="text-success">Aktif</span>';
                    }else{
                        $status='<span class="text-danger">Nonaktif</span>';
                    }
                    return $status;
                })
                ->addColumn('user', function ($row) {
                    $data=$row->user->name??'';
                    return $data;
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    if (auth()->user()->hasPermissionTo('gudang') or auth()->user()->hasPermissionTo('gudang-edit')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_gudang="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editGudang"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    
                    if (auth()->user()->hasPermissionTo('gudang') or auth()->user()->hasPermissionTo('gudang-trash')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_gudang="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteGudang"> <i class="mdi mdi-delete"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['select', 'nama','user','status', 'jenis', 'action'])
                ->make(true);
        }

        return view('gudang/gudang', compact('gudang'));
    }


    public function trashTable(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q=null;
            } else {
                $q=['website_id'=>website()->id];
            }
        $gudang = $this->gudangService->getWhere($q)->onlyTrashed()->latest()->get();
        if ($request->ajax()) {
            return Datatables::of($gudang)
                ->addIndexColumn()
                ->addColumn('jenis', function ($row) {
                    if($row->jenis){
                        $jenis='<span class="text-success">Inventory</span>';
                    }else{
                        $jenis='<span class="text-primary">Factory</span>';
                    }
                    return $jenis;
                })
                ->addColumn('status', function ($row) {
                    if($row->status){
                        $status='<span class="text-success">Aktif</span>';
                    }else{
                        $status='<span class="text-danger">Nonaktif</span>';
                    }
                    return $status;
                })
                ->addColumn('user', function ($row) {
                    $data=$row->user->name??'';
                    return $data;
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    
                    if (auth()->user()->hasPermissionTo('gudang') or auth()->user()->hasPermissionTo('gudang-trash')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_gudang="' . enc($row->id) . '" data-original-title="Restore" class="btn btn-outline-primary restoreGudang"> Restore</a>';
                    }
                    
                    if (auth()->user()->hasPermissionTo('gudang') or auth()->user()->hasPermissionTo('gudang-delete')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_gudang="' . enc($row->id) . '" data-status="delete" data-original-title="Hapus" class="btn btn-outline-danger deleteGudang"> Permanent Delete</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['nama','status','user','jenis','action'])
                ->make(true);
        }
    }

    public function kode(){
        $response = [
            'success' => true,
            'message' => 'Berhasil',
            'data' => createCodeGudang()
        ];
        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        $this->gudangService->store($request);
        return $this->responseService->response(true);
    }

    public function edit($id)
    {
        $id = dec($id);
        $gudang = $this->gudangService->getWhere(['id'=>$id])->with('user')->first();
        return response()->json($gudang);
    }


    public function trash($id)
    {
        $id = dec($id);

            $this->gudangService->getId($id)->delete();
            $response = $this->responseService->response(true);

        return $response;
    }

    public function delete($id)
    {
        $id = dec($id);

            $this->gudangService->getWhere(['id'=>$id])->withTrashed()->forceDelete();
            $response = $this->responseService->response(true);


        return $response;
    }

    public function restore($id)
    {
        $id = dec($id);
        // DB::transaction(function () use ($id) {
            $this->gudangService->getWhere(['id'=>$id])->withTrashed()->restore();
            // $produk = $this->produkService->getAll()->withTrashed()->where('gudang_id', $id);
            // foreach ($produk->get() as $pro) {
            //     Stock::withTrashed()->where('produk_id', $pro->id)->restore();
            // }
            // $produk->restore();
        // });
        return $this->responseService->response(true);
    }


    public function bulkDelete(Request $request)
    {
        $result = DB::transaction(function () use ($request) {

            $id = $request->id;
            $jml_pilih = count($id);
            $dihapus = 0;
            $response = [];
            foreach ($request->id as $delId) {
                $delId = dec($delId);
                // $produk = $this->produkService->getAll()->where('gudang_id', $delId)->withTrashed()->count();
                // if ($produk > 0) {
                // } else {
                    $this->gudangService->getId($delId)->delete();
                    $dihapus = $dihapus + 1;
                // }
            }

            if ($dihapus == $jml_pilih) {
                $response = $this->responseService->response(true);
            } else {
                $response = $this->responseService->response(true, 'Beberapa Gudang tidak dapat dihapus.');
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
           $data = $this->gudangRepo->getWhere($q)->whereStatus(1)->orderBy('nama','asc')->select('id','nama')->get();
        }else{
           $data = $this->gudangRepo->getWhere($q)->whereStatus(1)->orderby('nama','asc')->select('id','nama')->where('nama', 'like', '%' .$search . '%')->get();
        }

        $response = array();
        foreach($data as $data){
           $response[] = array(
                "id"=>$data->id,
                "text"=>$data->nama
           );
        }
        return response()->json($response);
     }
}
