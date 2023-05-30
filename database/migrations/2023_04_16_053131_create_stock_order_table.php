<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_order', function (Blueprint $table) {
            $table->id();
            $table->string('tgl');
            $table->string('jenis');
            // pembelian
            // sales
            // return pembelian
            // return sales
            // transfer
            $table->string('nomor')->unique();
            $table->integer('sales_id')->default(0)->nullable();
            $table->integer('pemasok_id')->default(0)->nullable();
            $table->integer('customer_id')->default(0)->nullable();
            $table->double('tax')->default(0)->nullable();
            $table->double('ppn')->default(0)->nullable();
            $table->double('pph')->default(0)->nullable();
            $table->double('diskon')->default(0)->nullable();
            $table->double('pengiriman')->default(0)->nullable();
            $table->double('biaya_lain')->default(0)->nullable();
            $table->double('total_harga')->nullable();
            //total harga*jumlah
            $table->double('total_biaya')->nullable();
            //total biaya*jumlah
            $table->double('order_total');
            //total harga+biaya*jumlah
            $table->double('total');
            //semua
            $table->double('bayar')->nullable();
            $table->double('kembalian')->default(0)->nullable();
            $table->string('status_order');
            $table->string('status_bayar');
            // 1 lunas
            // 2 belum lunas
            // 3 belum bayar
            $table->string('metode_bayar');
            $table->text('deskripsi')->nullable();
            $table->string('file_kirim')->nullable();
            $table->string('file_terima')->nullable();
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
        Schema::dropIfExists('stock_order');
    }
}
