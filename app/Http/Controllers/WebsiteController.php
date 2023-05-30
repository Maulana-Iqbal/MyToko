<?php

namespace App\Http\Controllers;

use App\Models\DefaultData;
use App\Models\Prefix;
use App\Models\Website;
use Illuminate\Http\Request;
use DataTables;

class WebsiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:toko|toko-list|toko-create|toko-edit|toko-delete', ['only' => ['index']]);
        $this->middleware('permission:toko|toko-create', ['only' => ['baru', 'updateWebsite']]);
        $this->middleware('permission:toko|toko-edit', ['only' => ['edit', 'setting', 'updateWebsite', 'updatePengaturan']]);
        $this->middleware('permission:toko|toko-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $website = Website::query();
        if (auth()->user()->hasPermissionTo('show-all')) {
        } else {
            $website->where('id', website()->id);
        }
        if ($request->ajax()) {
            return Datatables::of($website)
                ->addIndexColumn()
                ->addColumn('icon', function ($row) {
                    return "<a href='/image/website/" . $row->icon . "'><img width='50px' src='/image/website/" . $row->icon . "'/></a>";
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    $btn = $btn . ' <a href="/toko/' . $row->username . '" class="btn btn-outline-info btn-xs"> <i class="mdi mdi-eye-circle-outline"></i></a>';
                    $btn = $btn . ' <a href="/toko/ubah/' . $row->username . '" class="edit btn btn-outline-primary btn-xs"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_website="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteWebsite"> <i class="mdi mdi-delete"></i></a>';
                    return $btn;
                })
                ->rawColumns(['icon', 'action'])
                ->make(true);
        }
        return view('website.website');
    }

    public function baru(Request $request)
    {
        return view('website/websiteNew');
    }

    public function ubahProfil(Request $request)
    {
        $website = Website::where('username',$request->username)->firstOrFail();
        return view('website/website_setting', compact(['website']));
    }

    public function profil(Request $request)
    {
        $website = Website::where('username',$request->username)->firstOrFail();
        $jenis = jenisSelesai('', '',$website->id);
        $jenisCount=jenisCountSelesai('','',$website->id);
        $topSellProduk=topSellProduk('','',$website->id);
        return view('website/profil-toko', compact(['website','jenis','jenisCount','topSellProduk']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }


    public function updateWebsite(Request $request)
    {
        $id = '';
        if ($request->id) {
            $id = dec($request->id);
            $request->validate([
                'nama' => 'required',
                'username' => 'required|unique:website,username,' . $id,
                'nama_atasan' => 'required',
                'tagline' => 'required',
                'contact' => 'required|unique:website,contact,' . $id,
                'province_destination' => 'required',
                'city_destination' => 'required',
                'district_destination' => 'required',
                'kode_pos' => 'required',
                'alamat' => 'required',
            ], [
                'required' => 'Silahkan lengkapi data',
                'username.unique' => 'Username Toko Tidak Dapat Digunakan',
                'contact.unique' => 'Telpon / HP sudah terdaftar',
            ]);
        } else {

            $request->validate([
                'nama' => 'required',
                'username' => 'required|unique:website,username',
                'nama_atasan' => 'required',
                'tagline' => 'required',
                'contact' => 'required|unique:website,contact',
                'province_destination' => 'required',
                'city_destination' => 'required',
                'district_destination' => 'required',
                'kode_pos' => 'required',
                'alamat' => 'required',
            ], [
                'required' => 'Silahkan lengkapi data',
                'username.unique' => 'Username Toko Tidak Dapat Digunakan',
                'contact.unique' => 'Telpon / HP sudah terdaftar',
            ]);
        }
        if ($request->file) {
            $request->validate([
                'file' => 'image|mimes:ico,png,jpg,jpeg|max:220',
            ], [
                'max' => 'Maksimal Kapasitas Foto 100KB',
                'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg'
            ]);
            $filename = 'website' . time() . '.' . $request->file->extension();
            $request->file->move(public_path('image/website'), $filename);
        } else {
            if (!empty($id)) {
                $website = Website::find($id);
                $filename = $website->icon;
            } else {
                $filename = '';
            }
        }

        if ($request->kop_surat) {
            $request->validate([
                'kop_surat' => 'image|mimes:png,jpg,jpeg|max:500KB',
            ], [
                'max' => 'Maksimal Kapasitas Foto 500KB',
                'mimes' => 'Ekstensi Gambar Diizinkan .png / .jpg / .jpeg'
            ]);
            $kop_surat = 'kop' . time() . '.' . $request->kop_surat->extension();
            $request->kop_surat->move(public_path('image/website/kop'), $kop_surat);
        } else {
            if (!empty($id)) {
                $website = Website::find($id);
                $kop_surat = $website->kop_surat;
            } else {
                $kop_surat = '';
            }
        }



        $save = Website::updateOrCreate([
            'id' => $id
        ], [
            'nama_website' => $request->nama,
            'username' => strtolower($request->username),
            'nama_atasan' => $request->nama_atasan,
            'tagline' => $request->tagline,
            'provinsi' => $request->province_destination,
            'kota' => $request->city_destination,
            'kecamatan' => $request->district_destination,
            'kode_pos' => $request->kode_pos,
            'address' => $request->alamat,
            'contact' => $request->contact,
            'whatsapp' => $request->whatsapp,
            'email' => $request->email,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'tiktok' => $request->tiktok,
            'icon' => $filename,
            'kop_surat' => $kop_surat,
            'description' => $request->deskripsi,
        ]);
        if (empty($id)) {
            Prefix::create([
                'produk' => strtoupper($request->username) . '-PR',
                'gudang' => strtoupper($request->username) . '-GD',
                'sales' => strtoupper($request->username) . '-SL',
                'pemasok' => strtoupper($request->username) . '-SP',
                'pembelian' => strtoupper($request->username) . '-PB',
                'penjualan' => strtoupper($request->username) . '-PJ',
                'pengurangan' => strtoupper($request->username) . '-PN',
                'stocktransfer' => strtoupper($request->username) . '-ST',
                'preorder' => strtoupper($request->username) . '-PO',
                'website_id' => $save->id
            ]);

            DefaultData::create([
                'gudang_id' => 0,
                'sales_id' => 0,
                'pemasok_id' => 0,
                'pelanggan_id' => 0,
                'website_id' => $save->id
            ]);
        }
        return redirect()->back()->with(['status' => 'success', 'message' => 'Berhasil!']);
    }

    public function updatePengaturan(Request $request)
    {
        $id = dec($request->id);
        $request->validate([
            'ppn' => 'required',
            'pph' => 'required',
            'verifikasi' => 'required',
            'markup' => 'required',
            'duration_online' => 'required',
            'duration_offline' => 'required',
        ], [
            'required' => 'Silahkan lengkapi data',
        ]);
        $website = Website::find($id);
        $website->trx_ppn = $request->ppn;
        $website->trx_pph = $request->pph;
        $website->trx_verifikasi = $request->verifikasi;
        $website->trx_markup = $request->markup;
        $website->trx_duration_online = $request->duration_online;
        $website->trx_duration_offline = $request->duration_offline;
        $website->trx_prefix = $request->trx_prefix;
        $website->quo_prefix = $request->quo_prefix;
        $website->save();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Berhasil Diperbaharui!']);
    }

    public function updatePrefix(Request $request)
    {
        $website_id = dec($request->website_id);
        $prefix = Prefix::where('website_id', $website_id)->first();
        $request->validate([
            'produk'=>'required|unique:prefix,produk,'.$prefix->id,
            'gudang'=>'required|unique:prefix,gudang,'.$prefix->id,
            'sales'=>'required|unique:prefix,sales,'.$prefix->id,
            'supplier'=>'required|unique:prefix,pemasok,'.$prefix->id,
            'pembelian'=>'required|unique:prefix,pembelian,'.$prefix->id,
            'penjualan'=>'required|unique:prefix,penjualan,'.$prefix->id,
            'pengurangan'=>'required|unique:prefix,pengurangan,'.$prefix->id,
            'stocktransfer'=>'required|unique:prefix,stocktransfer,'.$prefix->id,
            'preorder'=>'required|unique:prefix,preorder,'.$prefix->id,
        ],[
            'required'=>'Data Belum Lengkap',
            'produk.unique'=>'Prefix Produk Tidak Dapat Digunakan',
            'gudang.unique'=>'Prefix Gudang Tidak Dapat Digunakan',
            'sales.unique'=>'Prefix Sales Tidak Dapat Digunakan',
            'supplier.unique'=>'Prefix Supplier Tidak Dapat Digunakan',
            'pembelian.unique'=>'Prefix Pembelian Tidak Dapat Digunakan',
            'penjualan.unique'=>'Prefix Penjualan Tidak Dapat Digunakan',
            'pengurangan.unique'=>'Prefix Pengurangan Tidak Dapat Digunakan',
            'stocktransfer.unique'=>'Prefix Transfer Stock Tidak Dapat Digunakan',
            'preorder.unique'=>'Prefix Purchase Order Tidak Dapat Digunakan',
        ]);
        $prefix->update([
            'produk' => $request->produk,
            'gudang' => $request->gudang,
            'sales' => $request->sales,
            'pemasok' => $request->supplier,
            'pembelian' => $request->pembelian,
            'penjualan' => $request->penjualan,
            'pengurangan' => $request->pengurangan,
            'stocktransfer' => $request->stocktransfer,
            'preorder' => $request->preorder,
        ]);
        return redirect()->back()->with(['status' => 'success', 'message' => 'Berhasil Diperbaharui!']);
    }

    public function updateDefault(Request $request)
    {
        $website_id = dec($request->website_id);
        $default = DefaultData::where('website_id', $website_id)->first();
        $request->validate([
            'gudang'=>'required|unique:default,gudang_id,'.$default->id,
            'sales'=>'required|unique:default,sales_id,'.$default->id,
            'supplier'=>'required|unique:default,pemasok_id,'.$default->id,
            'customer'=>'required|unique:default,pelanggan_id,'.$default->id,
        ],[
            'required'=>'Data Belum Lengkap',
        ]);
        $default->update([
            'gudang_id' => $request->gudang,
            'sales_id' => $request->sales,
            'pemasok_id' => $request->supplier,
            'pelanggan_id' => $request->customer,
            ]);
        return redirect()->back()->with(['status' => 'success', 'message' => 'Berhasil Diperbaharui!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function show(Website $website)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function edit(Website $website)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Website $website)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = dec($request->id);
        Website::find($id)->delete();
        $response = [
            'success' => true,
            'message' => 'Berhasil Hapus Data Website!',
        ];
        return response()->json($response, 200);
    }
}
