<?php

namespace App\Http\Controllers;

use App\Http\Requests\AkunRequest;
use App\Models\Akun;
use App\Models\KasBank;
use App\Models\KategoriAkun;
use App\Repositori\AkunRepositori;
use App\Repositori\KasRepositori;
use App\Services\AkunService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkunController extends Controller
{
    protected $akunService;
    protected $akunRepo;
    protected $kasRepo;
    protected $responseService;
    public function __construct()
    {
        $this->akunService = new AkunService();
        $this->akunRepo=new AkunRepositori();
        $this->responseService =new ResponseService();
        $this->kasRepo=new KasRepositori();
    }

    public function index(Request $request)
    {
        $q = null;
        $akun = Akun::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $akun->whereBetween('tgl', array($request->from_date, $request->to_date));
        }

        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $akun->where('website_id', website()->id);
            $q = ['website_id' => website()->id];
        }

        if (!empty($request->website)) {
            $akun->where('website_id', $request->website);
            $q = ['website_id' => $request->website];
        }

        $akun=$akun->orderBy('kategori_akun_id','asc')->get();
        if ($request->ajax()) {
           return $this->akunService->getDatatable($akun,1);
        }


        $induk=KasBank::all();
        $kategori_akun=KategoriAkun::orderBy('id','asc')->get();
        return view('akun/akun', compact('akun','kategori_akun','induk'));
    }


    public function trashTable(Request $request)
    {
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        $q=null;
        } else {
            $q=['website_id'=>website()->id];
        }
        $akun=$this->akunRepo->getWhere($q)->onlyTrashed()->get();
        if ($request->ajax()) {
            return $this->akunService->getDatatable($akun,2);
        }
    }

    public function store(AkunRequest $request)
    {
        $this->akunService->store($request);
        return $this->responseService->response(true);
    }

    public function edit($id)
    {
        $id = dec($id);
        $akun = $this->akunRepo->getWhere(['id'=>$id])->with('kategori_akun')->first();
        return response()->json($akun);
    }


    public function trash($id)
    {
        $id = dec($id);

            $this->akunRepo->getId($id)->delete();
            $response = $this->responseService->response(true);

        return $response;
    }

    public function delete($id)
    {
        $id = dec($id);

            $this->akunService->getWhere(['id'=>$id])->withTrashed()->forceDelete();
            $response = $this->responseService->response(true);

        return $response;
    }

    public function restore($id)
    {
        $id = dec($id);
            $this->akunService->getWhere(['id'=>$id])->withTrashed()->restore();
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

                    $this->akunService->getId($delId)->delete();
                    $dihapus = $dihapus + 1;

            }

            if ($dihapus == $jml_pilih) {
                $response = $this->responseService->response(true);
            } else {
                $response = $this->responseService->response(true, 'Beberapa Akun tidak dapat dihapus.');
            }

            return $response;
        });
        // return response

        return $result;
    }


    public function select(Request $request){
        $search = $request->search;

        if($search == ''){
           $data = $this->akunRepo->getWhere(['tipe'=>'detail'])->orderBy('kode','asc')->select('id','kode','name')->get();
        }else{
           $data = $this->akunRepo->getWhere(['tipe'=>'detail'])->orderby('kode','asc')->select('id','kode','name')->where('name', 'like', '%' .$search . '%')->orWhere('kode', 'like', '%' .$search . '%')->get();
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

     public function selectInduk(Request $request){
        $search = $request->search;

        if($search == ''){
           $data = $this->akunRepo->getWhere(['tipe'=>'header'])->orderBy('kode','asc')->select('id','kode','name')->get();
        }else{
           $data = $this->akunRepo->getWhere(['tipe'=>'header'])->orderby('kode','asc')->select('id','kode','name')->where('name', 'like', '%' .$search . '%')->orWhere('kode', 'like', '%' .$search . '%')->get();
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

     public function selectKategori(Request $request){
           $data = $this->akunRepo->getWhere(['kategori_akun_id'=>$request->id,'tipe'=>'header'])->orderBy('kode','asc')->select('id','kode','name')->get();
        $response = array();
        foreach($data as $data){
           $response[] = array(
                "id"=>$data->id,
                "text"=>$data->kode.' - '.$data->name
           );
        }
        return response()->json($response);
     }

     public function tableAkun(Request $request){
        $akun=$this->akunRepo->getWhere(null);
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $akun->where('website_id', website()->id);
        }
        $akun=$akun->orderBy('kategori_akun_id','ASC')->orderBy('kode','ASC')->get();
        if ($request->ajax()) {
            return $this->akunService->getDatatableAkun($akun);
         }
        return view('akun.tableAkun',compact('akun'));
     }



     public function apiAkun(Request $request)
    {
        $akun = $this->akunRepo->getAll($request);

        if ($akun) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $akun,
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

    public function apiAkunId(Request $request)
    {
        $akun = $this->akunRepo->getId($request->id);

        if ($akun) {
            $response = [
                'success' => true,
                'message' => 'Berhasil',
                'data' => $akun,
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

    public function neraca(){
        $lancar=KategoriAkun::whereIn('id',[11,15,14,2])->get();
        $tetap=KategoriAkun::whereIn('id',[3,1])->get();
        $hutang=KategoriAkun::whereIn('id',[9,8,10])->get();
        $ekuitas=KategoriAkun::where('id',6)->get();
        return view('laporan.neraca',compact('lancar','tetap','hutang','ekuitas'));
    }

    public function labaRugi(){
        $pendapatan=KategoriAkun::whereIn('id',[12])->get();
        $hpp=KategoriAkun::whereIn('id',[7])->get();
        $beban=KategoriAkun::whereIn('id',[4])->get();
        $pendapatanLain=KategoriAkun::whereIn('id',[13])->get();
        $bebanLain=KategoriAkun::whereIn('id',[5])->get();
        return view('laporan.labaRugi',compact('pendapatan','hpp','beban','bebanLain','pendapatanLain'));
    }

    public function bukuBesar(){
        $kas=$this->kasRepo->getAll();
        return view('laporan.bukuBesar',compact('kas'));
    }



}
