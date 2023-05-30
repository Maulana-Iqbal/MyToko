<?php

use App\Http\Controllers\XenditController;
use App\Models\Aset;
use App\Models\Gudang;
use App\Models\HariKerja;
use App\Models\Notifikasi;
use App\Models\Website;
use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\Pemasok;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\PresensiSetting;
use App\Models\Quotation;
use App\Models\Rekening;
use App\Models\Sales;
use App\Models\Stock;
use App\Models\StockOrder;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " Belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " Puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " Seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " Ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " Seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " Ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " Juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " Milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " Trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}

function terbilang($nilai)
{
    if ($nilai < 0) {
        $hasil = "Minus " . trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }
    return $hasil;
}

function uang($uang)
{
    return "Rp. " . number_format($uang, 0, ',', '.');
}

function cleanUang($uang)
{
    return str_replace('.', '', $uang);
}

function tglIndo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}


function sendNotif($token_1, $comment)
{
    $SERVER_API_KEY = 'AAAAzynQzAQ:APA91bFD1ThQWPt8-PzE0Ifeuhd-cAcPJwmfeUu9Xa5uqsBH4D4H3IAG31OoM48ZzYPvP9zqf6qWCtMSZzSNpN4uNNHWFShFBxWF3pQHuyewj_iun5iBnaMOzSb7emkbTjfJhE8ySi5C';

    // $token_1 = 'frtiwtX4RRONp_JpU-6pXl:APA91bFjUChGY1J-TQ1I1xV4PLOlNvplv8-KpVluw5SXTvf7J53veDtG7HJAvcQ9Z0sO1Ss2iYhuOTQoFr3_3fq6L10cdomMsOHyqQJHK-GrElYgtywM5Kih0qOW9M93J0_dDyifSrlL';

    $data = [

        "registration_ids" => [
            $token_1
        ],

        "notification" => $comment,

    ];

    $dataString = json_encode($data);

    $headers = [

        'Authorization: key=' . $SERVER_API_KEY,

        'Content-Type: application/json',

    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);
}

function createNotifikasi($data)
{
    $query = Notifikasi::Create($data);
    return $query;
}

function website($id = null)
{
    $setting = Website::query();
    if ($id <> null) {
        $setting->where('website.id', $id);
    } else {
        if (isset(auth()->user()->level)) {
            $setting->where('website.id', auth()->user()->website_id);
        }
    }
    $setting = $setting->with('webprovinsi')->with('webkota')->with('webkecamatan')->first();
    return $setting;
}

function rekening($id = null)
{
    $rekening = Rekening::query();
    if ($id <> null) {
        $rekening->where('website_id', $id);
    } else {
        if (isset(auth()->user()->level)) {
            $rekening->where('website_id', auth()->user()->website_id);
        }
    }
    $rekening->where('isActive', 1);
    $rekening = $rekening->first();
    return $rekening;
}

function dataWebsite()
{
    $website = Website::orderBy('nama_website', 'ASC');
    if (auth()->user()->hasPermissionTo('show-all')) {
    } else {
        $website->where('id', '>', 1);
    }
    $website = $website->get();
    return $website;
}

function gantiformat($nomorhp)
{
    //Terlebih dahulu kita trim dl
    $nomorhp = trim($nomorhp);
    //bersihkan dari karakter yang tidak perlu
    $nomorhp = strip_tags($nomorhp);
    // Berishkan dari spasi
    $nomorhp = str_replace(" ", "", $nomorhp);
    // bersihkan dari bentuk seperti  (022) 66677788
    $nomorhp = str_replace("(", "", $nomorhp);
    // bersihkan dari format yang ada titik seperti 0811.222.333.4
    $nomorhp = str_replace(".", "", $nomorhp);

    //cek apakah mengandung karakter + dan 0-9
    if (!preg_match('/[^+0-9]/', trim($nomorhp))) {
        // cek apakah no hp karakter 1-3 adalah +62
        if (substr(trim($nomorhp), 0, 3) == '+62') {
            $nomorhp = trim($nomorhp);
        }
        // cek apakah no hp karakter 1 adalah 0
        elseif (substr($nomorhp, 0, 1) == '0') {
            $nomorhp = '+62' . substr($nomorhp, 1);
        }
    }
    return $nomorhp;
}

