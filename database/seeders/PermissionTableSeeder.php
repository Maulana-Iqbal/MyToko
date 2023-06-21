<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'show-all',
            'role',
            'permission',
            'toko',
            'user',
            'kategori',
            'satuan',
            'gudang',
            'produk',
            'sales',
            'supplier',
            'customer',
            'pembelian',
            'penjualan',
            'pengurangan',
            'stocktransfer',
            'stock',
            'preorder',
            'transaksi',
            'quotation',
            'invoice'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

    }
}
