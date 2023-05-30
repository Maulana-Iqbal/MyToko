<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Repositori\ProdukRepositori;
use App\Repositori\SatuanRepositori;
use App\Services\SatuanService;
use App\Services\ResponseService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    protected $satuanService;
    protected $satuanRepo;
    protected $responseService;
    protected $produkRepo;
    public function __construct()
    {
        $this->satuanService = new SatuanService();
        $this->satuanRepo = new SatuanRepositori();
        $this->responseService = new ResponseService();
        $this->produkRepo = new ProdukRepositori();
        $this->middleware('permission:satuan|satuan-list|satuan-create|satuan-edit|satuan-trash|satuan-delete', ['only' => ['index', 'show', 'trashTable']]);
        $this->middleware('permission:satuan|satuan-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:satuan|satuan-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:satuan|satuan-trash', ['only' => ['trash', 'restore', 'bulkDelete']]);
        $this->middleware('permission:satuan|satuan-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $satuan = $this->satuanRepo->getWhere($q)->get();
        if ($request->ajax()) {
            return $this->satuanService->getDatatable($satuan, 1);
        }

        return view('satuan/satuan');
    }


    public function trashTable(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $satuan = $this->satuanRepo->getWhere($q)->onlyTrashed()->get();
        if ($request->ajax()) {
            return $this->satuanService->getDatatable($satuan, 2);
        }
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required'], ['required' => 'Satuan Tidak Boleh Kosong']);
        $save = $this->satuanService->store($request);
        return $this->responseService->response($save['success'], $save['message'], $save['data']);
    }

    public function edit($id)
    {
        $id = dec($id);
        $satuan = $this->satuanRepo->getId($id);
        return response()->json($satuan);
    }


    public function trash($id)
    {
        $id = dec($id);
        $produk = Produk::where('satuan_id', $id)->withTrashed()->count();
        if ($produk > 0) {
            $response = $this->responseService->response(false, 'Hapus Gagal, Masih ada Produk terkait satuan ini.');
        } else {
            $this->satuanRepo->getId($id)->delete();
            $response = $this->responseService->response(true, 'Data Berhasil Dipindahkan Ke Sampah');
        }
        return $response;
    }

    public function delete($id)
    {
        $id = dec($id);
        $produk = $this->produkRepo->getWhere(['satuan_id' => $id])->withTrashed()->count();
        if ($produk > 0) {
            $response = $this->responseService->response(false, 'Masih ada Produk terkait satuan ini.');
        } else {
            $this->satuanRepo->getWhere(['id' => $id])->withTrashed()->forceDelete();
            $response = $this->responseService->response(true, 'Data Berhasil Dihapus');
        }

        return $response;
    }

    public function restore($id)
    {
        $id = dec($id);
        // DB::transaction(function () use ($id) {
        $this->satuanRepo->getWhere(['id' => $id])->withTrashed()->restore();
        // $produk = $this->produkService->getAll()->withTrashed()->where('satuan_id', $id);
        // foreach ($produk->get() as $pro) {
        //     Stock::withTrashed()->where('produk_id', $pro->id)->restore();
        // }
        // $produk->restore();
        // });
        return $this->responseService->response(true, 'Data Berhasil Dikembalikan');
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
                $stock = $this->stockService->getWhere(['satuan_id' => $delId])->withTrashed()->count();
                if ($stock > 0) {
                } else {
                    $this->satuanRepo->getId($delId)->delete();
                    $dihapus = $dihapus + 1;
                }
            }

            if ($dihapus == $jml_pilih) {
                $response = $this->responseService->response(true, 'Data Dipilih Berhasil Dihapus');
            } else {
                $response = $this->responseService->response(true, 'Beberapa Satuan tidak dapat dihapus.');
            }

            return $response;
        });
        // return response

        return $result;
    }


    public function select(Request $request)
    {
        $search = $request->search;
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        if ($search == '') {
            $data = $this->satuanRepo->getWhere($q)->orderBy('name', 'asc')->select('id', 'name')->get();
        } else {
            $data = $this->satuanRepo->getWhere($q)->orderby('name', 'asc')->select('id', 'name')->where('name', 'like', '%' . $search . '%')->get();
        }

        $response = array();
        foreach ($data as $data) {
            $response[] = array(
                "id" => $data->id,
                "text" => $data->name
            );
        }
        return response()->json($response);
    }

    public function apiSatuan(Request $request)
    {
        $satuan = $this->satuanRepo->getAll($request);

        if ($satuan) {
            $response = $this->responseService->response(true, 'Berhasil', $satuan);
        } else {
            $response = $this->responseService->response(true, 'Data Tidak Ditemukan');
        }
        return $response;
    }

    public function apiSatuanId(Request $request)
    {
        $satuan = $this->satuanRepo->getId($request->id);

        if ($satuan) {
            $response = $this->responseService->response(true, 'Berhasil', $satuan);
        } else {
            $response = $this->responseService->response(true, 'Data Tidak Ditemukan');
        }
        return $response;
    }
}
