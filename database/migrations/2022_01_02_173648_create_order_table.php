<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('trans_kode',20);
            $table->integer('produk_id');
            $table->integer('gudang_id')->nullable();
            $table->double('biaya');
            $table->double('harga_modal');
            $table->double('harga_jual');
            $table->integer('jumlah');
            $table->double('total');
            $table->string('grosir')->default('tidak');
            $table->integer('status_data')->nullable();
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
        Schema::dropIfExists('order');
    }
}
