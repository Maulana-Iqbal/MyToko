<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;
    protected $table='stock_transfer';
    protected $guarded=[];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    

    public function produk(){
    	return $this->belongsTo(Produk::class);
    }

    public function dari_gudang(){
    	return $this->belongsTo(Gudang::class,'dari');
    }

    public function ke_gudang(){
    	return $this->belongsTo(Gudang::class,'ke');
    }
    

}
