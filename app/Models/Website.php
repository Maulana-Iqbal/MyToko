<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;

class Website extends Model
{
    use HasFactory;
    protected $table='website';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;

    public function webprovinsi(){
    	return $this->belongsTo(ProvinceOngkir::class,'provinsi');
    }

    public function webkota(){
    	return $this->belongsTo(RegencyOngkir::class,'kota');
    }

    public function webkecamatan(){
    	return $this->belongsTo(DistrictOngkir::class,'kecamatan');
    }

    public function user(){
        return $this->belongsTo(Website::class);
    }

    public function rekening(){
        return $this->hasMany(Rekening::class);
    }

    public function prefix(){
        return $this->belongsTo(Prefix::class,'id','website_id');
    }

    public function default(){
        return $this->belongsTo(DefaultData::class,'id','website_id');
    }
    
}