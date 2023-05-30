<?php

namespace App\Http\Controllers;

use App\Repositori\HistoryStockRepositori;
use App\Repositori\StockRepositori;
use Illuminate\Http\Request;
use PDF;

class LaporanController extends Controller
{
    protected $historyStockRepo;
    protected $stockRepo;
    public function __construct()
    {
        $this->historyStockRepo = new HistoryStockRepositori();
        $this->stockRepo = new StockRepositori();
    }
   
    public function laporanHistoryStock(Request $request)
    {

        $data = $this->historyStockRepo->getWhere(null);
        if (!empty($request->from_date_history) and !empty($request->to_date_history)) {
            $data->whereBetween('created_at', array($request->from_date_history, $request->to_date_history));
        }

        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $data->where('website_id', website()->id);
        }

        if ($request->verifikasi<>'') {
            $data->where('verifikasi', $request->verifikasi);
        }

        if (!empty($request->website)) {
            $data->where('website_id', $request->website);
        }

        if (!empty($request->jenisHistory)) {
            $data->where('jenis', $request->jenisHistory);
        }
        $data = $data->latest()->get();
        $historyStock = $data;
        if (!empty($request->from_date_history) and !empty($request->to_date_history)) {
            $tanggal = $request->from_date_history . ' - ' . $request->to_date_history;
        } else {
            $tanggal = '';
        }

        if (!empty($request->jenisHistory)) {
            if ($request->jenisHistory == 1) {
                $jenis = 'Tambah';
            } elseif ($request->jenisHistory == 2) {
                $jenis = 'Jual';
            } elseif ($request->jenisHistory == 3) {
                $jenis = 'Pengurangan';
            }
        } else {
            $jenis = 'Semua History Stock';
        }

        if (isset($request->print)) {
            $export = '';
            $print = 'ya';
            $pdf = '';
        } elseif (isset($request->export)) {
            $export = 'ya';
            $print = '';
            $pdf = '';
        } elseif (isset($request->pdf)) {
            $print = '';
            $export = '';
            $pdf = PDF::loadview('laporan/print-laporan-history-stock', compact('historyStock', 'tanggal', 'jenis', 'export', 'print'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-history-stock'.date('Y-m-d').'.pdf');
        }

        return view("laporan/print-laporan-history-stock", compact('historyStock', 'tanggal', 'jenis', 'export', 'print'));
    }


    public function laporanStock(Request $request)
    {
        $export = '';
        $print = '';
        $pdf = '';

        $stock = $this->stockRepo->getWhere(null);
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $stock->where('website_id', website()->id);
        }

        if (!empty($request->website)) {
            $stock->where('website_id', $request->website);
        }
        $stock = $stock->latest()->get();

        if (isset($request->print)) {
            $print = 'ya';
        } elseif (isset($request->export)) {
            $export = 'ya';
        } elseif (isset($request->pdf)) {
            $pdf = PDF::loadview('laporan/print-laporan-stock', compact('stock', 'export', 'print'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-stock'.date('Y-m-d').'.pdf');
        }

        return view("laporan/print-laporan-stock", compact('stock', 'export', 'print'));
    }

}
