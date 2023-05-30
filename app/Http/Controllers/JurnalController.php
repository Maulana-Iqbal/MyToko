<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\KasBank;
use App\Models\Jurnal;
use App\Repositori\KasRepositori;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DataTables;
use Image;

class JurnalController extends Controller
{
protected $kasRepo;
    public function __construct()
    {
        $this->kasRepo=new KasRepositori();
    }

    public function index(Request $request)
    {
        $q = null;
        $jurnal = Jurnal::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $jurnal->whereBetween('tgl', array($request->from_date, $request->to_date));
        }


        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $jurnal = $jurnal->where('website_id', website()->id);
            $q = ['website_id' => website()->id];
        }
        if (!empty($request->website)) {
            $jurnal = $jurnal->where('website_id', $request->website);
            $q = ['website_id' => $request->website];
        }
        $jurnal = $jurnal->latest();
        $jurnal = $jurnal->get();
        if ($request->ajax()) {
            return Datatables::of($jurnal)
                ->addIndexColumn()
                ->addColumn('akun', function ($row) {
                    $akun='';
                    if($row->akun<>null){
                        $akun=$row->akun->name;
                    }
                    return $akun;
                })
                ->addColumn('tgl', function ($row) {
                    $tgl = date('Y-m-d', strtotime($row->tgl));
                    return tglIndo($tgl);
                })
                ->addColumn('debit', function ($row) {
                    $rupiah = "Rp " . number_format($row->debit, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('kredit', function ($row) {
                    $rupiah = "Rp " . number_format($row->kredit, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO' or auth()->user()->level == 'CEO') {
                        if ($row->persetujuan == 1) {
                            $btn = $btn . ' <a href="javascript:void(0)" class="btn btn-outline-info" id="btnVerifikasi" data-id_jurnal="' . $row->id . '" >Verifikasi</a>';
                        }
                    }   // if ($row->persetujuan == 1) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_jurnal="' . $row->id . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editJurnal"> <i class="mdi mdi-square-edit-outline"></i></a>';

                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_jurnal="' . $row->id . '" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteJurnal"> <i class="mdi mdi-delete"></i></a>';
                        // }


                    return $btn;
                })
                ->rawColumns(['akun', 'debit', 'tgl', 'kredit', 'deskripsi', 'action'])
                ->make(true);
        }

        $kasBank = KasBank::orderBy('akun_id','asc')->get();
        return view('kantor/jurnal', compact('jurnal', 'kasBank'));
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
                'name' => 'required',
                'tgl' => 'required',
                // 'akun' => 'required',
            ],
            [
                'required' => 'Data Belum Lengkap'
            ]
        );

        $response = DB::transaction(function () use ($request) {

            foreach($request->idAkun as $index=>$idAkun){

        $debit = str_replace('.', '', $request->debit[$index]);
        $kredit = str_replace('.', '', $request->kredit[$index]);

       $jurnal= Jurnal::updateOrCreate([
            'id' => $request->id_jurnal
        ], [
            'nomor' => $request->nomor,
            'name' => $request->name,
            'no_inv' => $request->no_inv,
            'tgl' => $request->tgl,
            'akun_id' => $request->idAkun[$index],
            'debit' => $debit,
            'kredit' => $kredit,
            'deskripsi' => $request->deskripsi,
            'website_id' => website()->id,
        ]);

        $data=[];
        $data['sumber']='jurnal';
        $data['sumber_id']=$jurnal->id;
        $data['nomor']=$request->nomor;
        $data['tgl']=$request->tgl;
        $data['website_id']=website()->id;


        //save ke
        $data['debit']=$debit;
        $data['kredit']=$kredit;
        $data['akun_id']=$request->idAkun[$index];
        if(empty($request->id_jurnal)){
            $this->kasRepo->store($data);
        }
        $this->kasRepo->update(['sumber'=>$data['sumber'],'sumber_id'=>$data['sumber_id']],$data);

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
     * @param  \App\Models\Jurnal  $jurnal
     * @return \Illuminate\Http\Response
     */
    public function show(Jurnal $jurnal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jurnal  $jurnal
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jurnal = Jurnal::find($id);
        return response()->json($jurnal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jurnal  $jurnal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jurnal $jurnal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jurnal  $jurnal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->kasRepo->getWhere(['sumber'=>'jurnal','sumber_id'=>$id])->first()->delete();
        $del = Jurnal::find($id)->delete();
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

    public function apiDataJurnal($id)
    {
        $jurnal = Jurnal::latest();
        $jurnal->where('persetujuan', $id);
        $jurnal = $jurnal->get();
        return response()
            ->json(['message' => 'Berhasil mengambil data jurnal', 'token_type' => 'Bearer', 'data' => $jurnal]);
    }




}
