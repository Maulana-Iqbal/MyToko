<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class RajaOngkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('rajaongkir.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
