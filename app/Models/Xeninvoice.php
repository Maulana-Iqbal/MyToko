<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Xeninvoice extends Model
{
    use HasFactory;
    protected $table='xeninvoice';
    protected $guarded=[];

    public function transaksi(){
    	return $this->belongsTo(Transaksi::class);
    }


}
