<?php

namespace App\Http\Controllers;

use App\Repositori\PemasokRepositori;
use App\Services\PemasokService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class PemasokController extends Controller
{
    protected $pemasokService;
    protected $pemasokRepo;
    protected $responseService;
    public function __construct()
    {
        $this->pemasokService =new PemasokService();
        $this->pemasokRepo=new PemasokRepositori();
        $this->responseService = new ResponseService();
        $this->middleware('permission:supplier|supplier-list|supplier-create|supplier-edit|supplier-delete|supplier-trash', ['only' => ['index','show','trashTable']]);
        $this->middleware('permission:supplier|supplier-create', ['only' => ['create','store']]);
        $this->middleware('permission:supplier|supplier-edit', ['only' => ['edit','store']]);
        $this->middleware('permission:supplier|supplier-trash', ['only' => ['trash','restore','bulkDelete']]);
        $this->middleware('permission:supplier|supplier-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q=null;
            } else {
                $q=['website_id'=>website()->id];
            }
        $pemasok = $this->pemasokService->getWhere($q)->latest()->get();
        // dd($pemasok);
        if ($request->ajax()) {
            return Datatables::of($pemasok)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                })
                ->addColumn('aktif', function ($row) {
                    if($row->aktif){
                        $aktif='<span class="text-success">Aktif</span>';
                    }else{
                        $aktif='<span class="text-danger">Nonaktif</span>';
                    }
                    return $aktif;
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    if (auth()->user()->hasPermissionTo('supplier') or auth()->user()->hasPermissionTo('supplier-edit')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pemasok="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editPemasok"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    
                    if (auth()->user()->hasPermissionTo('supplier') or auth()->user()->hasPermissionTo('supplier-trash')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pemasok="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deletePemasok"> <i class="mdi mdi-delete"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['select', 'nama','aktif', 'action'])
                ->make(true);
        }

        return view('supplier/pemasok', compact('pemasok'));
    }


    public function trashTable(Request $request)
    {
        
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q=null;
            } else {
                $q=['website_id'=>website()->id];
            }
        $pemasok = $this->pemasokService->getWhere($q)->onlyTrashed()->latest()->get();
        if ($request->ajax()) {
            return Datatables::of($pemasok)
                ->addIndexColumn()
                ->addColumn('icon', function ($row) {
                    return "<a href='/icon/" . $row->icon . "'><img width='50px' src='icon/" . $row->icon . "'/></a>";
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    
                    if (auth()->user()->hasPermissionTo('supplier') or auth()->user()->hasPermissionTo('supplier-trash')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pemasok="' . enc($row->id) . '" data-original-title="Restore" class="btn btn-outline-primary restorePemasok"> Restore</a>';
                    }
                    
                    if (auth()->user()->hasPermissionTo('supplier') or auth()->user()->hasPermissionTo('supplier-delete')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pemasok="' . enc($row->id) . '" data-status="delete" data-original-title="Hapus" class="btn btn-outline-danger deletePemasok"> Permanent Delete</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['nama', 'icon', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->pemasokService->store($request);
        return $this->responseService->response(true);
    }

    public function edit($id)
    {
        $id = dec($id);
        $pemasok = $this->pemasokService->getId($id);
        return response()->json($pemasok);
    }


    public function trash($id)
    {
        $id = dec($id);
        if($id==1){
            $response = $this->responseService->response(false,'Data ini tidak dapat dihapus');
            return $response;
        }
            $this->pemasokService->getId($id)->delete();
            $response = $this->responseService->response(true);

        return $response;
    }

    public function delete($id)
    {
        $id = dec($id);
        if($id==1){
            $response = $this->responseService->response(false,'Data ini tidak dapat dihapus');
            return $response;
        }
            $this->pemasokService->getWhere(['id'=>$id])->withTrashed()->forceDelete();
            $response = $this->responseService->response(true);


        return $response;
    }

    public function restore($id)
    {
        $id = dec($id);
        // DB::transaction(function () use ($id) {
            $this->pemasokService->getWhere(['id'=>$id])->withTrashed()->restore();
            // $produk = $this->produkService->getAll()->withTrashed()->where('pemasok_id', $id);
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
                // $produk = $this->produkService->getAll()->where('pemasok_id', $delId)->withTrashed()->count();
                // if ($produk > 0) {
                // } else {
                    $this->pemasokService->getId($delId)->delete();
                    $dihapus = $dihapus + 1;
                // }
            }

            if ($dihapus == $jml_pilih) {
                $response = $this->responseService->response(true);
            } else {
                $response = $this->responseService->response(true, 'Beberapa Pemasok tidak dapat dihapus.');
            }

            return $response;
        });
        // return response

        return $result;
    }


    public function kode()
    {
        $response = [
            'success' => true,
            'message' => 'Berhasil',
            'data' => createCodeSupplier()
        ];
        return response()->json($response, 200);
    }

    public function select(Request $request){
        $search = $request->search;
       
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q=null;
            } else {
                $q=['website_id'=>website()->id];
            }
        if($search == ''){
           $data = $this->pemasokRepo->getWhere($q)->orderBy('perusahaan','asc')->select('id','kode','perusahaan')->get();
        }else{
           $data = $this->pemasokRepo->getWhere($q)->orderby('perusahaan','asc')->select('id','kode','perusahaan')->where('perusahaan', 'like', '%' .$search . '%')->orWhere('kode',$search)->get();
        }

        $response = array();
        foreach($data as $data){
           $response[] = array(
                "id"=>$data->id,
                "text"=>$data->kode.' - '.$data->perusahaan
           );
        }
        return response()->json($response);
     }
}
