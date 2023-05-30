<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockJenisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_jenis', function (Blueprint $table) {
            $table->id();
            $table->string('tgl');
            $table->string('jenis');
            // masuk
            // keluar
            // rusak
            $table->string('nomor')->nullable();
            // $table->integer('sales_id')->default(0);
            // $table->integer('pemasok_id')->default(0);
            $table->integer('produk_id');
            $table->integer('gudang_id')->default(0);
            $table->integer('stock_awal')->default(0);
            $table->integer('jumlah');
            $table->double('harga');
            $table->double('harga_jual');
            $table->double('harga_grosir')->nullable();
            $table->double('harga_final')->nullable();
            $table->double('biaya')->nullable();
            $table->integer('grosir');
            //1. ya
            //2. tidak
            $table->integer('valid');
            //1. valid
            //2. tidak valid
            $table->string('kondisi')->nullable();
            $table->text('deskripsi')->nullable();
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
        Schema::dropIfExists('stock_jenis');
    }
}
