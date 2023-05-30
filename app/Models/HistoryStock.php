<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryStock extends Model
{
    use HasFactory;
    protected $table='history_stock';
    protected $guarded=[];
    use Blameable;
    public function produk(){
    	return $this->belongsTo(Produk::class);
    }

    public function gudang(){
    	return $this->belongsTo(Gudang::class);
    }

}
