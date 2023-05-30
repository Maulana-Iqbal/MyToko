<?php

namespace App\Http\Controllers;

use App\Http\Requests\RekeningRequest;
use App\Repositori\RekeningRepositori;
use App\Services\RekeningService;
use App\Services\ResponseService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekeningController extends Controller
{
    protected $rekeningService;
    protected $rekeningRepo;
    protected $responseService;
    protected $stockService;
    public function __construct()
    {
        $this->rekeningService = new RekeningService();
        $this->rekeningRepo=new RekeningRepositori();
        $this->responseService =new ResponseService();
        $this->stockService=new StockService();
    }

    public function index(Request $request)
    {
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
            $q=null;
            } else {
                $q=['website_id'=>website()->id];
            }
        $rekening=$this->rekeningRepo->getWhere($q)->latest()->get();
        if ($request->ajax()) {
           return $this->rekeningService->getDatatable($rekening,1);
        }

        return view('rekening/rekening', compact('rekening'));
    }


    public function trashTable(Request $request)
    {
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
            $q=null;
            } else {
                $q=['website_id'=>website()->id];
            }
        $rekening=$this->rekeningRepo->getWhere($q)->onlyTrashed()->latest()->get();
        if ($request->ajax()) {
            return $this->rekeningService->getDatatable($rekening,2);
        }
    }

    public function store(RekeningRequest $request)
    {
        $cek=$this->rekeningRepo->getWhere(['akun_id'=>$request->akun,'website_id'=>website()->id])->where('id','<>',$request->id_rekening)->count();
        if($cek>0){
            return $this->responseService->response(false,"Akun Sudah Digunakan");
        }
        $this->rekeningService->store($request);
        return $this->responseService->response(true);
    }

    public function edit($id)
    {
        $id = dec($id);
        $rekening = $this->rekeningRepo->getId($id);
        return response()->json($rekening);
    }


    public function trash($id)
    {
        $id = dec($id);

            $delete=$this->rekeningRepo->getId($id);
            $delete->isActive=0;
            $delete->save();
            $delete->delete();
            $response = $this->responseService->response(true);
        return $response;
    }

    public function delete($id)
    {
        $id = dec($id);
            $this->rekeningService->getWhere(['id'=>$id])->withTrashed()->forceDelete();
            $response = $this->responseService->response(true);
        return $response;
    }

    public function restore($id)
    {
        $id = dec($id);
            $this->rekeningService->getWhere(['id'=>$id])->withTrashed()->restore();
        return $this->responseService->response(true);
    }


    public function bulkDelete(Request $request)
    {
        $result = DB::transaction(function () use ($request) {

            $id = $request->id;
            $jml_pilih = count($id);
            $dihapus = 0;
            $response = [];
            foreach ($request->id as $del) {
                $delId = dec($del);
                    $this->rekeningService->getId($delId)->delete();
                    $dihapus = $dihapus + 1;
            }

            if ($dihapus == $jml_pilih) {
                $response = $this->responseService->response(true);
            } else {
                $response = $this->responseService->response(true, 'Beberapa Rekening tidak dapat dihapus.');
            }

            return $response;
        });
        // return response

        return $result;
    }


    public function select(Request $request){
        $search = $request->search;
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
            $q=null;
            } else {
                $q=['website_id'=>website()->id];
            }
        if($search == ''){
           $data = $this->rekeningRepo->getWhere($q)->orderBy('nama_rek','asc')->select('id','nama_rek as name')->limit(5)->get();
        }else{
           $data = $this->rekeningRepo->getWhere($q)->orderby('nama_rek','asc')->select('id','nama_rek as name')->where('nama_rek', 'like', '%' .$search . '%')->limit(5)->get();
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

     public function apiRekening(Request $request)
    {
        $rekening = $this->rekeningRepo->getAll($request);

        if ($rekening) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $rekening,
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => [],
            ];
        }
        return response()->json($response, 200);
    }

    public function apiRekeningId(Request $request)
    {
        $rekening = $this->rekeningRepo->getId($request->id);

        if ($rekening) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $rekening,
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => [],
            ];
        }
        return response()->json($response, 200);
    }



}
