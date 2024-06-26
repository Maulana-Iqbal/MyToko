<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Pembayaran extends Model
{
    use HasFactory;
    protected $table='pembayaran';
    protected $guarded=[];
    public function transaksi(){
    	return $this->belongsTo(Transaksi::class,'kode_trans','kode_trans');
    }

    
}
