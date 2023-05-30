<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Pelanggan;
use App\Models\Kas;
use App\Models\Pemasok;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use App\Models\Sales;
use App\Models\Stock;
use App\Models\StockOrder;
use App\Models\User;
use App\Models\Website;
use App\Repositori\StockOrderRepositori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $stockOrderRepo;
    public function __construct()
    {
        $this->stockOrderRepo = new StockOrderRepositori();
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $website = $request->website;
        $year = $request->tahun;
        $month = $request->bulan;
       
        $jenis = jenisSelesai($month, $year,$website);
        $jenisCount = jenisCountSelesai($month, $year,$website);
        $topSellProduk = topSellProduk($month, $year,$website);
        $stockProdukMenipis = stockProdukMenipis($website);
        $totalPengguna = totalPengguna($month,$year,$website);
        $totalCustomer = totalCustomer($month,$year,$website);
        $totalSales = totalSales($month,$year,$website);
        $totalSupplier = totalSupplier($month,$year,$website);
        $totalToko = totalToko();

        $penjualan = getArrayTotal($month, $year, 'penjualan', $website);
        $pembelian = getArrayTotal($month, $year, 'pembelian', $website);
        $transfer = getArrayTotal($month, $year, 'stocktransfer', $website);
        $pengurangan = getArrayTotal($month, $year, 'pengurangan', $website);
        return view('home_pos', compact('month', 'year','website', 'transfer', 'pengurangan', 'pembelian', 'penjualan', 'jenis', 'jenisCount', 'topSellProduk', 'stockProdukMenipis', 'totalCustomer', 'totalPengguna', 'totalSales', 'totalSupplier', 'totalToko'));
    }

    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index2(Request $request)
    {
        $month = '';
        $year = '';
        $website = '';
        $kasKecilDebit = '';
        $kasKecilKredit = '';
        $kasBesarDebit = '';
        $kasBesarKredit = '';

        $pelanggan = Pelanggan::selectRaw('id');
        $trxSuccess = Transaksi::selectRaw('id')->where('status_trans', '3');
        $trxCancel = Transaksi::selectRaw('id')->where('status_trans', '4');
        $arrayStatus = array(1, 2, 5, 6);
        $trxOnProses = Transaksi::selectRaw('id')->whereIn('status_trans', $arrayStatus);
        $trxAll = Transaksi::selectRaw('id');
        $pendapatan = 0;
        // $pendapatan = Transaksi::whereMonth('updated_at', $month)->whereYear('tgl_trans', $year)->where('status_trans', '3')->sum('total');
        $pengeluaranWaiting = Pengeluaran::selectRaw('biaya')->where('persetujuan', '1');
        $pengeluaranAccept = Pengeluaran::selectRaw('biaya')->where('persetujuan', '2');
        $pengeluaranReject = Pengeluaran::selectRaw('biaya')->where('persetujuan', '3');
        $pengeluaranAll = Pengeluaran::selectRaw('biaya');
        $activity = Activity::query();

        // dd($request->bulan.'/'.$request->tahun);
        if (isset($request->bulan) and !empty($request->bulan) and isset($request->tahun) and !empty($request->tahun)) {
            $month = $request->bulan;
            $year = $request->tahun;
            $pelanggan->whereMonth('created_at', $month)->whereYear('created_at', $year);
            $trxSuccess->whereMonth('tgl_trans', $month)->whereYear('tgl_trans', $year);
            $trxCancel->whereMonth('tgl_trans', $month)->whereYear('tgl_trans', $year);
            $arrayStatus = array(1, 2, 5, 6);
            $trxOnProses->whereMonth('tgl_trans', $month)
                ->whereYear('tgl_trans', $year);
            $trxAll->whereMonth('tgl_trans', $month)->whereYear('tgl_trans', $year);
            $pengeluaranWaiting->whereMonth('tgl', $month)->whereYear('tgl', $year);
            $pengeluaranAccept->whereMonth('tgl', $month)->whereYear('tgl', $year);
            $pengeluaranReject->whereMonth('tgl', $month)->whereYear('tgl', $year);
            $pengeluaranAll->whereMonth('tgl', $month)->whereYear('tgl', $year);
            $activity->whereMonth('updated_at', $month)->whereYear('updated_at', $year);
        } elseif (empty($request->bulan) and isset($request->tahun) and !empty($request->tahun)) {
            $year = $request->tahun;
            $pelanggan->whereYear('created_at', $year);
            $trxSuccess->whereYear('tgl_trans', $year);
            $trxCancel->whereYear('tgl_trans', $year);
            $arrayStatus = array(1, 2, 5, 6);
            $trxOnProses->whereYear('tgl_trans', $year);
            $trxAll->whereYear('tgl_trans', $year);
            $pengeluaranWaiting->whereYear('tgl', $year);
            $pengeluaranAccept->whereYear('tgl', $year);
            $pengeluaranReject->whereYear('tgl', $year);
            $pengeluaranAll->whereYear('tgl', $year);
            $activity->whereYear('updated_at', $year);
        }

        if (isset($request->website) and !empty($request->website)) {
            $website = $request->website;
            $pelanggan->where('website_id', $website);
            $trxSuccess->where('website_id', $website);
            $trxCancel->where('website_id', $website);
            $trxOnProses->where('website_id', $website);
            $trxAll->where('website_id', $website);
            $pengeluaranWaiting->where('website_id', $website);
            $pengeluaranAccept->where('website_id', $website);
            $pengeluaranReject->where('website_id', $website);
            $pengeluaranAll->where('website_id', $website);
            $activity->where('website_id', website()->id);
        } else {
            if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
            } else {
                $pelanggan->where('website_id', website()->id);
                $trxSuccess->where('website_id', website()->id);
                $trxCancel->where('website_id', website()->id);
                $trxOnProses->where('website_id', website()->id);
                $trxAll->where('website_id', website()->id);
                $pengeluaranWaiting->where('website_id', website()->id);
                $pengeluaranAccept->where('website_id', website()->id);
                $pengeluaranReject->where('website_id', website()->id);
                $pengeluaranAll->where('website_id', website()->id);
                $activity->where('website_id', website()->id);
            }
        }


        $ppn = totalPpn($month, $year, $website);
        $pph = totalPph($month, $year, $website);
        // $kasBesar = sisaKasBesar($month, $year, $website);
        // $kasKecil = sisaKasKecil($month, $year, $website);
        $kasBesar = 0;
        $kasKecil = 0;

        $pelanggan = $pelanggan->count();
        $trxSuccess = $trxSuccess->count();
        $trxCancel = $trxCancel->count();
        $arrayStatus = array(1, 2, 5, 6);
        $trxOnProses = $trxOnProses->count();
        $trxAll = $trxAll->count();
        $pengeluaranWaiting = $pengeluaranWaiting->sum('biaya');
        $pengeluaranAccept = $pengeluaranAccept->sum('biaya');
        $pengeluaranReject = $pengeluaranReject->sum('biaya');
        $pengeluaranAll = $pengeluaranAll->sum('biaya');
        $activity = $activity->orderBy('id', 'DESC')->get();

        $kasKecilDebit = $this->getArrayKas($month, $year, 1, 'debit', $website);
        $kasKecilKredit = $this->getArrayKas($month, $year, 1, 'kredit', $website);
        $kasBesarDebit = $this->getArrayKas($month, $year, 2, 'debit', $website);
        $kasBesarKredit = $this->getArrayKas($month, $year, 2, 'kredit', $website);

        return view('home', compact('ppn', 'pph', 'kasBesar', 'kasKecil', 'pelanggan', 'trxAll', 'trxSuccess', 'trxOnProses', 'trxCancel', 'pendapatan', 'pengeluaranAll', 'pengeluaranWaiting', 'pengeluaranAccept', 'pengeluaranReject', 'month', 'year', 'website', 'activity', 'kasKecilDebit', 'kasKecilKredit', 'kasBesarDebit', 'kasBesarKredit'));
    }

    function getArrayKas($month = null, $year = null, $jenis, $mutasi, $website = null)
    {
        $value = array();
        if ($month and $year) {
            $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            for ($i = 1; $i <= $day; $i++) {
                if ($website == null) {

                    $query = Kas::where('jenis', $jenis)->whereDay('tgl', $i)->whereMonth('tgl', $month)->whereYear('tgl', $year)->sum($mutasi);
                } else {
                    $query = Kas::where('jenis', $jenis)->whereDay('tgl', $i)->whereMonth('tgl', $month)->whereYear('tgl', $year)->where('website_id', $website)->sum($mutasi);
                }
                $value[$i] = $query;
            }
        } elseif (!$month and $month) {
            for ($i = 1; $i <= 12; $i++) {
                if ($website == null) {

                    $query = Kas::where('jenis', $jenis)->whereYear('tgl', $year)->sum($mutasi);
                } else {
                    $query = Kas::where('jenis', $jenis)->whereYear('tgl', $year)->where('website_id', $website)->sum($mutasi);
                }
                $value[$i] = $query;
            }
        }

        return $value;
    }
}
