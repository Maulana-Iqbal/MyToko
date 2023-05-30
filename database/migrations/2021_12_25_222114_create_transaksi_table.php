<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_trans');
            $table->string('tgl_trans',20);
            $table->integer('pelanggan_id')->nullable();
            $table->double('totalModal');
            $table->double('totalBiaya');
            $table->double('subtotal');
            $table->double('ppn')->nullable();
            $table->double('pph')->nullable();
            $table->double('diskon')->nullable();
            $table->double('biayaLain')->nullable();
            $table->double('total');
            $table->string('deskripsi')->nullable();
            $table->integer('status_trans');
            $table->integer('jenis_trans');
            $table->integer('jenis_bayar');
            $table->integer('sales_id')->nullable();
            $table->integer('website_id');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('transaksi');
    }
}