function createCode($tipe)
{
    //$tipe
    //1. Online
    //2. Offile
    $cek_kode = Transaksi::latest()->first();
    if (empty($cek_kode->kode_trans)) {
        $kode_trans = website()->trx_prefix . '-' . $tipe . '-' . date('Ym') . '-1000';
    } else {
        $kode = substr($cek_kode->kode_trans, 13);
        $kode = $kode + 1;
        $kode_trans = website()->trx_prefix . '-' . $tipe . '-' . date('Ym') . '-' . $kode;
    }
    return $kode_trans;
}

function createCodeQuotation()
{
    $cek_kode = Quotation::latest()->first();
    if (empty($cek_kode->no_quo)) {
        $no_quo = 1000;
    } else {
        $no_quo = $cek_kode->no_quo + 1;
    }
    return $no_quo;
}

function createCodeProduct()
{
    $prefix = website()->prefix->produk . '-';
    $cek_kode = Produk::where('kode_produk', 'LIKE', '%' . $prefix . '%')->where('website_id', website()->id)->orderBy('kode_produk', 'desc')->first();
    if (empty($cek_kode->kode_produk)) {
        $kode = $prefix . '1001';
    } else {
        $no = str_replace($prefix, '', $cek_kode->kode_produk);
        $no = preg_replace("/[^0-9]/", "", $no);
        for ($i = $no + 1; $i < 10000000; $i++) {
            $kode = $prefix . $i;
            $cek = Produk::where("kode_produk", $kode)->count();
            if ($cek < 1) {
                break;
            }
        }
    }
    return $kode;
}

function createCodeGudang()
{
    $prefix = website()->prefix->gudang . '-';
    $cek_kode = Gudang::where('kode', 'LIKE', '%' . $prefix . '%')->where('website_id', website()->id)->orderBy('kode', 'desc')->first();
    if (empty($cek_kode->kode)) {
        $kode = $prefix . '1000';
    } else {
        $no = str_replace($prefix, '', $cek_kode->kode);
        $no = preg_replace("/[^0-9]/", "", $no);
        for ($i = $no + 1; $i < 10000000; $i++) {
            $kode = $prefix . $i;
            $cek = Gudang::where("kode", $kode)->count();
            if ($cek < 1) {
                break;
            }
        }
    }
    return $kode;
}

function createCodeAset()
{
    $cek_kode = Aset::where('kode_aset', 'LIKE', '%A-%')->latest()->first();
    if (empty($cek_kode->kode_aset)) {
        $kode = 'A' . '-1000';
    } else {
        $no = preg_replace("/[^0-9]/", "", $cek_kode->kode_aset);
        for ($i = $no + 1; $i < 10000000; $i++) {
            $kode = 'A' . '-' . $i;
            $cek = Aset::where("kode_aset", $kode)->count();
            if ($cek < 1) {
                break;
            }
        }
    }
    return $kode;
}

function createCodePembelian()
{
    $prefix = website()->prefix->pembelian . '-';
    $cek_kode = StockOrder::where('nomor', 'LIKE', '%' . $prefix . '%')->where('website_id', website()->id)->orderBy('nomor', 'desc')->first();
    if (empty($cek_kode->nomor)) {
        $kode = $prefix . '1001';
    } else {
        $no = str_replace($prefix, '', $cek_kode->nomor);
        $no = preg_replace("/[^0-9]/", "", $no);
        for ($i = $no + 1; $i < 10000000; $i++) {
            $kode = $prefix . $i;
            $cek = StockOrder::where("nomor", $kode)->count();
            if ($cek < 1) {
                break;
            }
        }
    }
    return $kode;
}

function createCodePenjualan()
{
    $prefix = website()->prefix->penjualan . '-';
    $cek_kode = StockOrder::where('nomor', 'LIKE', '%' . $prefix . '%')->where('website_id', website()->id)->orderBy('nomor', 'desc')->first();
    if (empty($cek_kode->nomor)) {
        $kode = $prefix . '1001';
    } else {
        $no = str_replace($prefix, '', $cek_kode->nomor);
        $no = preg_replace("/[^0-9]/", "", $no);
        for ($i = $no + 1; $i < 10000000; $i++) {
            $kode = $prefix . $i;
            $cek = StockOrder::where("nomor", $kode)->count();
            if ($cek < 1) {
                break;
            }
        }
    }
    return $kode;
}

