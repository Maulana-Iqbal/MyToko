<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\KasBank;
use App\Models\Penerimaan;
use App\Repositori\KasRepositori;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DataTables;
use Image;

class PenerimaanController extends Controller
{
    protected $kasRepo;
    public function __construct()
    {
        $this->kasRepo = new KasRepositori();
    }

    public function index(Request $request)
    {
        $q = null;
        $penerimaan = Penerimaan::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $penerimaan->whereBetween('tgl', array($request->from_date, $request->to_date));
        }


        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $penerimaan = $penerimaan->where('website_id', website()->id);
            $q = ['website_id' => website()->id];
        }
        if (!empty($request->website)) {
            $penerimaan = $penerimaan->where('website_id', $request->website);
            $q = ['website_id' => $request->website];
        }
        $penerimaan = $penerimaan->latest();
        $penerimaan = $penerimaan->get();
        if ($request->ajax()) {
            return Datatables::of($penerimaan)
                ->addIndexColumn()

                ->addColumn('select', function ($row) {
                    if ($row->persetujuan == 1) {
                        return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                    } else {
                        return '';
                    }
                })
                ->addColumn('akun', function ($row) {
                    $akun = '';
                    if ($row->akun <> null) {
                        $akun = $row->akun->kategori_akun->name.', '.$row->akun->name;
                    }
                    return $akun;
                })
                ->addColumn('ke', function ($row) {
                    $ke = '';
                    if (!empty($row->akun_ke->kode)) {
                        $ke = $row->akun_ke->name;
                    }
                    return $ke;
                })
                ->addColumn('tgl', function ($row) {
                    $tgl = date('Y-m-d', strtotime($row->tgl));
                    return tglIndo($tgl);
                })
                ->addColumn('jumlah', function ($row) {
                    $rupiah = "Rp " . number_format($row->jumlah, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO' or auth()->user()->level == 'CEO') {
                        if ($row->persetujuan == 1) {
                            $btn = $btn . ' <a href="javascript:void(0)" class="btn btn-outline-info" id="btnVerifikasi" data-id_penerimaan="' . $row->id . '" >Verifikasi</a>';
                        }
                    }   // if ($row->persetujuan == 1) {
                    // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_penerimaan="' . $row->id . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editPenerimaan"> <i class="mdi mdi-square-edit-outline"></i></a>';

                    // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_penerimaan="' . $row->id . '" data-original-title="Delete" class="btn btn-outline-danger btn-xs deletePenerimaan"> <i class="mdi mdi-delete"></i></a>';
                    // }


                    return $btn;
                })
                ->rawColumns(['akun', 'select', 'ke', 'tgl', 'jumlah', 'deskripsi', 'action'])
                ->make(true);
        }

        $kasBank = KasBank::orderBy('akun_id', 'asc')->get();
        return view('kantor/penerimaan', compact('penerimaan', 'kasBank'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate(
            [
                'nomor' => 'required',
                'ke' => 'required',
                'tgl' => 'required',
                // 'akun' => 'required',
                // 'jumlah' => 'required',
            ],
            [
                'required' => 'Data Belum Lengkap'
            ]
        );

        $response = DB::transaction(function () use ($request) {
            foreach($request->idAkun as $index=>$idAkun){

            $jumlah = str_replace('.', '', $request->jumlah[$index]);

            $id_penerimaan = $request->id_penerimaan;
            if (!empty($id_penerimaan)) {

                $penerimaan = Penerimaan::where('id', $id_penerimaan)->first();

                $data = [];
                $data['nomor'] = $request->nomor;
                $data['tgl'] = $request->tgl;
                $data['website_id'] = website()->id;
                $data['deskripsi'] = $request->deskripsi;
                $data['sumber'] = 'penerimaan';
                $data['sumber_id'] = $id_penerimaan;
                $data['debit'] = $jumlah;
                $data['kredit'] = 0;
                $data['akun_id'] = $request->ke;
                $this->kasRepo->update(['sumber' => $data['sumber'], 'sumber_id' => $data['sumber_id'], 'akun_id' => $penerimaan->ke], $data);

                $data = [];
                $data['nomor'] = $request->nomor;
                $data['tgl'] = $request->tgl;
                $data['website_id'] = website()->id;
                $data['deskripsi'] = $request->deskripsi;
                $data['sumber'] = 'penerimaan';
                $data['sumber_id'] = $id_penerimaan;
                $data['debit'] = 0;
                $data['kredit'] = $jumlah;
                $data['akun_id'] = $request->idAkun[$index];
                $this->kasRepo->update(['sumber' => $data['sumber'], 'sumber_id' => $data['sumber_id'], 'akun_id' => $penerimaan->akun_id], $data);

                $data = [];
                $data['nomor'] = $request->nomor;
                $data['tgl'] = $request->tgl;
                $data['website_id'] = website()->id;
                $data['deskripsi'] = $request->deskripsi;
                $data['ke'] = $request->ke;
                $data['akun_id'] = $request->idAkun[$index];
                $update = $penerimaan->update($data);

            } else {
                $data = [];
                $data['nomor'] = $request->nomor;
                $data['tgl'] = $request->tgl;
                $data['website_id'] = website()->id;
                $data['deskripsi'] = $request->deskripsi;
                $data['jumlah'] = $jumlah;
                $data['ke'] = $request->ke;
                $data['akun_id'] = $request->idAkun[$index];
                $penerimaan = Penerimaan::create($data);

                $data = [];
                $data['nomor'] = $request->nomor;
                $data['tgl'] = $request->tgl;
                $data['website_id'] = website()->id;
                $data['deskripsi'] = $request->deskripsi;
                $data['sumber'] = 'penerimaan';
                $data['sumber_id'] = $penerimaan->id;
                $data['debit'] = $jumlah;
                $data['kredit'] = 0;
                $data['akun_id'] = $request->ke;
                $this->kasRepo->store($data);

                $data = [];
                $data['nomor'] = $request->nomor;
                $data['tgl'] = $request->tgl;
                $data['website_id'] = website()->id;
                $data['deskripsi'] = $request->deskripsi;
                $data['sumber'] = 'penerimaan';
                $data['sumber_id'] = $penerimaan->id;
                $data['debit'] = 0;
                $data['kredit'] = $jumlah;
                $data['akun_id'] = $request->idAkun[$index];
                $this->kasRepo->store($data);
            }

        }

            $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan.',
            ];
            return $response;
        });


        // return response

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penerimaan  $penerimaan
     * @return \Illuminate\Http\Response
     */
    public function show(Penerimaan $penerimaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penerimaan  $penerimaan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $penerimaan = Penerimaan::find($id);
        return response()->json($penerimaan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penerimaan  $penerimaan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penerimaan $penerimaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penerimaan  $penerimaan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Penerimaan::find($id)->delete();
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

    public function apiDataPenerimaan($id)
    {
        $penerimaan = Penerimaan::latest();
        $penerimaan->where('persetujuan', $id);
        $penerimaan = $penerimaan->get();
        return response()
            ->json(['message' => 'Berhasil mengambil data penerimaan', 'token_type' => 'Bearer', 'data' => $penerimaan]);
    }
}
