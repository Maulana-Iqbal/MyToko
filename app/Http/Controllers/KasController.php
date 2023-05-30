<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Kas;
use App\Models\KategoriAkun;
use App\Models\Rekening;
use Illuminate\Http\Request;
use DataTables;

class KasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = null;
        $kas = Kas::latest();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $kas->whereBetween('tgl', array($request->from_date, $request->to_date));
        }

        if (!empty($request->akun)) {
            $kas->where('akun_id', $request->akun);
        }

        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $kas->where('website_id', website()->id);
            $q = ['website_id' => website()->id];
        }

        if (!empty($request->website)) {
            $kas->where('website_id', $request->website);
            $q = ['website_id' => $request->website];
        }

        $kas->get();
        if ($request->ajax()) {
            return Datatables::of($kas)
                ->addIndexColumn()
                ->addColumn('tgl', function ($row) {
                    $tgl = date('Y-m-d', strtotime($row->tgl));
                    return tglIndo($tgl);
                })
                ->addColumn('akun', function ($row) {
                    $akun = '';
                    if ($row->akun <> null) {
                        $akun = $row->akun->name;
                    }
                    return $akun;
                })
                ->addColumn('kredit', function ($row) {
                    $rupiah = "Rp " . number_format($row->kredit, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('debit', function ($row) {
                    $rupiah = "Rp " . number_format($row->debit, 0, ',', '.');
                    return $rupiah;
                })
                ->rawColumns(['akun', 'debit', 'kredit', 'tgl'])
                ->make(true);
        }
        $rekening = Rekening::where($q)->orderBy('nama_bank', 'ASC')->get();
        return view('kantor/kas', compact('kas', 'rekening'));
    }

    public function store(Request $request)
    {
        $response = [
            'success' => true,
            'message' => 'Berhasil Disimpan.',
        ];

        return response()->json($response, 200);
    }



}
