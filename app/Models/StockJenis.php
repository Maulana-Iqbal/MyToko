<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class StockJenis extends Model
{
    use HasFactory;
    protected $table='stock_jenis';
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

    public function gudang(){
    	return $this->belongsTo(Gudang::class);
    }

    public function stock_order(){
    	return $this->belongsTo(StockOrder::class,'nomor','nomor');
    }

    public function stock_transfer(){
    	return $this->belongsTo(StockTransfer::class,'id','stock_jenis_id');
    }

}
