<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_stock', function (Blueprint $table) {
            $table->id();
            $table->date('tgl')->nullable();
            $table->integer('gudang_id')->nullable();
            $table->integer('produk_id');
            $table->integer('pemasok_id')->default(0);
            $table->integer('sales_id')->default(0);
            $table->string('no')->nullable();
            $table->integer('jenis');
            //1. Tambah
            //2. Jual
            //3. Pengurangan
            //4. Ubah Harga
            //5. Transfer Dari
            //6. Transfer Ke
            //7. Dipinjamkan
            $table->integer('stock_awal');
            $table->integer('jumlah');
            $table->double('harga')->nullable();
            $table->double('harga_jual')->nullable();
            $table->double('harga_grosir')->nullable();
            $table->double('harga_modal_awal')->nullable();
            $table->double('harga_jual_awal')->nullable();
            $table->double('harga_grosir_awal')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('verifikasi')->default(1)->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('website_id');
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
        Schema::dropIfExists('history_stock');
    }
}
