<?php

namespace App\Http\Controllers;

use App\Http\Requests\KasBankRequest;
use App\Models\Akun;
use App\Models\KasBank;
use App\Repositori\KasBankRepositori;
use App\Services\KasBankService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasBankController extends Controller
{
    protected $kasBankService;
    protected $kasBankRepo;
    protected $responseService;
    public function __construct()
    {
        $this->kasBankService = new KasBankService();
        $this->kasBankRepo=new KasBankRepositori();
        $this->responseService =new ResponseService();
    }

    public function index(Request $request)
    {
        $q = null;
        $kasBank=$this->kasBankRepo->getWhere(null)->orderBy('akun_id','asc');
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $kasBank->whereBetween('tgl', array($request->from_date, $request->to_date));
        }


        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $kasBank = $kasBank->where('website_id', website()->id);
            $q = ['website_id' => website()->id];
        }
        if (!empty($request->website)) {
            $kasBank = $kasBank->where('website_id', $request->website);
            $q = ['website_id' => $request->website];
        }
        $kasBank = $kasBank->latest();
        $kasBank = $kasBank->get();
        if ($request->ajax()) {
           return $this->kasBankService->getDatatable($kasBank,1);
        }

        $induk=$kasBank;
        return view('kasBank/kasBank', compact('kasBank','induk'));
    }


    public function trashTable(Request $request)
    {
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        $q=null;
        } else {
            $q=['website_id'=>website()->id];
        }
        $kasBank=$this->kasBankRepo->getWhere($q)->onlyTrashed()->get();
        if ($request->ajax()) {
            return $this->kasBankService->getDatatable($kasBank,2);
        }
    }

    public function store(KasBankRequest $request)
    {
        $this->kasBankService->store($request);
        return $this->responseService->response(true);
    }

    public function edit($id)
    {
        $id = dec($id);
        $kasBank = $this->kasBankRepo->getId($id);
        return response()->json($kasBank);
    }


    public function trash($id)
    {
        $id = dec($id);

            $this->kasBankRepo->getId($id)->delete();
            $response = $this->responseService->response(true);

        return $response;
    }

    public function delete($id)
    {
        $id = dec($id);

            $this->kasBankService->getWhere(['id'=>$id])->withTrashed()->forceDelete();
            $response = $this->responseService->response(true);

        return $response;
    }

    public function restore($id)
    {
        $id = dec($id);
            $this->kasBankService->getWhere(['id'=>$id])->withTrashed()->restore();
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

                    $this->kasBankService->getId($delId)->delete();
                    $dihapus = $dihapus + 1;

            }

            if ($dihapus == $jml_pilih) {
                $response = $this->responseService->response(true);
            } else {
                $response = $this->responseService->response(true, 'Beberapa KasBank tidak dapat dihapus.');
            }

            return $response;
        });
        // return response

        return $result;
    }


    public function select(Request $request){
        $search = $request->search;

        if($search == ''){
           $data = $this->kasBankRepo->getWhere(null)->orderBy('kode','asc')->select('id','kode','name')->get();
        }else{
           $data = $this->kasBankRepo->getWhere(null)->orderby('kode','asc')->select('id','kode','name')->where('name', 'like', '%' .$search . '%')->orWhere('kode', 'like', '%' .$search . '%')->get();
        }

        $response = array();
        foreach($data as $data){
           $response[] = array(
                "id"=>$data->id,
                "text"=>$data->kode.' - '.$data->name
           );
        }
        return response()->json($response);
     }



     public function apiKasBank(Request $request)
    {
        $kasBank = $this->kasBankRepo->getAll($request);

        if ($kasBank) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $kasBank,
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

    public function apiKasBankId(Request $request)
    {
        $kasBank = $this->kasBankRepo->getId($request->id);

        if ($kasBank) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $kasBank,
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
