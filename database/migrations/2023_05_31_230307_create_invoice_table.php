<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->date('tgl');
            $table->string('no_inv');
            $table->string('nomor');
            $table->string('kepada');
            $table->string('di');
            $table->text('pembuka');
            $table->text('penutup');
            $table->text('catatan')->nullable();
            $table->date('tgl_jatuh_tempo');
            $table->integer('status');
            //0 draft
            //1 dikirim
            //2 dibayar
            //3 selesai
            //4 batal
            //5 kedaluwarsa
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
        Schema::dropIfExists('invoice');
    }
}
