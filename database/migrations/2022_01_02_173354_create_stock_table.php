<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->integer('produk_id');
            $table->integer('jumlah');
            $table->double('harga');
            $table->double('harga_jual');
            $table->double('harga_grosir');
            // $table->integer('berat')->nullable();
            $table->text('deskripsi')->nullable();
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
        Schema::dropIfExists('stock');
    }
}
