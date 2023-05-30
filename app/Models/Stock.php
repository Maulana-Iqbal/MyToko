<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class Stock extends Model
{
    use HasFactory;
    protected $table='stock';
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
    public function satuan(){
    	return $this->belongsTo(Satuan::class);
    }

    public function order(){
    	return $this->hasMany(Order::class);
    }

    public function historyStock(){
    	return $this->hasMany(HistoryStock::class);
    }

    public function produk(){
    	return $this->belongsTo(Produk::class);
    }

}
