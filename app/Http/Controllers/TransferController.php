<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\KasBank;
use App\Models\Transfer;
use App\Repositori\KasRepositori;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DataTables;
use Image;

class TransferController extends Controller
{
protected $kasRepo;
    public function __construct()
    {
        $this->kasRepo = new KasRepositori();
    }


    public function index(Request $request)
    {
        $q = null;
        $transfer = Transfer::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $transfer->whereBetween('tgl', array($request->from_date, $request->to_date));
        }

        if (!empty($request->persetujuan)) {
            $transfer->where('persetujuan', $request->persetujuan);
        }

        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $transfer = $transfer->where('website_id', website()->id);
            $q = ['website_id' => website()->id];
        }
        if (!empty($request->website)) {
            $transfer = $transfer->where('website_id', $request->website);
            $q = ['website_id' => $request->website];
        }
        $transfer = $transfer->latest();
        $transfer = $transfer->get();
        if ($request->ajax()) {
            return Datatables::of($transfer)
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
                        $akun = $row->akun->name;
                    }
                    return $akun;
                })
                ->addColumn('dari', function ($row) {
                    $dari = '';
                    if (!empty($row->akun_dari->kode)) {
                        $dari = $row->akun_dari->name;
                    }
                    return $dari;
                })
                ->addColumn('tgl', function ($row) {
                    $tgl = date('Y-m-d', strtotime($row->tgl));
                    return tglIndo($tgl);
                })
                ->addColumn('jumlah', function ($row) {
                    $rupiah = "Rp " . number_format($row->jumlah, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('file', function ($row) {
                    $file = '';
                    if (!empty($row->file)) {
                        $file = '<a target="_blank" href="/image/transfer/' . $row->file . '">Lihat Berkas</a>';
                    }
                    return $file;
                })
                ->addColumn('persetujuan', function ($row) {
                    if ($row->persetujuan == 1) {
                        return '<span class="badge badge-info-lighten">Belum Verifikasi</span>';
                    } elseif ($row->persetujuan == 2) {
                        return '<span class="badge badge-success-lighten">Disetujui</span>';
                    } elseif ($row->persetujuan == 3) {
                        return '<span class="badge badge-danger-lighten">Ditolak</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    // if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO' or auth()->user()->level == 'CEO') {
                        // if ($row->persetujuan == 1) {
                            // $btn = $btn . ' <a href="javascript:void(0)" class="btn btn-outline-info" id="btnVerifikasi" data-id_transfer="' . $row->id . '" >Verifikasi</a>';
                        // }
                    // } elseif (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {
                        // if ($row->persetujuan == 1) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_transfer="' . $row->id . '" data-original-title="Edit" title="Ubah" class="editTransfer"> <i class="mdi mdi-square-edit-outline"></i></a>';

                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_transfer="' . $row->id . '" data-original-title="Delete" title="Hapus" class="deleteTransfer"> <i class="mdi mdi-delete"></i></a>';
                        // }
                    // }

                    return $btn;
                })
                ->rawColumns(['akun', 'select', 'dari', 'tgl', 'jumlah', 'deskripsi', 'persetujuan', 'file', 'action'])
                ->make(true);
        }

        $akun=KasBank::all();
        return view('kantor/transfer', compact('transfer', 'akun'));
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
        $jumlah = str_replace('.', '', $request->jumlah);
        $request->validate(
            [
                'nomor' => 'required',
                'dari' => 'required',
                'tgl' => 'required',
                'akun' => 'required',
                'jumlah' => 'required',
            ],
            [
                'required' => 'Data tidak boleh kosong...!!!'
            ]
        );

        if (empty($request->id_transfer)) {
            if ($request->file) {
                $request->validate([
                    'file' => 'required|mimes:png,jpg,jpeg,pdf,doc,docx,xls,xlsx|max:1024',
                ], [
                    'max' => 'Maksimal Kapasitas Berkas File 1MB',
                    'mimes' => 'Ekstensi Berkas File Diizinkan  .png / .jpg / .jpeg / .pdf / .doc / .docx / .xls / .xlsx'
                ]);
                $filename = time() . '.' . $request->file->extension();
                $request->file->move(public_path('image/transfer'), $filename);
            } else {
                $filename = '';
            }
        } else {
            if ($request->file) {
                $request->validate([
                    'file' => 'required|mimes:png,jpg,jpeg,pdf,doc,docx,xls,xlsx|max:1024',
                ], [
                    'max' => 'Maksimal Kapasitas Berkas File 1MB',
                    'mimes' => 'Ekstensi Berkas File Diizinkan  .png / .jpg / .jpeg / .pdf / .doc / .docx / .xls / .xlsx'
                ]);
                $filename = time() . '.' . $request->file->extension();
                $request->file->move(public_path('image/transfer'), $filename);
            } else {
                $cek = Transfer::find($request->id_transfer);
                $filename = $cek->file;
            }
        }

        $transfer=Transfer::updateOrCreate([
            'id' => $request->id_transfer
        ], [
            'nomor' => $request->nomor,
            'dari' => $request->dari,
            'tgl' => $request->tgl,
            'akun_id' => $request->akun,
            'jumlah' => $jumlah,
            'deskripsi' => $request->deskripsi,
            'file' => $filename,
            'website_id' => website()->id,
        ]);

        $data = [];
        $data['sumber'] = 'transfer';
        $data['sumber_id'] = $transfer->id;
        $data['nomor'] = $request->nomor;
        $data['tgl'] = $request->tgl;
        $data['website_id'] = website()->id;


        //save dari
        $data['debit'] = 0;
        $data['kredit'] = $jumlah;
        $data['akun_id'] = $request->dari;
        $this->kasRepo->store($data);


        //save akun
        $data['debit'] = $jumlah;
        $data['kredit'] = 0;
        $data['akun_id'] = $request->akun;
        $this->kasRepo->store($data);


        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Disimpan.',
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function show(Transfer $transfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transfer = Transfer::where('id',$id)->with('akun')->with('akun_dari')->first();
        return response()->json($transfer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transfer $transfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Transfer::find($id)->delete();
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

    public function verifikasiTransfer(Request $request)
    {
        if ($request->id == 1) {

            //Setuju
            $transfer = Transfer::find($request->verifikasiId);
            if ($transfer->persetujuan == 2) {
                $response = [
                    'success' => true,
                    'message' => 'Sudah Diverifikasi.',
                ];
                return response()->json($response, 200);
            } else {

                $response = DB::transaction(function () use ($request, $transfer) {
                    $transfer->update([
                        'persetujuan' => 2
                    ]);

                    $data = [];
                    $data['sumber'] = 'transfer';
                    $data['sumber_id'] = $transfer->id;
                    $data['nomor'] = $transfer->nomor;
                    $data['tgl'] = $transfer->tgl;
                    $data['website_id'] = $transfer->website_id;


                    //save dari
                    $data['debit'] = 0;
                    $data['kredit'] = $transfer->jumlah;
                    $data['akun_id'] = $transfer->dari;
                    $this->kasRepo->store($data);


                    //save akun
                    $data['debit'] = $transfer->jumlah;
                    $data['kredit'] = 0;
                    $data['akun_id'] = $transfer->akun_id;
                    $this->kasRepo->store($data);


                    $response = [
                        'success' => true,
                        'message' => 'Verifikasi Berhasil.',
                    ];
                    return $response;
                });

                return response()->json($response, 200);
            }
        } elseif ($request->id == 2) {
            //Tolak
            $transfer = Transfer::find($request->verifikasiId);
            $transfer->update([
                'persetujuan' => 3,
            ]);


            $response = [
                'success' => true,
                'message' => 'Telah Ditolak.',
            ];
            return response()->json($response, 200);
        }
    }


    public function bulkVerifikasi(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $jml_verifikasi = count($request->verifikasiId);
            $diverifikasi = 0;
            $gagal = 0;
            $response = [];
            foreach ($request->verifikasiId as $verifikasi) {
                $verifikasiId = dec($verifikasi);
                if ($request->aksi == 1) {
                    //Setuju
                    $transfer = Transfer::find($verifikasiId);
                    if ($transfer->persetujuan == 2) {
                        $diverifikasi++;
                    } else {


                        DB::transaction(function () use ($request, $transfer) {
                            $transfer->update([
                                'persetujuan' => 2,
                            ]);

                            $data = [];
                            $data['sumber'] = 'transfer';
                            $data['sumber_id'] = $transfer->id;
                            $data['nomor'] = $transfer->nomor;
                            $data['tgl'] = $transfer->tgl;
                            $data['website_id'] = $transfer->website_id;


                            //save dari
                            $data['debit'] = 0;
                            $data['kredit'] = $transfer->jumlah;
                            $data['akun_id'] = $transfer->dari;
                            $this->kasRepo->store($data);


                            //save akun
                            $data['debit'] = $transfer->jumlah;
                            $data['kredit'] = 0;
                            $data['akun_id'] = $transfer->akun_id;
                            $this->kasRepo->store($data);
                        });

                        $diverifikasi++;
                    }
                } elseif ($request->aksi == 2) {
                    //Tolak
                    $transfer = Transfer::find($verifikasiId);
                    $transfer->update([
                        'persetujuan' => 3,
                    ]);
                    $diverifikasi++;
                }
            }
            $response = ['success' => true, 'message' => 'Verifikasi Berhasil, Beberapa Data Gagal Diverifikasi'];

            if ($diverifikasi >= $jml_verifikasi) {
                $response = ['success' => true, 'message' => 'Verifikasi Transfer Berhasil'];
            }

            if ($gagal >= $jml_verifikasi) {
                $response = ['success' => false, 'message' => 'Verifikasi Transfer Gagal'];
            }

            return $response;
        });
        // return response

        return response()->json($result, 200);
    }




    public function uploadImage($request)
    {
        $filename = time() . '.' . $request->extension();
        $request->move(public_path('image/transfer'), $filename);
        $tumb = Image::make('image/transfer/' . $filename)->resize(40, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $patchTumb = 'image/transfer/tumb';
        if (!file_exists($patchTumb)) {
            mkdir($patchTumb, 755, true);
        }
        $tumb->save(public_path('image/transfer/tumb/' . $filename));
        return $filename;
    }
}
