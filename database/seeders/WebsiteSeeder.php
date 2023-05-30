<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('website')->insert([
            'nama_website' => 'My Toko',
            'username'=>'superadmin',
            'tagline'=>'',
            'contact' => '089636137032',
            'provinsi'=>32,
            'kota'=>93,
            'kecamatan'=>1276,
            'kode_pos'=>26181,
            'address' => 'Jl. M. Yamin, Aur Kuning',
            'icon' => 'default.png',
            'description'=>'',
            'trx_ppn'=>0,
            'trx_pph'=>0,
            'trx_markup'=>0,
            'trx_verifikasi'=>0,
            'trx_duration_online'=>172800,
            'trx_duration_offline'=>15768000,
            'trx_prefix'=>'',
            'quo_prefix'=>'',
            'created_by'=>1,
            'updated_by'=>1,
        ]);

       
    }
}
