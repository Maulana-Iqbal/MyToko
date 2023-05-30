<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping', function (Blueprint $table) {
            $table->id();
            $table->integer('transaksi_id');
            $table->string('resi')->nullable();
            $table->string('kurir')->nullable();
            $table->string('biaya')->nullable();
            $table->string('file')->nullable();
            $table->integer('ditanggung')->nullable();
            //1 Pembeli
            //2 Penjual
            //3 Gratis
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
        Schema::dropIfExists('shipping');
    }
}
