<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class StockOrder extends Model
{
    use HasFactory;
    protected $table='stock_order';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;

    protected $hidden = [
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_at',
    ];
    

    public function produk(){
    	return $this->belongsTo(Produk::class);
    }

    public function supplier(){
    	return $this->belongsTo(Pemasok::class,'pemasok_id');
    }
    
    public function sales(){
    	return $this->belongsTo(Sales::class,'sales_id');
    }

    public function gudang(){
    	return $this->belongsTo(Gudang::class);
    }

    public function pemasok(){
    	return $this->belongsTo(Pemasok::class);
    }

    public function pelanggan(){
    	return $this->belongsTo(Pelanggan::class,'customer_id');
    }

    public function stock_jenis(){
    	return $this->hasMany(StockJenis::class,'nomor','nomor');
    }

    public function shipping(){
    	return $this->hasOne(Shipping::class,'transaksi_id','id');
    }

}
