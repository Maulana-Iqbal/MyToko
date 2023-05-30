<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table='transaksi';
    protected $guarded=[];
    use Blameable;
    public function pelanggan(){
    	return $this->belongsTo(Pelanggan::class);
    }

    public function order(){
    	return $this->hasMany(Order::class,'trans_kode','kode_trans');
    }

    public function pembayaran(){
    	return $this->hasMany(Pembayaran::class,'kode_trans','kode_trans');
    }

    public function shipping(){
    	return $this->hasOne(Shipping::class);
    }

    public function xeninvoice(){
    	return $this->hasOne(Xeninvoice::class);
    }

    public function pajak(){
        return $this->hasMany(Pajak::class);
    }

    public function sales(){
        return $this->belongsTo(Sales::class);
    }

}
