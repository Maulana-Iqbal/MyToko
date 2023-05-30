<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $table='shipping';
    protected $guarded=[];
    use Blameable;
    public function transaksi(){
    	return $this->belongsTo(Transaksi::class);
    }

}
