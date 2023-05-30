<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBayarUpahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bayar_upah', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('nomor')->nullable();
            $table->date('tgl');
            $table->date('awal');
            $table->date('akhir');
            $table->integer('dari')->nullable();
            $table->integer('akun_id')->nullable();
            $table->double('jumlah');
            $table->double('sisa',20)->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('persetujuan',10);
            $table->string('file')->nullable();
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
        Schema::dropIfExists('bayar_upah');
    }
}
