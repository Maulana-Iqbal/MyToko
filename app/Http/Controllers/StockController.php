<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\Repositori\ProdukRepositori;
use App\Repositori\StockJenisRepositori;
use App\Repositori\StockTransferRepositori;
use App\Services\GudangService;
use App\Services\SatuanService;
use App\Services\StockService;
use App\Services\PemasokService;
use Illuminate\Http\Request;
use PDF;

class StockController extends Controller
{
    protected $stockService;
    protected $produkRepo;
    protected $satuanService;
    protected $pemasokService;
    protected $gudangService;
    protected $stockJenisRepo;
    protected $stockTransferRepo;
    public function __construct()
    {
        $this->stockJenisRepo = new StockJenisRepositori();
        $this->stockTransferRepo = new StockTransferRepositori();
        $this->stockService = new StockService();
        $this->satuanService = new SatuanService();
        $this->pemasokService = new PemasokService();
        $this->gudangService = new GudangService();
        $this->produkRepo = new ProdukRepositori();
        $this->middleware('permission:stock|stock-list|stock-ubah-harga|stock-pengurangan|stock-transfer|stock-masuk|stock-keluar|stock-gudang|stock-laporan-semua-stock|stock-laporan-stock-gudang|stock-laporan-masuk|stock-laporan-keluar|stock-laporan-pengurangan|stock-laporan-transfer', ['only' => ['index']]);
        // $this->middleware('permission:stock|stock-create', ['only' => ['store']]);
        $this->middleware('permission:stock|stock-ubah-harga', ['only' => ['ubahHarga']]);
        $this->middleware('permission:stock|stock-pengurangan', ['only' => ['pengurangan']]);
        $this->middleware('permission:stock|stock-transfer', ['only' => ['stockTransfer']]);
    }

    public function index(Request $request)
    {
    
        $produk = $this->produkRepo->getWhere(null)->select('stock.produk_id', 'stock.harga', 'stock.harga_jual', 'stock.harga_grosir', 'stock.jumlah', 'produk.id', 'produk.kode_produk', 'produk.nama_produk', 'produk.slug', 'produk.kategori_id', 'produk.satuan_id', 'produk.website_id')->join('stock', 'stock.produk_id', 'produk.id');
        if ($request->produk) {
            $produk->whereIn('stock.produk_id', $request->produk);
        }
        if ($request->satuan) {
            $produk->whereIn('satuan_id', $request->satuan);
        }
        if ($request->kategori) {
            $produk->whereIn('kategori_id', $request->kategori);
        }

        if ($request->jumlah) {
            $produk->where('jumlah', '<=', $request->jumlah);
        }

        if (auth()->user()->hasPermissionTo('show-all')) {
            if (!empty($request->website)) {
                $produk->whereIn('stock.website_id', $request->website);
            }
        } else {
            $produk->where('stock.website_id', website()->id);
        }

        // $produk->orderBy('updated_at', 'DESC');
        // $produk->orderBy('created_at', 'DESC');
        $produk = $produk->get();
        if ($request->ajax()) {
            return $this->stockService->getDatatable($produk);
        }

        if ($request->print or $request->export or $request->pdf) {
            $print = '';
            $export = '';
            $stock = $produk;
            if (isset($request->print)) {
                $print = 'ya';
            } elseif (isset($request->export)) {
                $export = 'ya';
            } elseif (isset($request->pdf)) {
                $pdf = PDF::loadview('laporan/laporan-stock', compact('stock', 'export', 'print'))->setPaper('a4', 'landscape');
                return $pdf->download('laporan-stock' . date('Y-m-d') . '.pdf');
            }

            return view("laporan/laporan-stock", compact('stock', 'export', 'print'));
        }

        return view('stock/stock', compact('produk'));
    }

    public function stockJenis(Request $request)
    {
        $produk = $this->stockJenisRepo->getWhere(null)->select('stock_jenis.id','stock_jenis.jenis', 'stock_jenis.nomor', 'stock_jenis.gudang_id', 'stock_jenis.tgl', 'stock_jenis.produk_id', 'stock_jenis.stock_awal', 'stock_jenis.jumlah', 'stock_jenis.kondisi','stock_jenis.harga', 'stock_jenis.harga_final','stock_jenis.harga_jual','stock_jenis.harga_grosir', 'produk.id', 'produk.kode_produk', 'produk.nama_produk', 'produk.kategori_id', 'produk.satuan_id', 'produk.website_id')->join('produk', 'produk.id', 'stock_jenis.produk_id');
       
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $produk->whereBetween('tgl', array($request->from_date, $request->to_date));
        }
        if ($request->nomor) {
            $produk->whereIn('nomor', $request->nomor);
        }
        if ($request->produk) {
            $produk->whereIn('produk.id', $request->produk);
        }
        if ($request->satuan) {
            $produk->whereIn('satuan_id', $request->satuan);
        }
        if ($request->kategori) {
            $produk->whereIn('kategori_id', $request->kategori);
        }
        if ($request->kondisi) {
            $produk->whereIn('kondisi', $request->kondisi);
        }
        if ($request->jumlah) {
            $produk->where('jumlah', '<=', $request->jumlah);
        }
        if (auth()->user()->hasPermissionTo('show-all')) {
            if (!empty($request->website)) {
                $produk->whereIn('stock_jenis.website_id', $request->website);
            }
        } else {
            $produk->where('stock_jenis.website_id', website()->id);
        }

        if ($request->jenis) {
            if ($request->jenis == 1 or $request->jenis == 2 or $request->jenis == 3) {
                $produk->where('valid', 1);
            }
            $produk->where('jenis', $request->jenis);
        }

