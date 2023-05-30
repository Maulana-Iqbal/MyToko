<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PrefixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        DB::table('prefix')->insert([
            'produk'=>'PRD',
            'gudang'=>'GDG',
            'sales'=>'SLS',
            'pemasok'=>'SPL',
            'pembelian'=>'PMB',
            'penjualan'=>'PNJ',
            'pengurangan'=>'PNG',
            'stocktransfer'=>'TRF',
            'preorder'=>'PO',
            'website_id'=>1
        ]);

       
    }
}
