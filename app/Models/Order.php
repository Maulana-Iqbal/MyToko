<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table='order';
    protected $guarded=[];

    public function transaksi(){
    	return $this->belongsTo(Transaksi::class);
    }

    public function stock(){
    	return $this->belongsTo(Stock::class);
    }

    public function produk(){
    	return $this->belongsTo(Produk::class);
    }

    public function gudang(){
    	return $this->belongsTo(Gudang::class);
    }
}
