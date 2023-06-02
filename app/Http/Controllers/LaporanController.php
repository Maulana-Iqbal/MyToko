<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Transaksi;
use App\Models\Kas;
use App\Models\Aset;
use App\Models\Penerimaan;
use App\Models\Upah;
use App\Repositori\HistoryStockRepositori;
use App\Repositori\StockRepositori;
use Illuminate\Http\Request;
use DataTables;
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
    function view_laporan_transaksi(Request $request)
    {
        //
        $transaksi = Transaksi::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $transaksi->whereBetween('tgl_trans', array($request->from_date, $request->to_date));
        }

        if (!empty($request->status_trans)) {
            $transaksi->where('status_trans', $request->status_trans);
        }
        $transaksi = $transaksi->get();
        if ($request->ajax()) {
            return Datatables::of($transaksi)
                ->addIndexColumn()
                ->addColumn('totalModal', function ($row) {
                    $rupiah = "Rp " . number_format($row->totalModal, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('totalBiaya', function ($row) {
                    $rupiah = "Rp " . number_format($row->totalBiaya, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('pph', function ($row) {
                    $rupiah = "Rp -" . number_format($row->pph, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('subtotal', function ($row) {
                    $rupiah = "Rp " . number_format($row->subtotal, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('ppn', function ($row) {
                    $rupiah = "Rp " . number_format($row->ppn, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('total', function ($row) {
                    $rupiah = "Rp " . number_format($row->total, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    // if (session('level')==1){
                    // $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs editKategori"> <i class="mdi mdi-square-edit-outline"></i></a>';

                    // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteKategori"> <i class="mdi mdi-delete"></i></a>';
                    //}

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        //
        return view('laporan/view-laporan-transaksi');
    }

    public function print_laporan_transaksi(Request $request)
    {
        $transaksi = Transaksi::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $transaksi->whereBetween('tgl_trans', array($request->from_date, $request->to_date));
        }

        if (!empty($request->status_trans)) {
            $transaksi->where('status_trans', $request->status_trans);
        }

        if (!empty($request->status)) {
            if ($request->status == 'waiting' or $request->status == 1) {
                $status = 1;
            } elseif ($request->status == 'process' or $request->status == 2) {
                $status = 2;
            } elseif ($request->status == 'paid' or $request->status == 5) {
                $status = 5;
            } elseif ($request->status == 'sent' or $request->status == 6) {
                $status = 6;
            } elseif ($request->status == 'done' or $request->status == 3) {
                $status = 3;
            } elseif ($request->status == 'cancel' or $request->status == 4) {
                $status = 4;
            }
            $transaksi->where('status_trans', $status);
        }
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $transaksi = $transaksi->where('website_id', website()->id);
        }
        if (!empty($request->website)) {
            $transaksi = $transaksi->where('website_id', $request->website);
        }

        $transaksi = $transaksi->get();


        if (!empty($request->from_date) and !empty($request->to_date)) {
            $tanggal = $request->from_date . ' - ' . $request->to_date;
        } else {
            $tanggal = '';
        }
        if (!empty($request->status_trans)) {
            $status = $request->status_trans;
        }

        if (!empty($status)) {
            if ($status == 'waiting' or $status == 1) {
                $status_trans = 'Menunggu Verifikasi';
            } elseif ($status == 'process' or $status == 2) {
                $status_trans = 'Proses';
            } elseif ($status == 'paid' or $status == 5) {
                $status_trans = 'Dibayar';
            } elseif ($status == 'sent' or $status == 6) {
                $status_trans = 'Dikirim';
            } elseif ($status == 'done' or $status == 3) {
                $status_trans = 'Selesai';
            } elseif ($status == 'cancel' or $status == 4) {
                $status_trans = 'Dibatalkan';
            }
        } else {
            $status_trans = 'Semua Transaksi';
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
            $pdf = PDF::loadview('laporan/print-laporan-transaksi', compact('transaksi', 'tanggal', 'status_trans', 'export', 'print'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-transaksi'.date('Y-m-d').'.pdf');
        }


        return view("laporan/print-laporan-transaksi", compact('transaksi', 'tanggal', 'status_trans', 'export', 'print'));
    }


    public function print_laporan_pengeluaran(Request $request)
    {
        $pengeluaran = Pengeluaran::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $pengeluaran->whereBetween('tgl', array($request->from_date, $request->to_date));
        }

        if (!empty($request->jenis)) {
            $pengeluaran->where('dari', $request->jenis);
        }

        if (!empty($request->persetujuan)) {
            $pengeluaran->where('persetujuan', $request->persetujuan);
        }


        if(!empty($request->bulan) and !empty($request->tahun)){
            $pengeluaran->whereMonth('tgl',$request->bulan);
            $pengeluaran->whereYear('tgl',$request->tahun);
        }elseif(empty($request->bulan) and !empty($request->tahun)){
            $pengeluaran->whereYear('tgl',$request->tahun);
        }


        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $pengeluaran = $pengeluaran->where('website_id', website()->id);
        }
        if (!empty($request->website)) {
            $pengeluaran = $pengeluaran->where('website_id', $request->website);
        }

        $pengeluaran = $pengeluaran->get();


        if (!empty($request->from_date) and !empty($request->to_date)) {
            $tanggal = $request->from_date . ' - ' . $request->to_date;
        } else {
            $tanggal = '';
        }
        if (!empty($request->persetujuan)) {
            if ($request->persetujuan == 1) {
                $persetujuan = 'Belum Verifikasi';
            } elseif ($request->persetujuan == 2) {
                $persetujuan = 'Disetujui';
            } elseif ($request->persetujuan == 3) {
                $persetujuan = 'Ditolak';
            }
        } else {
            $persetujuan = 'Semua Pengeluaran';
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
            $pdf = true;
            $print = '';
            $export = '';
            $pdf = PDF::loadview('laporan/print-laporan-pengeluaran', compact('pengeluaran', 'tanggal', 'persetujuan', 'export', 'print', 'pdf'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-pengeluaran'.date('Y-m-d').'.pdf');
        }


        return view("laporan/print-laporan-pengeluaran", compact('pengeluaran', 'tanggal', 'persetujuan', 'export', 'print'));
    }


    public function print_laporan_penerimaan(Request $request)
    {
        $penerimaan = Penerimaan::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $penerimaan->whereBetween('tgl', array($request->from_date, $request->to_date));
        }

        if (!empty($request->jenis)) {
            $penerimaan->where('dari', $request->jenis);
        }

        if (!empty($request->persetujuan)) {
            $penerimaan->where('persetujuan', $request->persetujuan);
        }


        if(!empty($request->bulan) and !empty($request->tahun)){
            $penerimaan->whereMonth('tgl',$request->bulan);
            $penerimaan->whereYear('tgl',$request->tahun);
        }elseif(empty($request->bulan) and !empty($request->tahun)){
            $penerimaan->whereYear('tgl',$request->tahun);
        }


        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $penerimaan = $penerimaan->where('website_id', website()->id);
        }
        if (!empty($request->website)) {
            $penerimaan = $penerimaan->where('website_id', $request->website);
        }

        $penerimaan = $penerimaan->get();


        if (!empty($request->from_date) and !empty($request->to_date)) {
            $tanggal = $request->from_date . ' - ' . $request->to_date;
        } else {
            $tanggal = '';
        }
        if (!empty($request->persetujuan)) {
            if ($request->persetujuan == 1) {
                $persetujuan = 'Belum Verifikasi';
            } elseif ($request->persetujuan == 2) {
                $persetujuan = 'Disetujui';
            } elseif ($request->persetujuan == 3) {
                $persetujuan = 'Ditolak';
            }
        } else {
            $persetujuan = 'Semua Penerimaan';
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
            $pdf = true;
            $print = '';
            $export = '';
            $pdf = PDF::loadview('laporan/print-laporan-penerimaan', compact('penerimaan', 'tanggal', 'persetujuan', 'export', 'print', 'pdf'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-penerimaan'.date('Y-m-d').'.pdf');
        }


        return view("laporan/print-laporan-penerimaan", compact('penerimaan', 'tanggal', 'persetujuan', 'export', 'print'));
    }



    public function print_laporan_kas(Request $request)
    {
        $kas = Kas::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $kas->whereBetween('tgl', array($request->from_date, $request->to_date));
        }

        if (!empty($request->jenis)) {
            $kas->where('jenis', $request->jenis);
        }

        if (!empty($request->rekening_id)) {
            $kas->where('rekening_id', $request->rekening_id);
        }

        if (!empty($request->mutasi)) {
            if ($request->mutasi == 1) {
                $mutasi = 'Debit';
                $kas->where('debit', '>', 0);
            } elseif ($request->mutasi == 2) {
                $mutasi = 'Kredit';
                $kas->where('kredit', '>', 0);
            }
        } else {
            $mutasi = '';
        }

        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $kas->where('website_id', website()->id);
        }

        if (!empty($request->website)) {
            $kas->where('website_id', $request->website);
        }

        if(!empty($request->bulan) and !empty($request->tahun)){
            $kas->whereMonth('tgl',$request->bulan);
            $kas->whereYear('tgl',$request->tahun);
        }elseif(empty($request->bulan) and !empty($request->tahun)){
            $kas->whereYear('tgl',$request->tahun);
        }


        $kas->orderBy('id', 'DESC');
        $kas = $kas->get();


        if (!empty($request->from_date) and !empty($request->to_date)) {
            $tanggal = $request->from_date . ' - ' . $request->to_date;
        } else {
            $tanggal = '';
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
            $pdf = PDF::loadview('laporan/print-laporan-kas', compact('kas', 'tanggal',  'mutasi', 'export', 'print'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-kas'.date('Y-m-d').'.pdf');
        }

        return view("laporan/print-laporan-kas", compact('kas', 'tanggal', 'mutasi', 'export', 'print'));
    }




    function view_laporan_aset(Request $request)
    {
        //
        $aset = Aset::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $aset->whereBetween('created_at', array($request->from_date, $request->to_date));
        }

        if (!empty($request->kondisi)) {
            $aset->where('kondisi', $request->kondisi);
        }
        $aset = $aset->get();
        if ($request->ajax()) {
            return Datatables::of($aset)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        //
        return view('laporan/view-laporan-aset');
    }

    public function print_laporan_aset(Request $request)
    {
        $aset = Aset::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $aset->whereBetween('created_at', array($request->from_date, $request->to_date));
        }

        $kondisi = $request->kondisi;
        if (!empty($kondisi)) {
            $aset->where('kondisi', $kondisi);
        } else {
            $kondisi = 'Semua Aset';
        }
        $aset = $aset->get();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $tanggal = $request->from_date . ' - ' . $request->to_date;
        } else {
            $tanggal = '';
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
            $pdf = PDF::loadview('laporan/print-laporan-aset', compact('aset', 'tanggal', 'kondisi', 'export', 'print'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-aset'.date('Y-m-d').'.pdf');
        }


        return view("laporan/print-laporan-aset", compact('aset', 'tanggal', 'kondisi', 'export', 'print'));
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

    function laporanPajak(Request $request)
    {
        //
        $transaksi = Transaksi::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $transaksi->whereBetween('tgl_trans', array($request->from_date, $request->to_date));
        }

        if (!empty($request->jenis_pajak)) {
            $transaksi->where($request->jenis_pajak, '<>', '');
        }


        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $transaksi->where('website_id', website()->id);
        }

        if (!empty($request->website)) {
            $transaksi->where('website_id', $request->website);
        }

        $transaksi->where('status_trans', 3);
        $transaksi = $transaksi->get();
        if ($request->ajax()) {
            return Datatables::of($transaksi)
                ->addIndexColumn()
                ->addColumn('perusahaan', function ($row) {
                    $perusahaan = '';
                    if ($row->pelanggan <> null) {
                        $perusahaan = $row->pelanggan->perusahaan;
                    }
                    return $perusahaan;
                })
                ->addColumn('totalBiaya', function ($row) {
                    $rupiah = "Rp " . number_format($row->totalBiaya, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('pph', function ($row) {
                    $rupiah = "Rp -" . number_format($row->pph, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('subtotal', function ($row) {
                    $rupiah = "Rp " . number_format($row->subtotal, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('ppn', function ($row) {
                    $rupiah = "Rp " . number_format($row->ppn, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('filePpn', function ($row) {
                    $file='';
                    if(!empty($row->pajak)){
                        $pajak=$row->pajak->where('jenis','ppn')->first();
                        if($pajak<>null){
                        $file="<a href='/image/pajak/" . $pajak->file . "'><img width='80px' src='/image/pajak/" . $pajak->file . "'/></a>";
                        }
                    }
                    return $file;
                })
                ->addColumn('file', function ($row) {
                    $file='';
                    if(!empty($row->pajak)){
                        $pajak=$row->pajak->where('jenis','pph')->first();
                        if($pajak<>null){
                        $file="<a href='/image/pajak/" . $pajak->file . "'><img width='80px' src='/image/pajak/" . $pajak->file . "'/></a>";
                        }
                    }
                    return $file;
                })
                // ->addColumn('total', function ($row) {
                //     $rupiah = "Rp " . number_format($row->total, 0, ',', '.');
                //     return $rupiah;
                // })
                ->addColumn('tgl_trans', function ($row) {
                    return tglIndo($row->tgl_trans);
                })
                ->rawColumns(['perusahaan','filePpn','file','tgl_trans'])
                ->make(true);
        }
        //
        return view('laporan/view-laporan-pajak');
    }


    function print_laporan_pajak(Request $request)
    {
        //
        $transaksi = Transaksi::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $transaksi->whereBetween('tgl_trans', array($request->from_date, $request->to_date));
        }

        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $transaksi->where('website_id', website()->id);
        }

        if (!empty($request->jenis_pajak)) {
            $transaksi->where($request->jenis_pajak, '<>', '');
        }


        if(!empty($request->bulan) and !empty($request->tahun)){
            $transaksi->whereMonth('tgl_trans',$request->bulan);
            $transaksi->whereYear('tgl_trans',$request->tahun);
        }elseif(empty($request->bulan) and !empty($request->tahun)){
            $transaksi->whereYear('tgl_trans',$request->tahun);
        }

        if (!empty($request->website)) {
            $transaksi->where('website_id', $request->website);
        }

        $transaksi->where('status_trans', 3);
        $transaksi = $transaksi->get();

        if (!empty($request->from_date) and !empty($request->to_date)) {
            $tanggal = $request->from_date . ' - ' . $request->to_date;
        } else {
            $tanggal = '';
        }

        $view = 'laporan/print-laporan-pajak';
        $jenis_pajak='';
        if (!empty($request->jenis_pajak)) {
            if ($request->jenis_pajak == 'ppn') {
                $view = "laporan/print-laporan-pajak-ppn";
                $jenis_pajak='PPN';
            } elseif ($request->jenis_pajak == 'pph') {
                $jenis_pajak='PPH';
                $view = "laporan/print-laporan-pajak-pph";
            }
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
            $pdf = PDF::loadview($view, compact('transaksi', 'tanggal','jenis_pajak', 'export', 'print'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-pajak'.date('Y-m-d').'.pdf');
        }

        return view($view, compact('transaksi', 'tanggal', 'jenis_pajak', 'export', 'print'));
    }


    public function print_laporan_upah(Request $request)
    {
        $upah = Upah::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $upah->whereBetween('tanggal', array($request->from_date, $request->to_date));
        }

        if (!empty($request->pekerjaan)) {
            $upah->where('pekerjaan_id', $request->pekerjaan);
        }

        if (!empty($request->user)) {
            $upah->where('user_id', $request->user);
        }

        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $upah->where('website_id', website()->id);
        }

        if (!empty($request->website)) {
            $upah->where('website_id', $request->website);
        }

        if(!empty($request->bulan) and !empty($request->tahun)){
            $upah->whereMonth('tanggal',$request->bulan);
            $upah->whereYear('tanggal',$request->tahun);
        }elseif(empty($request->bulan) and !empty($request->tahun)){
            $upah->whereYear('tanggal',$request->tahun);
        }


        $upah->orderBy('id', 'DESC');
        $upah = $upah->get();

        $pegawai='';
        $pekerjaan='';
        $website='';

        if (!empty($request->from_date) and !empty($request->to_date)) {
            $tanggal = $request->from_date . ' - ' . $request->to_date;
        } else {
            $tanggal = '';
        }

        if (!empty($request->pekerjaan)) {
            $pekerjaan=$upah->where('pekerjaan_id',$request->pekerjaan)->first();
            $pekerjaan=$pekerjaan->pekerjaan->name;
        }

        if (!empty($request->user)) {
            $pegawai=$upah->where('user_id',$request->user)->first();
            $pegawai=$pegawai->user->name;
        }

        if(!empty($request->website)){
            $website=$request->website;
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
            $pdf = PDF::loadview('laporan/print-laporan-upah', compact('upah', 'tanggal', 'pekerjaan', 'pegawai', 'website', 'export', 'print'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-upah'.date('Y-m-d').'.pdf');
        }

        return view("laporan/print-laporan-upah", compact('upah', 'tanggal', 'pekerjaan', 'pegawai','website', 'export', 'print'));
    }
}
