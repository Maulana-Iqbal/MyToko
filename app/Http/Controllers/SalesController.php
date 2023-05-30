<?php

namespace App\Http\Controllers;

use App\Repositori\SalesRepositori;
use App\Services\SalesService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    protected $salesService;
    protected $salesRepo;
    protected $responseService;
    public function __construct()
    {
        $this->salesService =new SalesService();
        $this->salesRepo=new SalesRepositori();
        $this->responseService = new ResponseService();
        $this->middleware('permission:sales|sales-list|sales-create|sales-edit|sales-delete|sales-trash', ['only' => ['index','show','trashTable']]);
        $this->middleware('permission:sales|sales-create', ['only' => ['create','store']]);
        $this->middleware('permission:sales|sales-edit', ['only' => ['edit','store']]);
        $this->middleware('permission:sales|sales-trash', ['only' => ['trash','restore','bulkDelete']]);
        $this->middleware('permission:sales|sales-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q=null;
            } else {
                $q=['website_id'=>website()->id];
            }
        $sales = $this->salesService->getWhere($q)->latest()->get();
        // dd($sales);
        if ($request->ajax()) {
            return Datatables::of($sales)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                })
                ->addColumn('jk', function ($row) {
                    if($row->jk==1){
                        $jk='<span class="text-success">Laki - Laki</span>';
                    }else{
                        $jk='<span class="text-danger">Perempuan</span>';
                    }
                    return $jk;
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
                    if (auth()->user()->hasPermissionTo('sales') or auth()->user()->hasPermissionTo('sales-edit')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_sales="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editSales"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    
                    if (auth()->user()->hasPermissionTo('sales') or auth()->user()->hasPermissionTo('sales-trash')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_sales="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteSales"> <i class="mdi mdi-delete"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['select', 'nama','aktif','jk', 'action'])
                ->make(true);
        }

        return view('sales/sales', compact('sales'));
    }


    public function trashTable(Request $request)
    {
        
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q=null;
            } else {
                $q=['website_id'=>website()->id];
            }
        $sales = $this->salesService->getWhere($q)->onlyTrashed()->latest()->get();
        if ($request->ajax()) {
            return Datatables::of($sales)
                ->addIndexColumn()
                ->addColumn('icon', function ($row) {
                    return "<a href='/icon/" . $row->icon . "'><img width='50px' src='icon/" . $row->icon . "'/></a>";
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    
                    if (auth()->user()->hasPermissionTo('sales') or auth()->user()->hasPermissionTo('sales-trash')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_sales="' . enc($row->id) . '" data-original-title="Restore" class="btn btn-outline-primary restoreSales"> Restore</a>';
                    }
                    
                    if (auth()->user()->hasPermissionTo('sales') or auth()->user()->hasPermissionTo('sales-delete')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_sales="' . enc($row->id) . '" data-status="delete" data-original-title="Hapus" class="btn btn-outline-danger deleteSales"> Permanent Delete</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['nama', 'icon', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->salesService->store($request);
        return $this->responseService->response(true);
    }

    public function edit($id)
    {
        $id = dec($id);
        $sales = $this->salesService->getId($id);
        return response()->json($sales);
    }


    public function trash($id)
    {
        $id = dec($id);
        if($id==1){
            $response = $this->responseService->response(false,'Data ini tidak dapat dihapus');
            return $response;
        }
            $this->salesService->getId($id)->delete();
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
            $this->salesService->getWhere(['id'=>$id])->withTrashed()->forceDelete();
            $response = $this->responseService->response(true);


        return $response;
    }

    public function restore($id)
    {
        $id = dec($id);
        // DB::transaction(function () use ($id) {
            $this->salesService->getWhere(['id'=>$id])->withTrashed()->restore();
            // $produk = $this->produkService->getAll()->withTrashed()->where('sales_id', $id);
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
                // $produk = $this->produkService->getAll()->where('sales_id', $delId)->withTrashed()->count();
                // if ($produk > 0) {
                // } else {
                    $this->salesService->getId($delId)->delete();
                    $dihapus = $dihapus + 1;
                // }
            }

            if ($dihapus == $jml_pilih) {
                $response = $this->responseService->response(true);
            } else {
                $response = $this->responseService->response(true, 'Beberapa Sales tidak dapat dihapus.');
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
            'data' => createCodeSales()
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
           $data = $this->salesRepo->getWhere($q)->orderBy('nama','asc')->select('id','nama')->get();
        }else{
           $data = $this->salesRepo->getWhere($q)->orderby('nama','asc')->select('id','nama')->where('nama', 'like', '%' .$search . '%')->get();
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