        // $produk->orderBy('updated_at', 'DESC');
        $produk->orderBy('stock_jenis.created_at', 'DESC');
        $produk = $produk->get();
        if ($request->ajax()) {
            return $this->stockService->getDatatableJenis($produk);
        }


        if ($request->segment(2) == 'masuk') {
            $view = 'stock/masuk';
            $view_laporan = 'laporan/laporan-stock-masuk';
        } elseif ($request->segment(2) == 'keluar') {
            $view = 'stock/keluar';
            $view_laporan = 'laporan/laporan-stock-keluar';
        } elseif ($request->segment(2) == 'pengurangan-stock') {
            $view = 'stock/pengurangan-stock';
            $view_laporan = 'laporan/laporan-pengurangan-stock';
        }

        if ($request->print or $request->export or $request->pdf) {

            $print = '';
            $export = '';
            $stock = $produk;

            if (isset($request->print)) {
                $print = 'ya';
            } elseif (isset($request->export)) {
                $export = 'ya';
            } elseif (isset($request->pdf)) {
                $pdf = PDF::loadview($view_laporan, compact('stock', 'export', 'print'))->setPaper('a4', 'landscape');
                return $pdf->download($view_laporan . date('Y-m-d') . '.pdf');
            }

            return view($view_laporan, compact('stock', 'export', 'print'));
        }

        return view($view);
    }

    public function stockTransfer(Request $request)
    {
       
        $produk = $this->stockTransferRepo->getWhere(null)->join('stock_jenis','stock_jenis.id','stock_transfer.stock_jenis_id')->join('produk', 'produk.id', 'stock_jenis.produk_id')->groupBy('stock_jenis.nomor')->groupBy('stock_transfer.dari')->groupBy('stock_transfer.ke')->groupBy('stock_jenis.produk_id');
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $produk->whereBetween('tgl', array($request->from_date, $request->to_date));
        }
        if ($request->nomor) {
            $produk->whereIn('nomor', $request->nomor);
        }
        if ($request->produk) {
            $produk->whereIn('stock_jenis.produk_id', $request->produk);
        }
        if ($request->satuan) {
            $produk->whereIn('satuan_id', $request->satuan);
        }
        if ($request->kategori) {
            $produk->whereIn('kategori_id', $request->kategori);
        }

        if ($request->jumlah) {
            $produk->where('jumlah', '<=', $request->jumlah);
        }

        if (auth()->user()->hasPermissionTo('show-all')) {
            if (!empty($request->website)) {
                $produk->whereIn('stock_jenis.website_id', $request->website);
            }
        } else {
            $produk->where('stock_jenis.website_id', website()->id);
        }

        $produk->where('stock_jenis.valid',1);
        $produk->where('stock_jenis.jenis',2);

        $produk = $produk->orderBy('stock_jenis.created_at','desc')->get();
        if ($request->ajax()) {
            return $this->stockService->getDatatableTransfer($produk);
        }

        if ($request->print or $request->export or $request->pdf) {
            $print = '';
            $export = '';
            $stock = $produk;
            if (isset($request->print)) {
                $print = 'ya';
            } elseif (isset($request->export)) {
                $export = 'ya';
            } elseif (isset($request->pdf)) {
                $pdf = PDF::loadview('laporan/laporan-stock-transfer', compact('stock', 'export', 'print'))->setPaper('a4', 'landscape');
                return $pdf->download('laporan-stock-transfer' . date('Y-m-d') . '.pdf');
            }

            return view("laporan/laporan-stock-transfer", compact('stock', 'export', 'print'));
        }

        return view('stock/transfer');
    }

    public function store(StockRequest $request)
    {
        $result = $this->stockService->store($request);
        return response()->json($result, 200);
    }

    public function storeKeluar(Request $request)
    {
        $result = $this->stockService->storeKeluar($request);
        return response()->json($result, 200);
    }

    public function storeTransfer(Request $request)
    {
        $result = $this->stockService->storeTransfer($request);
        return response()->json($result, 200);
    }

    public function edit($id)
    {
        $data = $this->stockService->getId($id);
        return $data;
    }

    public function pengurangan(Request $request)
    {
        $request->validate([
            'produk_id' => 'required',
            'jumlahHapus' => 'required',
            'deskripsiHapus' => 'required',
        ], [
            'required' => 'Data Belum Lengkap'
        ]);
        return $this->stockService->pengurangan($request->all());
    }

    public function ubahHarga(Request $request)
    {
        $request->validate([
            'produk_id' => 'required',
            'harga_modal_baru' => 'required',
            'harga_jual_baru' => 'required',
        ], [
            'required' => 'Data Belum Lengkap'
        ]);
        return $this->stockService->ubahHarga($request->all());
    }

    public function noSelect(Request $request)
    {
        $search = $request->search;
        if (auth()->user()->hasPermissionTo('show-all')) {
            $q = null;
        } else {
            $q = ['website_id' => website()->id];
        }
        if ($search == '') {
            $data = $this->stockJenisRepo->getWhere($q)->where('jenis',$request->jenis)->orderBy('nomor', 'asc')->select('nomor')->groupBy('nomor')->get();
        } else {
            $data = $this->stockJenisRepo->getWhere($q)->where('jenis',$request->jenis)->where('nomor',$search)->orderby('nomor', 'asc')->select('nomor')->groupBy('nomor')->first();
        }

        $response = array();
        foreach ($data as $data) {
            $response[] = array(
                "id" => $data->nomor,
                "text" => $data->nomor
            );
        }
        return response()->json($response);
    }
}
