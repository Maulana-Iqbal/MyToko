<?php

namespace App\Http\Controllers;

use App\Models\HistoryStock;
use App\Models\Notifikasi;
use App\Models\User;
use App\Repositori\HistoryStockRepositori;
use App\Repositori\ProdukRepositori;
use App\Repositori\StockRepositori;
use Illuminate\Http\Request;
use App\Services\HistoryStockService;
use Illuminate\Support\Facades\DB;
use PDF;

class HistoryStockController extends Controller
{
    protected $stockRepo;
    protected $historyStockRepo;
    protected $historyStockService;
    protected $produkRepo;
    public function __construct()
    {
        $this->stockRepo = new StockRepositori();
        $this->historyStockRepo = new HistoryStockRepositori();
        $this->historyStockService = new HistoryStockService();
        $this->produkRepo = new ProdukRepositori();
        $this->middleware('permission:mutasi|mutasi-list|mutasi-create|mutasi-delete|mutasi-verifikasi', ['only' => ['index', 'historyStock']]);
        $this->middleware('permission:mutasi|mutasi-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:mutasi|mutasi-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:mutasi|mutasi-verifikasi', ['only' => ['verifikasiPengurangan']]);
    }

    public function index(Request $request)
    {
        return view('stock.dataHistoryStock');
    }

    public function historyStock(Request $request)
    {
        $data = $this->historyStockRepo->getWhere(null);
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $data->whereBetween('created_at', array($request->from_date, $request->to_date));
        }

        if ($request->jenisMutasi) {
            $data->whereIn('jenis', $request->jenisMutasi);
        }
        if (!empty($request->produk_id)) {
            $data->where('produk_id', $request->produk_id);
        }
        if ($request->produkMutasiFilter) {
            $data->whereIn('produk_id', $request->produkMutasiFilter);
        }
        if ($request->gudangMutasiFilter) {
            $data->whereIn('gudang_id', $request->gudangMutasiFilter);
        }
        if (auth()->user()->hasPermissionTo('show-all')) {
            if (!empty($request->website)) {
                $data->where('website_id', $request->website);
            }
        } else {
            $data->where('website_id', website()->id);
        }
        $data = $data->orderBy('updated_at', 'DESC')->orderBy('created_at', 'DESC')->get();
        if ($request->ajax()) {
            return $this->historyStockService->getDatatable($data);
        }

        if (isset($request->print) or isset($request->export) or isset($request->pdf)) {
            if (!empty($request->from_date) and !empty($request->to_date)) {
                $tanggal = $request->from_date . ' - ' . $request->to_date;
            } else {
                $tanggal = '';
            }
            $jenis = '';
            if (!empty($request->jenisMutasi)) {
                if ($request->jenisMutasi == 1) {
                    $jenis = 'Tambah';
                } elseif ($request->jenisMutasi == 2) {
                    $jenis = 'Jual';
                } elseif ($request->jenisMutasi == 3) {
                    $jenis = 'Pengurangan';
                } elseif ($request->jenisMutasi == 4) {
                    $jenis = 'Ubah Harga';
                } elseif ($request->jenisMutasi == 5) {
                    $jenis = 'Transfer Dari ';
                } elseif ($request->jenisMutasi == 6) {
                    $jenis = 'Transfer Ke';
                } elseif ($request->jenisMutasi == 3) {
                    $jenis = 'Transfer Dari & Ke';
                }
            } else {
                $jenis = 'Semua Mutasi Stock';
            }
            $historyStock = $data;
            $print = '';
            $export = '';
            $pdf = '';
            if (isset($request->print)) {
                $print = 'ya';
            } elseif (isset($request->export)) {
                $export = 'ya';
            } elseif (isset($request->pdf)) {
                $pdf = PDF::loadview('laporan/laporan-mutasi-stock', compact('historyStock', 'tanggal', 'jenis', 'export', 'print'))->setPaper('a4', 'landscape');
                return $pdf->download('laporan-mutasi-stock-' . date('Y-m-d') . '.pdf');
            }

            return view("laporan/laporan-mutasi-stock", compact('historyStock', 'tanggal', 'jenis', 'export', 'print'));
        }
        return view('stock/historyStock');
    }



    public function verifikasiPengurangan(Request $request)
    {
        return $this->historyStockService->verifikasiPengurangan($request);
    }
}
