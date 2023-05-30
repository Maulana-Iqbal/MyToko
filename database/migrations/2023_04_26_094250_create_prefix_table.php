<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prefix', function (Blueprint $table) {
            $table->id();
            $table->string('produk')->unique();
            $table->string('gudang')->unique();
            $table->string('sales')->unique();
            $table->string('pemasok')->unique();
            $table->string('pembelian')->unique();
            $table->string('penjualan')->unique();
            $table->string('pengurangan')->unique();
            $table->string('stocktransfer')->unique();
            $table->string('preorder')->unique();
            $table->integer('website_id')->unique();
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
        Schema::dropIfExists('prefix');
    }
}
