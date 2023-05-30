<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelangganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('nama_depan',50);
            $table->string('nama_belakang',50);
            $table->string('perusahaan',100)->nullable();
            $table->string('email',100);
            $table->string('telpon',20)->nullable();
            $table->string('hp',20)->nullable();
            $table->string('provinsi_id',3)->nullable();
            $table->string('kota_id',10)->nullable();
            $table->string('kecamatan_id',10)->nullable();
            $table->string('desa_id',10)->nullable();
            $table->string('pos',15)->nullable();
            $table->text('alamat');
            $table->text('logo')->nullable();
            $table->integer('status_data')->nullable();
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
        Schema::dropIfExists('pelanggan');
    }
}