function createCodeStockTransfer()
{
    $prefix = website()->prefix->stocktransfer . '-';
    $cek_kode = StockOrder::where('nomor', 'LIKE', '%' . $prefix . '%')->where('website_id', website()->id)->orderBy('nomor', 'desc')->first();
    if (empty($cek_kode->nomor)) {
        $kode = $prefix . '1001';
    } else {
        $no = str_replace($prefix, '', $cek_kode->nomor);
        $no = preg_replace("/[^0-9]/", "", $no);
        for ($i = $no + 1; $i < 10000000; $i++) {
            $kode = $prefix . $i;
            $cek = StockOrder::where("nomor", $kode)->count();
            if ($cek < 1) {
                break;
            }
        }
    }
    return $kode;
}

function createCodePreorder()
{
    $prefix = website()->prefix->preorder . '-';
    $cek_kode = StockOrder::where('nomor', 'LIKE', '%' . $prefix . '%')->where('website_id', website()->id)->orderBy('nomor', 'desc')->first();
    if (empty($cek_kode->nomor)) {
        $kode = $prefix . '1001';
    } else {
        $no = str_replace($prefix, '', $cek_kode->nomor);
        $no = preg_replace("/[^0-9]/", "", $no);
        for ($i = $no + 1; $i < 10000000; $i++) {
            $kode = $prefix . $i;
            $cek = StockOrder::where("nomor", $kode)->count();
            if ($cek < 1) {
                break;
            }
        }
    }
    return $kode;
}


function createCodePengurangan()
{
    $prefix = website()->prefix->pengurangan . '-';
    $cek_kode = StockOrder::where('nomor', 'LIKE', '%' . $prefix . '%')->where('website_id', website()->id)->orderBy('nomor', 'desc')->first();
    if (empty($cek_kode->nomor)) {
        $kode = $prefix . '1001';
    } else {
        $no = str_replace($prefix, '', $cek_kode->nomor);
        $no = preg_replace("/[^0-9]/", "", $no);
        for ($i = $no + 1; $i < 10000000; $i++) {
            $kode = $prefix . $i;
            $cek = StockOrder::where("nomor", $kode)->count();
            if ($cek < 1) {
                break;
            }
        }
    }
    return $kode;
}

function createCodeSales()
{
    $prefix = website()->prefix->sales . '-';
    $cek_kode = Sales::where('kode', 'LIKE', $prefix . '%')->where('website_id', website()->id)->orderBy('kode', 'desc')->first();
    if (empty($cek_kode->kode)) {
        $kode = $prefix . '1000';
    } else {
        $no = str_replace($prefix, '', $cek_kode->kode);
        $no = preg_replace("/[^0-9]/", "", $no);
        for ($i = $no + 1; $i < 10000000; $i++) {
            $kode = $prefix . $i;
            $cek = Sales::where("kode", $kode)->count();
            if ($cek < 1) {
                break;
            }
        }
    }
    return $kode;
}

function createCodeSupplier()
{
    $prefix = website()->prefix->pemasok . '-';
    $cek_kode = Pemasok::where('kode', 'LIKE', '%' . $prefix . '%')->where('website_id', website()->id)->orderBy('kode', 'desc')->first();
    if (empty($cek_kode->kode)) {
        $kode = $prefix . '1000';
    } else {
        $no = str_replace($prefix, '', $cek_kode->kode);
        $no = preg_replace("/[^0-9]/", "", $no);
        for ($i = $no + 1; $i < 10000000; $i++) {
            $kode = $prefix . $i;
            $cek = Pemasok::where("kode", $kode)->count();
            if ($cek < 1) {
                break;
            }
        }
    }
    return $kode;
}

function slug($slug)
{
    return strtolower(preg_replace("/[^a-zA-Z0-9]/", "-", $slug));
}


function enc($data)
{
    $str = Crypt::encryptString($data);
    return $str;
}

function dec($data)
{
    $str = Crypt::decryptString($data);
    return $str;
}

function dataMenu()
{
    $menu = Menu::orderBy('indek', 'asc')->get();
    return $menu;
}

function saldo()
{
    $xen = new XenditController();
    $saldo = $xen->saldo();
    return $saldo->balance;
}

// function kasBesar(){
//     $kas=Kas::where('jenis','2')->where('website_id',website()->id)->orderBy('created_at','desc')->first();
//     if($kas){
//         return $kas->nominal;
//     }else{
//         return '0';
//     }
// }

// function kasKecil(){
//     $kas=Kas::where('jenis','1')->where('website_id',website()->id)->orderBy('created_at','desc')->first();
//     if($kas){
//         return $kas->nominal;
//     }else{
//         return '0';
//     }
// }


