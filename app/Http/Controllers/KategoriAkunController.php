<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriAkunRequest;
use App\Models\KategoriAkun;
use App\Repositori\KategoriAkunRepositori;
use App\Services\KategoriAkunService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriAkunController extends Controller
{
    protected $kategoriAkunService;
    protected $kategoriAkunRepo;
    protected $responseService;
    public function __construct()
    {
        $this->kategoriAkunService = new KategoriAkunService();
        $this->kategoriAkunRepo=new KategoriAkunRepositori();
        $this->responseService =new ResponseService();
    }

    public function index(Request $request)
    {
        // if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        // $q=null;
        // } else {
        //     $q=['website_id'=>website()->id];
        // }
        $kategoriAkun=$this->kategoriAkunRepo->getAll();
        if ($request->ajax()) {
           return $this->kategoriAkunService->getDatatable($kategoriAkun,1);
        }

        $kategori_kategoriAkun=KategoriAkun::orderBy('id','asc')->get();
        return view('kategoriAkun/kategoriAkun', compact('kategori_kategoriAkun'));
    }



    public function trashTable(Request $request)
    {
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        $q=null;
        } else {
            $q=['website_id'=>website()->id];
        }
        $kategoriAkun=$this->kategoriAkunRepo->getWhere($q)->onlyTrashed()->get();
        if ($request->ajax()) {
            return $this->kategoriAkunService->getDatatable($kategoriAkun,2);
        }
    }

    public function store(KategoriAkunRequest $request)
    {
        $this->kategoriAkunService->store($request);
        return $this->responseService->response(true);
    }

    public function edit($id)
    {
        $id = dec($id);
        $kategoriAkun = $this->kategoriAkunRepo->getId($id);
        return response()->json($kategoriAkun);
    }


    public function trash($id)
    {
        $id = dec($id);

            $this->kategoriAkunRepo->getId($id)->delete();
            $response = $this->responseService->response(true);

        return $response;
    }

    public function delete($id)
    {
        $id = dec($id);

            $this->kategoriAkunService->getWhere(['id'=>$id])->withTrashed()->forceDelete();
            $response = $this->responseService->response(true);

        return $response;
    }

    public function restore($id)
    {
        $id = dec($id);
            $this->kategoriAkunService->getWhere(['id'=>$id])->withTrashed()->restore();
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

                    $this->kategoriAkunService->getId($delId)->delete();
                    $dihapus = $dihapus + 1;

            }

            if ($dihapus == $jml_pilih) {
                $response = $this->responseService->response(true);
            } else {
                $response = $this->responseService->response(true, 'Beberapa KategoriAkun tidak dapat dihapus.');
            }

            return $response;
        });
        // return response

        return $result;
    }


    public function select(Request $request){
        $search = $request->search;
       $q=null;

        if($search == ''){
           $data = $this->kategoriAkunRepo->getWhere($q)->orderBy('name','asc')->select('id','name')->get();
        }else{
           $data = $this->kategoriAkunRepo->getWhere($q)->orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->get();
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



     public function apiKategoriAkun(Request $request)
    {
        $kategoriAkun = $this->kategoriAkunRepo->getAll($request);

        if ($kategoriAkun) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $kategoriAkun,
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

    public function apiKategoriAkunId(Request $request)
    {
        $kategoriAkun = $this->kategoriAkunRepo->getId($request->id);

        if ($kategoriAkun) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $kategoriAkun,
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
