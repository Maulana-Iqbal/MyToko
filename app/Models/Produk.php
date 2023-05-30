<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class Produk extends Model
{
    use HasFactory;
    protected $table='produk';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;
    public function stock(){
    	return $this->hasOne(Stock::class);
    }

    public function stock_jenis(){
    	return $this->hasMany(StockJenis::class);
    }

    public function kategori(){
    	return $this->belongsTo(Kategori::class)->withTrashed();
    }

    public function satuan(){
    	return $this->belongsTo(Satuan::class)->withTrashed();
    }

    public function gudang(){
    	return $this->belongsTo(Gudang::class)->withTrashed();
    }

    public function galeri(){
    	return $this->hasMany(Galeri::class,'data_id');
    }

    public function gudangTrans(){
    	return $this->hasMany(GudangTrans::class);
    }

    public function order(){
    	return $this->hasOne(Order::class);
    }

    public function website(){
    	return $this->belongsTo(Website::class);
    }
}