// function sisaKasBesar($bulan=null,$tahun=null,$website=null){
//     // dd($bulan.'/'.$tahun.'/'.$website);
//     $kasBesarKredit=Kas::where('jenis',2);
//     $kasBesarDebit=Kas::where('jenis',2);
//     if(isset($bulan) and !empty($bulan) and isset($tahun) and !empty($tahun)){
//         $kasBesarDebit->whereMonth('tgl',$bulan);
//         $kasBesarKredit->whereMonth('tgl',$bulan);
//         $kasBesarDebit->whereYear('tgl',$tahun);
//         $kasBesarKredit->whereYear('tgl',$tahun);
//     }elseif(empty($bulan) and isset($tahun) and !empty($tahun)){
//         $kasBesarKredit->whereYear('tgl',$tahun);
//         $kasBesarDebit->whereYear('tgl',$tahun);
//     }

//     if(isset($website) and !empty($website)){
//         $kasBesarKredit->where('website_id',$website);
//         $kasBesarDebit->where('website_id',$website);
//     }else{
//         if(auth()->user()->level=='SUPERADMIN' OR auth()->user()->level=='SUPERCEO'){

//         }else{
//         $kasBesarKredit->where('website_id',website()->id);
//         $kasBesarDebit->where('website_id',website()->id);
//         }
//     }
//     $kasBesarDebit=$kasBesarDebit->sum('debit');
//     $kasBesarKredit=$kasBesarKredit->sum('kredit');
//     $sisa=(double)$kasBesarKredit-(double)$kasBesarDebit;
//     // if($sisa<0){
//     //     $sisa=0;
//     // }
//     return $sisa;
// }

// function sisaKasKecil($bulan=null,$tahun=null,$website=null){
//     // dd($bulan.'/'.$tahun);
//     $kasBesarKredit=Kas::where('jenis',1);
//     $kasBesarDebit=Kas::where('jenis',1);
//     if(isset($bulan) and !empty($bulan) and isset($tahun) and !empty($tahun)){
//         $kasBesarDebit->whereMonth('tgl',$bulan);
//         $kasBesarKredit->whereMonth('tgl',$bulan);
//         $kasBesarDebit->whereYear('tgl',$tahun);
//         $kasBesarKredit->whereYear('tgl',$tahun);
//     }elseif(empty($bulan) and isset($tahun) and !empty($tahun)){
//         $kasBesarDebit->whereYear('tgl',$tahun);
//         $kasBesarKredit->whereYear('tgl',$tahun);
//     }

//     if(isset($website) and !empty($website)){
//         $kasBesarKredit->where('website_id',$website);
//         $kasBesarDebit->where('website_id',$website);
//     }else{
//         if(auth()->user()->level=='SUPERADMIN' OR auth()->user()->level=='SUPERCEO'){

//         }else{
//         $kasBesarKredit->where('website_id',website()->id);
//         $kasBesarDebit->where('website_id',website()->id);
//         }
//     }

//     $kasBesarDebit=$kasBesarDebit->sum('debit');
//     $kasBesarKredit=$kasBesarKredit->sum('kredit');
//     $sisa=(double)$kasBesarKredit-(double)$kasBesarDebit;
//     // if($sisa<0){
//     //     $sisa=0;
//     // }
//     return $sisa;
// }

function totalPpn($bulan = null, $tahun = null, $website = null)
{
    $transaksi = Transaksi::where('status_trans', 3);
    if (isset($bulan) and !empty($bulan) and isset($tahun) and !empty($tahun)) {
        $transaksi->whereMonth('tgl_trans', $bulan);
        $transaksi->whereYear('tgl_trans', $tahun);
    } elseif (empty($bulan) and isset($tahun) and !empty($tahun)) {
        $transaksi->whereYear('tgl_trans', $tahun);
    }

    if (isset($website) and !empty($website)) {
        $transaksi->where('website_id', $website);
    } else {
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $transaksi->where('website_id', website()->id);
        }
    }

    $transaksi = $transaksi->sum('ppn');
    return $transaksi;
}

