<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('permission.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
