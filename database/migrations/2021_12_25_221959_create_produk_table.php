<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk',50);
            $table->string('nama_produk');
            $table->string('merek',50)->nullable();
            $table->string('slug');
            $table->integer('kategori_id');
            $table->integer('satuan_id');
            $table->integer('berat')->nullable();
            $table->integer('min_stock')->default(0)->nullable();
            $table->integer('min_order')->nullable();
            $table->integer('max_order')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('gambar_utama');
            $table->integer('dilihat')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('website_id');
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
        Schema::dropIfExists('produk');
    }
}