function totalPph($bulan = null, $tahun = null, $website = null)
{
    $transaksi = Transaksi::where('status_trans', 3);
    if (isset($bulan) and !empty($bulan) and isset($tahun) and !empty($tahun)) {
        $transaksi->whereMonth('tgl_trans', $bulan);
        $transaksi->whereYear('tgl_trans', $tahun);
    } elseif (empty($bulan) and isset($tahun) and !empty($tahun)) {
        $transaksi->whereYear('tgl_trans', $tahun);
    }

    if (isset($website) and !empty($website)) {
        $transaksi->where('website_id', $website);
    } else {
        if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO') {
        } else {
            $transaksi->where('website_id', website()->id);
        }
    }

    $transaksi = $transaksi->sum('pph');
    return $transaksi;
}

function presensi_setting()
{
    $q = PresensiSetting::where('website_id', website()->id)->first();
    return $q;
}

function hari_kerja($tgl = null)
{

    $q = HariKerja::where('website_id', website()->id);
    if (!empty($tgl)) {
        $q->where('tanggal', date('Y-m-d', strtotime($tgl)));
    } else {
        $q->where('tanggal', date('Y-m-d'));
    }
    $q = $q->first();
    return $q;
}


function getArrayTotal($month = null, $year = null, $jenis, $website = null)
{
    if (auth()->user()->hasPermissionTo('show-all')) {
        $q = null;
        if ($website) {
            $q = ['website_id' => $website];
        }
    } else {
        $q = ['website_id' => website()->id];
    }
    $value = array();
    if ($month and $year) {
        $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $day; $i++) {

            $query = StockOrder::where($q)->where('jenis', $jenis)->where('status_order', 'selesai')->whereDay('tgl', $i)->whereMonth('tgl', $month)->whereYear('tgl', $year)->sum('total');
            $value[$i] = $query;
        }
    } elseif (!$month and $year) {
        for ($i = 1; $i <= 12; $i++) {
            $query = StockOrder::where($q)->where('jenis', $jenis)->where('status_order', 'selesai')->whereMonth('tgl', $i)->whereYear('tgl', $year)->sum('total');
            $value[$i] = $query;
        }
    } else {
        for ($i = 2021; $i <= date('Y'); $i++) {
            $query = StockOrder::where($q)->where('jenis', $jenis)->where('status_order', 'selesai')->whereYear('tgl', $i)->sum('total');
            $value[$i] = $query;
        }
    }

    return $value;
}


function JenisSelesai($month = null, $year = null, $website = null)
{
    if (auth()->user()->hasPermissionTo('show-all')) {
        $q = null;
        if ($website) {
            $q = ['website_id' => $website];
        }
    } else {
        $q = ['website_id' => website()->id];
    }

    $jenis = DB::table('stock_order')->select(DB::raw('sum(total) as jml, jenis'))->where($q);
    if ($month && $year) {
        $jenis = $jenis->whereMonth('tgl', $month);
    }
    if ($year) {
        $jenis = $jenis->whereYear('tgl', $year);
    }
    $jenis = $jenis->where('status_order', 'selesai')
        ->groupBy('jenis')
        ->get();
    $data = [];
    $data['pembelian'] = 0;
    $data['penjualan'] = 0;
    $data['pengurangan'] = 0;
    $data['transfer'] = 0;
    foreach ($jenis as $t) {
        if ($t->jenis == 'pembelian') {
            $data['pembelian'] = $t->jml;
        }
        if ($t->jenis == 'penjualan') {
            $data['penjualan'] = $t->jml;
        }
        if ($t->jenis == 'pengurangan') {
            $data['pengurangan'] = $t->jml;
        }
        if ($t->jenis == 'stocktransfer') {
            $data['transfer'] = $t->jml;
        }
    }
    return $data;
}

function JenisCountSelesai($month = null, $year = null, $website = null)
{
    if (auth()->user()->hasPermissionTo('show-all')) {
        $q = null;
        if ($website) {
            $q = ['website_id' => $website];
        }
    } else {
        $q = ['website_id' => website()->id];
    }

    $jenisCount = DB::table('stock_order')->select(DB::raw('count(*) as jml, jenis'))->where($q);
    if ($month && $year) {
        $jenisCount = $jenisCount->whereMonth('tgl', $month);
    }
    if ($year) {
        $jenisCount = $jenisCount->whereYear('tgl', $year);
    }
    $jenisCount = $jenisCount->where('status_order', 'selesai')
        ->groupBy('jenis')
        ->get();
    $data = [];
    $data['pembelian'] = 0;
    $data['penjualan'] = 0;
    $data['pengurangan'] = 0;
    $data['transfer'] = 0;
    foreach ($jenisCount as $t) {
        if ($t->jenis == 'pembelian') {
            $data['pembelian'] = $t->jml;
        }
        if ($t->jenis == 'penjualan') {
            $data['penjualan'] = $t->jml;
        }
        if ($t->jenis == 'pengurangan') {
            $data['pengurangan'] = $t->jml;
        }
        if ($t->jenis == 'stocktransfer') {
            $data['transfer'] = $t->jml;
        }
    }
    return $data;
}

