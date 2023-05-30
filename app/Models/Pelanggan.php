<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;
class Pelanggan extends Model
{
    use HasFactory;
    protected $table='pelanggan';
    protected $guarded=[];
    use Blameable;
    public function transaksi(){
    	return $this->hasMany(Transaksi::class);
    }

    public function provinsi(){
        return $this->belongsTo(ProvinceOngkir::class,'provinsi_id');
    }

    public function kota(){
        return $this->belongsTo(RegencyOngkir::class,'kota_id');
    }

    public function kecamatan(){
        return $this->belongsTo(DistrictOngkir::class,'kecamatan_id');
    }
    
}
