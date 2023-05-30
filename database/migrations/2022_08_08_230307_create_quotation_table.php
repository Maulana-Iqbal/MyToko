<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation', function (Blueprint $table) {
            $table->id();
            $table->string('no_quo');
            $table->string('kode_trans');
            $table->string('kepada');
            $table->string('di');
            $table->text('pembuka');
            $table->text('penutup');
            $table->text('catatan')->nullable();
            $table->date('tgl_dikeluarkan');
            $table->date('tgl_kedaluwarsa');
            $table->integer('status');
            //0 draft
            //1 dikirim
            //2 disetujui
            //3 Selesai
            //4 Ditolak
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
        Schema::dropIfExists('quotation');
    }
}
