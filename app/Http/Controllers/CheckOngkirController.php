<?php

namespace App\Http\Controllers;

use App\Models\ProvinceOngkir;
use App\Models\RegencyOngkir;
use App\Models\DistrictOngkir;
use Illuminate\Http\Request;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class CheckOngkirController extends Controller
{
    // public function index()
    // {
    //     $provinces = OngkirProvince::pluck('name', 'province_id');
    //     return view('website.ongkir', compact('provinces'));
    // }

    // public function getCities($id)
    // {
    //     $city = OngkirCity::where('province_id', $id)->pluck('name', 'city_id');
    //     return response()->json($city);
    // }

    public function check_ongkir(Request $request)
    {
        $cost = RajaOngkir::ongkosKirim([
            'origin'        => $request->city_origin, // ID kota/kabupaten asal
            'destination'   => $request->city_destination, // ID kota/kabupaten tujuan
            'weight'        => $request->weight, // berat barang dalam gram
            'courier'       => $request->courier // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
        ])->get();


        return response()->json($cost);
    }

    public function getAllProvinsi()
    {
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
        //     CURLOPT_HTTPHEADER => array(
        //         "key: 5d7790df50bf13f5b139d3c4589d5eb7"
        //     ),
        // ));

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);

        // if ($err) {
        //     echo "cURL Error #:" . $err;
        // } else {
        //     $data = json_decode($response, TRUE);
        //     $ongkirProvinsi = $data["rajaongkir"]["results"];
        //     echo "<option value=''>Pilih Provinsi Tujuan</option>";
        //     foreach ($ongkirProvinsi as $prov) {
        //         echo "<option value='" . $prov['province_id'] . "'>" . $prov['province'] . "</option>";
        //     }
        // }

        // $ongkirProvinsi = ProvinceOngkir::orderBy('name', 'asc')->get();

        // echo "<option value=''>Pilih Provinsi</option>";
        // foreach ($ongkirProvinsi as $prov) {
        //     echo "<option value='" . $prov['id'] . "'>" . $prov['name'] . "</option>";
        // }
    }

    public function provinsiSelect(Request $request)
    {
        $data = ProvinceOngkir::orderBy('name', 'asc')->select('id', 'name');
        if ($request->search) {
            $data->where('name', 'like', '%' . $request->search . '%');
        }
        $data = $data->get();

        $response = array();
        foreach ($data as $data) {
            $response[] = array(
                "id" => $data->id,
                "text" => $data->name
            );
        }
        return response()->json($response);
    }

    public function kabupatenSelect(Request $request)
    {
        $data = RegencyOngkir::orderBy('name', 'asc')->select('id', 'name');
        if ($request->provinsi_id) {
            $data->where('province_id', $request->provinsi_id);
        }
        $data->where('name', 'like', '%' . $request->search . '%');
        $data = $data->get();

        $response = array();
        foreach ($data as $data) {
            $response[] = array(
                "id" => $data->id,
                "text" => $data->name
            );
        }
        return response()->json($response);
    }

    public function kecamatanSelect(Request $request)
    {
        $data = DistrictOngkir::orderBy('name', 'asc')->select('id', 'name');
        if ($request->kabupaten_id) {
            $data->where('regency_id', $request->kabupaten_id);
        }
        $data->where('name', 'like', '%' . $request->search . '%');
        $data = $data->get();

        $response = array();
        foreach ($data as $data) {
            $response[] = array(
                "id" => $data->id,
                "text" => $data->name
            );
        }
        return response()->json($response);
    }

    public function getAllKota($id)
    {
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=" . $id,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
        //     CURLOPT_HTTPHEADER => array(
        //         "key: 5d7790df50bf13f5b139d3c4589d5eb7"
        //     ),
        // ));

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);

        // if ($err) {
        //     echo "cURL Error #:" . $err;
        // } else {
        //     $data = json_decode($response, TRUE);
        //     $ongkirKota = $data["rajaongkir"]["results"];
        //     echo "<option value=''>Pilih Kota / Kabupaten Tujuan</option>";
        //     foreach ($ongkirKota as $kota) {
        //         echo "<option value='" . $kota['city_id'] . "'>" . $kota['city_name'] . "</option>";
        //     }
        // }

        // $ongkirKota = RegencyOngkir::where('province_id', $id)->orderBy('name', 'asc')->get();

        // echo "<option value=''>Pilih Kota</option>";
        // foreach ($ongkirKota as $kota) {
        //     echo "<option value='" . $kota['id'] . "'>" . $kota['name'] . "</option>";
        // }
    }

    public function getAllKecamatan($id)
    {
        // $ongkirKecamatan = DistrictOngkir::where('regency_id', $id)->orderBy('name', 'asc')->get();

        // echo "<option value=''>Pilih Kecamatan</option>";
        // foreach ($ongkirKecamatan as $kecamatan) {
        //     echo "<option value='" . $kecamatan['id'] . "'>" . $kecamatan['name'] . "</option>";
        // }
    }

    public function costs(Request $request)
    {
        $origin = website()->kota;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=" . $origin . "&destination=" . $request->tujuan . "&weight=" . $request->berat . "&courier=" . $request->kurir,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . env('RAJAONGKIR_API_KEY')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response, TRUE);
            $costs = $data["rajaongkir"]["results"]["0"]["costs"];
            echo "<option value=''>Pilih Paket</option>";
            foreach ($costs as $cost) {
                if ($request->kurir <> 'pos') {
                    $day = 'HARI';
                } else {
                    $day = '';
                }
                $text = $cost['service'] . " - Biaya Rp. " . number_format($cost['cost']['0']['value'], 0, ',', '.') . " / " . $cost['cost']['0']['etd'] . " " . $day;
                echo "<option data-paket='" . $cost['service'] . "' data-ongkir='" . $cost['cost']['0']['value'] . "' value='" . $cost['cost']['0']['value'] . "'  data-etd='" . $cost['cost']['0']['etd'] . "'>" . $text . "</option>";
            }
        }
    }
}
