<?php

namespace App\Http\Controllers;

use App\Repositori\GaleriRepositori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GaleriController extends Controller
{
    protected $galeriRepo;
    public function __construct()
    {
        $this->galeriRepo = new GaleriRepositori();
    }

    public function index(Request $request)
    {
        $galeri = $this->galeriRepo->getAll();
        return view('website/galeri', compact('galeri'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $request->validate([
            'file.*' => 'required|mimes:png,jpeg,jpg,JPEG,JPG|max:1024',
        ], [
            'max' => 'Maximal ukuran file 1024KB/1MB',
            'mimes' => 'Format Gambar di Izinkan png,jpeg,jpg',

        ]);

        if ($request->hasfile('file')) {
            $images = $request->file('file');

            foreach ($images as $image) {
                $fileName = time() . '-' . $image->getClientOriginalName();
                $image->move(public_path('galeriImage'), $fileName);
                $this->galeriRepo->store(['gambar' => $fileName, 'data_id' => '', 'jenis' => 'galeri', 'deskripsi' => '']);
            }
        }

        return back()->with('success', 'Upload Gambar Berhasil.');
    }

    public function edit($id)
    {
        $galeri =$this->galeriRepo->getId($id);
        return response()->json($galeri);
    }

    public function destroy($id)
    {
        $query =$this->galeriRepo->getId($id)->destroy();
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

    public function aksiGaleri(Request $request)
    {
        $request->validate([
            'select' => 'required'
        ], [
            'required' => 'Tidak ada Gambar Dipilih...!!!'
        ]);
        if (isset($request->hapus)) {
            DB::transaction(function () use ($request) {
               $this->galeriRepo->getWhere(null)->whereIn('id', $request->select)->delete();
            });
            return back()->with('success', 'Hapus Gambar Berhasil.');
        } elseif (isset($request->homeSlider)) {
            $jml = count($request->input('select'));
            $inDb =$this->galeriRepo->getWhere(['jenis'=>'homeSlider'])->count();
            $total = $jml + $inDb;
            if ($total <= 5) {
                foreach ($request->input('select') as $select) {
                    $query =$this->galeriRepo->getId($select);
                    // if(empty($query->data_id)){
                    //     $data_id='';
                    // }else{
                    //     $data_id=$query->data_id;
                    // }

                    if ($query->jenis == 'homeSlider') {
                    } else {
                        $galeri = $query->replicate()->fill([
                            'data_id' => '',
                            'jenis' => 'homeSlider',
                            'gambar' => $query->gambar,
                        ]);
                        $galeri->save();
                    }
                }
            } else {
                return back()->with('error', 'Maksimal Gambar Slider Home Hanya 5.');
            }

            return back()->with('success', 'Berhasil Ditambahkan Ke Slider Home.');
        } elseif (isset($request->produkSlider)) {
            $jml = count($request->input('select'));
            $inDb =$this->galeriRepo->getWhere(['jenis'=>'produkSlider'])->count();
            $total = $jml + $inDb;
            if ($total <= 5) {
                foreach ($request->input('select') as $select) {
                    $query =$this->galeriRepo->getId($select);
                    // if(empty($query->data_id)){
                    //     $data_id='';
                    // }else{
                    //     $data_id=$query->data_id;
                    // }

                    if ($query->jenis == 'produkSlider') {
                    } else {
                        $galeri = $query->replicate()->fill([
                            'data_id' => '',
                            'jenis' => 'produkSlider',
                            'gambar' => $query->gambar,
                        ]);
                        $galeri->save();
                    }
                }
            } else {
                return back()->with('error', 'Maksimal Gambar Slider Produk Hanya 5.');
            }
            return back()->with('success', 'Berhasil Ditambahkan Ke Slider Produk');
        }
    }
}