function topSellProduk($month = null, $year = null, $website = null)
{
    if (auth()->user()->hasPermissionTo('show-all')) {
        $q = null;
    } else {
        $q = ['stock_order.website_id' => website()->id];
    }
    if ($website) {
        $q = ['stock_order.website_id' => $website];
    }
    $data = StockOrder::select(DB::raw('sum(jumlah) as jml, produk_id,harga_final as harga'))
        ->join('stock_jenis', 'stock_jenis.nomor', 'stock_order.nomor')
        ->where('stock_order.jenis', 'penjualan')
        ->where($q);
    if ($month && $year) {
        $data = $data->whereMonth('stock_jenis.tgl', $month);
    }
    if ($year) {
        $data = $data->whereYear('stock_jenis.tgl', $year);
    }
    $data = $data->where('stock_jenis.jenis', 2)->whereValid(1)
        ->groupBy('produk_id')->orderBy('jml', 'desc')
        ->limit(5)
        ->get();
    return $data;
}

function stockProdukMenipis($jml = null, $website = null)
{
    if (auth()->user()->hasPermissionTo('show-all')) {
        $q = null;
    } else {
        $q = ['website_id' => website()->id];
    }
    if ($website) {
        $q = ['stock_order.website_id' => $website];
    }

    $jumlah = 10;
    if ($jml) {
        $jumlah = $jml;
    }
    $data = Stock::select('produk_id', 'jumlah')
        ->where($q)
        ->where('jumlah', '<=', $jumlah)
        ->groupBy('produk_id')
        ->orderBy('jumlah', 'asc')
        ->limit(10)
        ->get();
    return $data;
}

function totalPengguna($month = null, $year = null, $website = null)
{
    if (auth()->user()->hasPermissionTo('show-all')) {
        $q = null;
    } else {
        $q = ['website_id' => website()->id];
    }
    if ($website) {
        $q = ['website_id' => $website];
    }
    $data = User::where($q);
    if ($month && $year) {
        $data = $data->whereMonth('created_at', $month);
    }
    if ($year) {
        $data = $data->whereYear('created_at', $year);
    }
    $data = $data->count();
    return $data;
}

function totalCustomer($month = null, $year = null, $website = null)
{
    if (auth()->user()->hasPermissionTo('show-all')) {
        $q = null;
    } else {
        $q = ['website_id' => website()->id];
    }
    if ($website) {
        $q = ['website_id' => $website];
    }

    $data = Pelanggan::where($q);
    if ($month && $year) {
        $data = $data->whereMonth('created_at', $month);
    }
    if ($year) {
        $data = $data->whereYear('created_at', $year);
    }
    $data = $data->count();
    return $data;
}

function totalSales($month = null, $year = null, $website = null)
{
    if (auth()->user()->hasPermissionTo('show-all')) {
        $q = null;
    } else {
        $q = ['website_id' => website()->id];
    }
    if ($website) {
        $q = ['website_id' => $website];
    }

    $data = Sales::where($q);
    if ($month && $year) {
        $data = $data->whereMonth('created_at', $month);
    }
    if ($year) {
        $data = $data->whereYear('created_at', $year);
    }
    $data = $data->count();
    return $data;
}

function totalSupplier($month = null, $year = null, $website = null)
{
    if (auth()->user()->hasPermissionTo('show-all')) {
        $q = null;
    } else {
        $q = ['website_id' => website()->id];
    }
    if ($website) {
        $q = ['website_id' => $website];
    }

    $data = Pemasok::where($q);
    if ($month && $year) {
        $data = $data->whereMonth('created_at', $month);
    }
    if ($year) {
        $data = $data->whereYear('created_at', $year);
    }
    $data = $data->count();
    return $data;
}

function totalToko()
{
    if (auth()->user()->hasPermissionTo('show-all')) {
        $q = null;
    } else {
        $q = ['created_by' => auth()->user()->id];
    }

    $data = Website::where($q)->count();
    return $data;
}
