<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->string('sumber');
            $table->integer('sumber_id');
            $table->date('tgl');
            $table->string('nomor');
            $table->integer('akun_id');
            $table->double('debit')->nullable();
            $table->double('kredit')->nullable();
            // $table->double('nominal');
            $table->string('deskripsi')->nullable();
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
        Schema::dropIfExists('kas');
    }
}
