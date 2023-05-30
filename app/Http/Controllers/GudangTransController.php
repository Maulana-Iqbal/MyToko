<?php

namespace App\Http\Controllers;

use App\Repositori\GudangRepositori;
use App\Repositori\GudangTransRepositori;
use Illuminate\Http\Request;
use DataTables;
use PDF;

class GudangTransController extends Controller
{
    protected $gudangTransRepo;
    public function __construct()
    {
        $this->gudangTransRepo = new GudangTransRepositori();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['gudang_trans.website_id' => website()->id];
        }
        $data = $this->gudangTransRepo->getWhere($q)->select('gudang_trans.produk_id', 'gudang_trans.gudang_id', 'gudang_trans.jumlah', 'produk.kode_produk', 'produk.nama_produk', 'produk.slug', 'produk.kategori_id', 'produk.satuan_id', 'produk.website_id')->join('produk', 'produk.id', 'gudang_trans.produk_id')->orderBy('gudang_trans.updated_at', 'desc')->orderBy('gudang_trans.created_at', 'DESC');
        if ($request->produk) {
            $data->whereIn('gudang_trans.produk_id', $request->produk);
        }
        if ($request->gudang) {
            $data->whereIn('gudang_trans.gudang_id', $request->gudang);
        }
        if ($request->satuan) {
            $data->whereIn('produk.satuan_id', $request->satuan);
        }
        if ($request->kategori) {
            $data->whereIn('produk.kategori_id', $request->kategori);
        }
        if ($request->jumlah) {
            $data->where('gudang_trans.jumlah', '<=', $request->jumlah);
        }

        if (auth()->user()->hasPermissionTo('show-all')) {
            if ($request->website) {
                $data->whereIn('gudang_trans.website_id', $request->website);
            }
        }

        $data = $data->get();

        if ($request->print or $request->export or $request->pdf) {
            $print = '';
            $export = '';
            $stock = $data;
            if (isset($request->print)) {
                $print = 'ya';
            } elseif (isset($request->export)) {
                $export = 'ya';
            } elseif (isset($request->pdf)) {
                $pdf = PDF::loadview('laporan/laporan-stock-gudang', compact('stock', 'export', 'print'))->setPaper('a4', 'landscape');
                return $pdf->download('laporan-stock-gudang' . date('Y-m-d') . '.pdf');
            }

            return view("laporan/laporan-stock-gudang", compact('stock', 'export', 'print'));
        }

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('gudang', function ($row) {
                    return "<b>" . $row->gudang->nama . "</b>";
                })
                ->addColumn('kode_produk', function ($row) {
                    return $row->produk->kode_produk;
                })
                ->addColumn('nama_produk', function ($row) {
                    return '<b><a href="' . url($row->produk->website->username . '/produk/detail' . '/' . $row->produk->slug) . '" class="text-body">' . $row->produk->nama_produk . '</a></b>';
                })
                ->addColumn('nama_kategori', function ($row) {
                    return $row->produk->kategori->nama_kategori;
                })
                ->addColumn('harga', function ($row) {
                    $harga = (float)$row->produk->stock->harga;
                    $rupiah = "Rp " . number_format($harga, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('harga_jual', function ($row) {
                    $harga = (float)$row->produk->stock->harga_jual;
                    $rupiah = "Rp " . number_format($harga, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('harga_grosir', function ($row) {
                    $harga = (float)$row->produk->stock->harga_grosir;
                    $rupiah = "Rp " . number_format($harga, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('jumlah', function ($row) {
                    return $row->jumlah;
                })
                ->addColumn('satuan', function ($row) {
                    return $row->produk->satuan->name;
                })
                ->addColumn('profit', function ($row) {
                    $stock = $row->jumlah;
                    $show = 0;
                    if ($stock) {
                        $profitEceran=0;
                        $profitGrosir=0;
                        $tModal = $stock * $row->produk->stock->harga;
                        $tEceran = $stock * $row->produk->stock->harga_jual;
                        $tGrosir = $stock * $row->produk->stock->harga_grosir;
                        if($tEceran>0 and $tModal>0){
                        $profitEceran = (($tEceran - $tModal) / $tEceran) * 100;
                        }
                        if($tGrosir>0 and $tModal>0){
                        $profitGrosir = (($tGrosir - $tModal) / $tGrosir) * 100;
                        }
                        $show = "Eceran : <span class='text-primary'>" . number_format($profitEceran,0,',','') . "%</span><br>Grosir : <span class='text-success'>" . number_format($profitGrosir,0,',','') . "%</span>";
                    }
                    return $show;
                })

                ->addColumn('laba', function ($row) {
                    $stock = $row->jumlah;
                    $show = 0;
                    if ($stock) {
                        $tModal = $stock * $row->produk->stock->harga;
                        $tEceran = $stock * $row->produk->stock->harga_jual;
                        $tGrosir = $stock * $row->produk->stock->harga_grosir;
                        $labaEceran = $tEceran - $tModal;
                        $labaGrosir = $tGrosir - $tModal;
                        $show = "Eceran : <span class='text-primary'>" . uang($labaEceran) . "</span><br>Grosir : <span class='text-success'>" . uang($labaGrosir) . "</span>";
                    }
                    return $show;
                })

                // ->addColumn('tanggal', function ($row) {
                //     return date('d-m-Y', strtotime($row->created_at));
                // })

                ->rawColumns(['gudang', 'kode_produk', 'nama_produk', 'profit','laba','nama_kategori', 'jumlah', 'satuan'])
                ->make(true);
        }
        return view('gudangTrans.index');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
