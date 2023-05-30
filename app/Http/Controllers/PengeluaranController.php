<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\KasBank;
use App\Models\Pengeluaran;
use App\Repositori\KasRepositori;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DataTables;
use Image;

class PengeluaranController extends Controller
{

    protected $kasRepo;
    public function __construct()
    {
        $this->kasRepo = new KasRepositori();
    }


    public function index(Request $request)
    {
        $q = null;
        $pengeluaran = Pengeluaran::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $pengeluaran->whereBetween('tgl', array($request->from_date, $request->to_date));
        }

        if (!empty($request->persetujuan)) {
            $pengeluaran->where('persetujuan', $request->persetujuan);
        }

        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $pengeluaran = $pengeluaran->where('website_id', website()->id);
            $q = ['website_id' => website()->id];
        }
        if (!empty($request->website)) {
            $pengeluaran = $pengeluaran->where('website_id', $request->website);
            $q = ['website_id' => $request->website];
        }
        $pengeluaran = $pengeluaran->latest();
        $pengeluaran = $pengeluaran->get();
        if ($request->ajax()) {
            return Datatables::of($pengeluaran)
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
                ->addColumn('biaya', function ($row) {
                    $rupiah = "Rp " . number_format($row->biaya, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('file', function ($row) {
                    $file = '';
                    if (!empty($row->file)) {
                        $file = '<a target="_blank" href="/image/pengeluaran/' . $row->file . '">Lihat Berkas</a>';
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
                        if ($row->persetujuan == 1) {
                            $btn = $btn . ' <a href="javascript:void(0)" class="btn btn-outline-info" id="btnVerifikasi" data-id_pengeluaran="' . $row->id . '" >Verifikasi</a>';
                        }
                    // } elseif (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {
                        if ($row->persetujuan == 1) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pengeluaran="' . $row->id . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editPengeluaran"> <i class="mdi mdi-square-edit-outline"></i></a>';

                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pengeluaran="' . $row->id . '" data-original-title="Delete" class="btn btn-outline-danger btn-xs deletePengeluaran"> <i class="mdi mdi-delete"></i></a>';
                        }
                    // }

                    return $btn;
                })
                ->rawColumns(['akun', 'select', 'dari', 'tgl', 'biaya', 'deskripsi', 'persetujuan', 'file', 'action'])
                ->make(true);
        }

        $kasBank = KasBank::orderBy('akun_id', 'asc')->get();
        return view('kantor/pengeluaran', compact('pengeluaran', 'kasBank'));
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
                'dari' => 'required',
                'tgl' => 'required',
                // 'akun' => 'required',
                // 'biaya' => 'required',
            ],
            [
                'required' => 'Data Belum Lengkap'
            ]
        );

        if (empty($request->id_pengeluaran)) {
            if ($request->file) {
                $request->validate([
                    'file' => 'required|mimes:png,jpg,jpeg,pdf,doc,docx,xls,xlsx|max:1024',
                ], [
                    'max' => 'Maksimal Kapasitas Berkas File 1MB',
                    'mimes' => 'Ekstensi Berkas File Diizinkan  .png / .jpg / .jpeg / .pdf / .doc / .docx / .xls / .xlsx'
                ]);
                $filename = time() . '.' . $request->file->extension();
                $request->file->move(public_path('image/pengeluaran'), $filename);
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
                $request->file->move(public_path('image/pengeluaran'), $filename);
            } else {
                $cek = Pengeluaran::find($request->id_pengeluaran);
                $filename = $cek->file;
            }
        }

        foreach ($request->idAkun as $index => $idAkun) {

            $biaya = str_replace('.', '', $request->biaya[$index]);

            Pengeluaran::updateOrCreate([
                'id' => $request->id_pengeluaran
            ], [
                'nomor' => $request->nomor,
                'dari' => $request->dari,
                'tgl' => $request->tgl,
                'akun_id' => $request->idAkun[$index],
                'biaya' => $biaya,
                'deskripsi' => $request->deskripsi,
                'persetujuan' => 1,
                'file' => $filename,
                'website_id' => website()->id,
            ]);
        }

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
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function show(Pengeluaran $pengeluaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pengeluaran = Pengeluaran::find($id);
        return response()->json($pengeluaran);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Pengeluaran::find($id)->delete();
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

    public function verifikasiPengeluaran(Request $request)
    {
        if ($request->id == 1) {

            //Setuju
            $pengeluaran = Pengeluaran::find($request->verifikasiId);
            if ($pengeluaran->persetujuan == 2) {
                $response = [
                    'success' => true,
                    'message' => 'Sudah Diverifikasi.',
                ];
                return response()->json($response, 200);
            } else {

                $response = DB::transaction(function () use ($request, $pengeluaran) {
                    $pengeluaran->update([
                        'persetujuan' => 2
                    ]);

                    $data = [];
                    $data['sumber'] = 'pengeluaran';
                    $data['sumber_id'] = $pengeluaran->id;
                    $data['nomor'] = $pengeluaran->nomor;
                    $data['tgl'] = $pengeluaran->tgl;
                    $data['website_id'] = $pengeluaran->website_id;


                    //save dari
                    $data['debit'] = 0;
                    $data['kredit'] = $pengeluaran->biaya;
                    $data['akun_id'] = $pengeluaran->dari;
                    $this->kasRepo->store($data);


                    //save akun
                    $data['debit'] = $pengeluaran->biaya;
                    $data['kredit'] = 0;
                    $data['akun_id'] = $pengeluaran->akun_id;
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
            $pengeluaran = Pengeluaran::find($request->verifikasiId);
            $pengeluaran->update([
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
                    $pengeluaran = Pengeluaran::find($verifikasiId);
                    if ($pengeluaran->persetujuan == 2) {
                        $diverifikasi++;
                    } else {


                        DB::transaction(function () use ($request, $pengeluaran) {
                            $pengeluaran->update([
                                'persetujuan' => 2,
                            ]);

                            $data = [];
                            $data['sumber'] = 'pengeluaran';
                            $data['sumber_id'] = $pengeluaran->id;
                            $data['nomor'] = $pengeluaran->nomor;
                            $data['tgl'] = $pengeluaran->tgl;
                            $data['website_id'] = $pengeluaran->website_id;


                            //save dari
                            $data['debit'] = 0;
                            $data['kredit'] = $pengeluaran->biaya;
                            $data['akun_id'] = $pengeluaran->dari;
                            $this->kasRepo->store($data);


                            //save akun
                            $data['debit'] = $pengeluaran->biaya;
                            $data['kredit'] = 0;
                            $data['akun_id'] = $pengeluaran->akun_id;
                            $this->kasRepo->store($data);
                        });

                        $diverifikasi++;
                    }
                } elseif ($request->aksi == 2) {
                    //Tolak
                    $pengeluaran = Pengeluaran::find($verifikasiId);
                    $pengeluaran->update([
                        'persetujuan' => 3,
                    ]);
                    $diverifikasi++;
                }
            }
            $response = ['success' => true, 'message' => 'Verifikasi Berhasil, Beberapa Data Gagal Diverifikasi'];

            if ($diverifikasi >= $jml_verifikasi) {
                $response = ['success' => true, 'message' => 'Verifikasi Pengeluaran Berhasil'];
            }

            if ($gagal >= $jml_verifikasi) {
                $response = ['success' => false, 'message' => 'Verifikasi Pengeluaran Gagal'];
            }

            return $response;
        });
        // return response

        return response()->json($result, 200);
    }




    public function uploadImage($request)
    {
        $filename = time() . '.' . $request->extension();
        $request->move(public_path('image/pengeluaran'), $filename);
        $tumb = Image::make('image/pengeluaran/' . $filename)->resize(40, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $patchTumb = 'image/pengeluaran/tumb';
        if (!file_exists($patchTumb)) {
            mkdir($patchTumb, 755, true);
        }
        $tumb->save(public_path('image/pengeluaran/tumb/' . $filename));
        return $filename;
    }
}
