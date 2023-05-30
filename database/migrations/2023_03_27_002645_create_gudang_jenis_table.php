<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGudangJenisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gudang_jenis', function (Blueprint $table) {
            $table->id();
            $table->string('tgl');
            $table->integer('jenis');
            // 1. Masuk
            // 2. Keluar
            // 3. Hapus
            // 4. Return
            $table->integer('produk_id');
            $table->integer('gudang_id');
            $table->integer('stock_awal')->default(0)->nullable();
            $table->integer('jumlah');
            $table->integer('website_id');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gudang_jenis');
    }
}
