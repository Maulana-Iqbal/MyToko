<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekening', function (Blueprint $table) {
            $table->id();
            $table->integer('akun_id');
            $table->string('nama_bank');
            $table->string('nama_rek');
            $table->string('no_rek');
            $table->integer('jenis_rek');
            //1 bank
            //2 e-wallet
            $table->integer('user_id');
            $table->integer('website_id');
            $table->integer('isActive');
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
        Schema::dropIfExists('rekening');
    }
}
