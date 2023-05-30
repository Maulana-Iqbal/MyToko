<?php

namespace App\Http\Controllers;

use App\Repositori\KategoriRepositori;
use App\Repositori\ProdukRepositori;
use App\Services\KategoriService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    protected $kategoriService;
    protected $kategoriRepo;
    protected $produkRepo;
    protected $responseService;
    public function __construct()
    {
        $this->kategoriService = new KategoriService();
        $this->kategoriRepo = new KategoriRepositori();
        $this->produkRepo = new ProdukRepositori();
        $this->responseService = new ResponseService();
        $this->middleware('permission:kategori|kategori-list|kategori-create|kategori-edit|kategori-delete|kategori-trash', ['only' => ['index', 'show', 'trashTable']]);
        $this->middleware('permission:kategori|kategori-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:kategori|kategori-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:kategori|kategori-trash', ['only' => ['trash', 'restore', 'bulkDelete']]);
        $this->middleware('permission:kategori|kategori-delete', ['only' => ['delete']]);
    }



    public function index(Request $request)
    {
        // dd(auth()->user()->hasRole(['SUPERADMIN','SHOW ALL']));
        // dd(auth()->user()->hasPermissionTo('kategori-list'));
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $kategori = $this->kategoriRepo->getWhere($q)->latest();
        if ($request->ajax()) {
            return $this->kategoriService->getDatatable($kategori, 1);
        }
        return view('kategori/kategori', compact('kategori'));
    }


    public function trashTable(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $kategori = $this->kategoriRepo->getWhere($q)->onlyTrashed()->get();
        if ($request->ajax()) {
            return $this->kategoriService->getDatatable($kategori, 2);
        }
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required'], ['required'=>'Nama Kategori Tidak Boleh Kosong']);
        $save=$this->kategoriService->store($request);
        return $this->responseService->response($save['success'],$save['message'],$save['data']);
    }

    public function edit($id)
    {
        $id = dec($id);
        $kategori = $this->kategoriRepo->getId($id);
        return response()->json($kategori);
    }


    public function trash($id)
    {
        $id = dec($id);
        $produk = $this->produkRepo->getWhere(['kategori_id' => $id])->withTrashed()->count();
        if ($produk > 0) {
            $response = $this->responseService->response(false, 'Masih ada produk terkait kategori ini.');
        } else {
            $this->kategoriRepo->getId($id)->delete();
            $response = $this->responseService->response(true, 'Berhasil Dipindahkan Ke Sampah');
        }
        return $response;
    }

    public function delete($id)
    {
        $id = dec($id);
        $produk = $this->produkRepo->getWhere(['kategori_id' => $id])->withTrashed()->count();
        if ($produk > 0) {
            $response = $this->responseService->response(false, 'Hapus Gagal, Masih ada produk terkait kategori ini.');
        } else {
            $this->kategoriRepo->getWhere(['id' => $id])->withTrashed()->forceDelete();
            $response = $this->responseService->response(true, 'Berhasil Dihapus');
        }

        return $response;
    }

    public function restore($id)
    {
        $id = dec($id);
        $this->kategoriRepo->getWhere(['id' => $id])->withTrashed()->restore();
        return $this->responseService->response(true, 'Berhasil Dikembalikan');
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
                $produk = $this->produkRepo->getWhere(['kategori_id' => $delId])->withTrashed()->count();
                if ($produk > 0) {
                } else {
                    $this->kategoriRepo->getId($delId)->delete();
                    $dihapus = $dihapus + 1;
                }
            }

            if ($dihapus == $jml_pilih) {
                $response = $this->responseService->response(true, 'Semua Data Dipilih Berhasil Dipindahkan Ke Sampah');
            } else {
                $response = $this->responseService->response(true, 'Beberapa Kategori tidak dapat dihapus.');
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
            $data = $this->kategoriRepo->getWhere($q)->orderBy('nama_kategori', 'asc')->select('id', 'nama_kategori')->get();
        } else {
            $data = $this->kategoriRepo->getWhere($q)->orderby('nama_kategori', 'asc')->select('id', 'nama_kategori')->where('nama_kategori', 'like', '%' . $search . '%')->get();
        }

        $response = array();
        foreach ($data as $data) {
            $response[] = array(
                "id" => $data->id,
                "text" => $data->nama_kategori
            );
        }
        return response()->json($response);
    }

    public function apiKategori(Request $request)
    {
        $kategori = $this->kategoriRepo->getAll($request);

        if ($kategori) {
            $response = $this->responseService->response(true, 'Berhasil', $kategori);
        } else {
            $response = $this->responseService->response(false, 'Data Tidak Ditemukan');
        }
        return $response;
    }

    public function apiKategoriId(Request $request)
    {
        $kategori = $this->kategoriRepo->getId($request->id);

        if ($kategori) {
            $response = $this->responseService->response(true, 'Berhasil', $kategori);
        } else {
            $response = $this->responseService->response(false, 'Data Tidak Ditemukan');
        }
        return $response;
    }

    public function apiKategoriSlug(Request $request)
    {
        $kategori = $this->kategoriRepo->getWhere(['slug' => $request->slug])->first();

        if ($kategori) {
            $response = $this->responseService->response(true, 'Berhasil', $kategori);
        } else {
            $response = $this->responseService->response(false, 'Data Tidak Ditemukan');
        }
        return $response;
    }
}
