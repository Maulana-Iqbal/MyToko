<?php

namespace App\Http\Controllers;

use App\Http\Requests\PelangganRequest;
use App\Models\ProvinceOngkir;
use App\Repositori\PelangganRepositori;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    protected $pelangganRepo;
    protected $responseService;
    public function __construct()
    {
        $this->pelangganRepo = new PelangganRepositori();
        $this->responseService = new ResponseService();
        $this->middleware('permission:customer|customer-list|customer-create|customer-edit|customer-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:customer|customer-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer|customer-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer|customer-delete', ['only' => ['delete', 'bulkDelete']]);
    }

    public function index(Request $request)
    {
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $pelanggan = $this->pelangganRepo->getWhere($q)->latest()->get();
        if ($request->ajax()) {
            return Datatables::of($pelanggan)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                })
                ->addColumn('nama_pelanggan', function ($row) {
                    $namaDepan = $row->nama_depan;
                    $namaBelakang = $row->nama_belakang;
                    $namaLengkap = $namaDepan . ' ' . $namaBelakang;
                    return $namaLengkap;
                })
                ->addColumn('alamat', function ($row) {
                    $data = $row->alamat . ',' . $row->kecamatan->name . ',' . $row->kota->name . ',' . $row->provinsi->name . ',' . $row->pos;
                    return $data;
                })
                ->addColumn('toko', function ($row) {
                    $data = website($row->website_id)->nama_website;
                    return $data;
                })
                ->addColumn('action', function ($row) {

                    $btn = "";
                    if (auth()->user()->hasPermissionTo('customer') or auth()->user()->hasPermissionTo('customer-edit')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pelanggan="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editPelanggan"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (auth()->user()->hasPermissionTo('customer') or auth()->user()->hasPermissionTo('customer-delete')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pelanggan="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deletePelanggan"> <i class="mdi mdi-delete"></i></a>';
                    }


                    return $btn;
                })
                ->rawColumns(['select', 'alamat', 'nama_pelanggan', 'action'])
                ->make(true);
        }
        $page = 'pelanggan';
        return view('pelanggan/pelanggan', compact('pelanggan', 'page'));
    }

    public function store(PelangganRequest $request)
    {
        $data = [
            'nama_depan' => $request->nama_depan,
            'nama_belakang' => $request->nama_belakang,
            'perusahaan' => $request->perusahaan,
            'email' => $request->email,
            'telpon' => $request->telpon,
            'hp' => $request->telpon,
            'provinsi_id' => $request->provinsi,
            'kota_id' => $request->kabupaten,
            'kecamatan_id' => $request->kecamatan,
            'pos' => $request->kode_pos,
            'alamat' => $request->alamat,
            'logo' => 'customer.png',
            'status_data' => 1,
            'website_id' => website()->id,
        ];
        if ($request->pelangganId) {
            $this->pelangganRepo->update($request->pelangganId, $data);
        } else {
            $this->pelangganRepo->store($data);
        }
        $response = $this->responseService->response(true, 'Data Berhasil Disimpan');
        return $response;
    }

    public function update(PelangganRequest $request)
    {
        $id = dec($request->id);
        $this->pelangganRepo->update($id, [
            'nama_depan' => $request->nama_depan,
            'nama_belakang' => $request->nama_belakang,
            'perusahaan' => $request->perusahaan,
            'email' => $request->email,
            'telpon' => $request->telpon,
            'hp' => $request->telpon,
            'provinsi_id' => $request->provinsi,
            'kota_id' => $request->kabupaten,
            'kecamatan_id' => $request->kecamatan,
            'pos' => $request->kode_pos,
            'alamat' => $request->alamat,
            'logo' => 'customer.png',
            'status_data' => 1,
            'website_id' => website()->id,
        ]);
        $response = $this->responseService->response(true, 'Data Berhasil Diubah');
        return $response;
    }

    public function edit($id)
    {
        $id = dec($id);
        $pelanggan = $this->pelangganRepo->getWhere(['id' => $id])->with('provinsi')->with('kota')->with('kecamatan')->first();
        return response()->json($pelanggan);
    }

    public function delete($id)
    {
        $id = dec($id);
        if ($id == 1) {
            $response = $this->responseService->response(false, 'Data ini tidak dapat dihapus');
            return $response;
        }
        $this->pelangganRepo->getId($id)->delete();
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
                $delId = dec($del);
                $this->pelangganRepo->getId($delId)->delete();
                $dihapus = $dihapus + 1;
            }

            if ($dihapus == $jml_pilih) {
                $response = $this->responseService->response(true);
            } else {
                $response = $this->responseService->response(true, 'Beberapa Pelanggan tidak dapat dihapus.');
            }

            return $response;
        });
        // return response

        return $result;
    }


    public function listPelanggan()
    {
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $pelanggan = $this->pelangganRepo->getWhere($q)->get()->sortBy('nama_depan');
        foreach ($pelanggan as $value) {
            echo ' <a href="javascript:void(0);" id="idPelanggan" data-id="' . $value->id . '" class="dropdown-item notify-item">
            <div class="d-flex mt-2">
            <img class="d-flex me-2 rounded-circle" src="/icon/customer.png" alt="' . $value->nama_depan . ' ' . $value->nama_belakang . '" height="32">

            <div class="w-100">
            <h5 class="m-0 font-14">' . $value->nama_depan . ' ' . $value->nama_belakang . '</h5>
            <span class="font-12 mb-0">' . $value->perusahaan . '</span>
            </div>
            </div>
            </a>';
        }
        // return response()->json($produk, 200);
    }

    public function cariPelanggan(Request $request)
    {
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        $pelanggan = $this->pelangganRepo->getWhere($q)->where('nama_depan', 'LIKE', '%' . $request->name . '%')->orWhere('nama_belakang', 'LIKE', '%' . $request->name . '%')->orWhere('perusahaan', 'LIKE', '%' . $request->name . '%')->get();
        foreach ($pelanggan as $value) {
            echo ' <a href="javascript:void(0);" id="idPelanggan" data-id="' . $value->id . '" class="dropdown-item notify-item">
            <div class="d-flex mt-2">
            <img class="d-flex me-2 rounded-circle" src="icon/customer.png" alt="' . $value->nama_depan . ' ' . $value->nama_belakang . '" height="32">
            <div class="w-100">
            <h5 class="m-0 font-14">' . $value->nama_depan . ' ' . $value->nama_belakang . '</h5>
            <span class="font-12 mb-0">' . $value->perusahaan . '</span>
            </div>
            </div>
            </a>';
        }
        // return response()->json($produk, 200);
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
            $data = $this->pelangganRepo->getWhere($q)->orderBy('nama_depan', 'asc')->select('id', 'nama_depan', 'nama_belakang')->get();
        } else {
            $data = $this->pelangganRepo->getWhere($q)->orderby('nama_depan', 'asc')->select('id', 'nama_depan', 'nama_belakang')->where('nama_depan', 'like', '%' . $search . '%')->orWhere('nama_belakang', 'like', '%' . $search . '%')->get();
        }

        $response = array();
        foreach ($data as $data) {
            $response[] = array(
                "id" => $data->id,
                "text" => $data->nama_depan . ' ' . $data->nama_belakang
            );
        }
        return response()->json($response);
    }
}
