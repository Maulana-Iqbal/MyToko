<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_trans');
            $table->date('tgl_bayar');
            $table->double('jml_bayar');
            $table->integer('metode_bayar');
            //1 cash
            //2 bank transfer
            //3 virtual akun
            $table->string('nama_bank')->nullable();
            $table->string('nama_rek')->nullable();
            $table->string('no_rek')->nullable();
            $table->integer('status_bayar');
            //1 belum lunas
            //2 lunas
            $table->integer('verifikasi');
            //1 menunggu verifikasi
            //2 distujui
            //3 ditolak
            $table->string('file')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('pembayaran');
    }
}
