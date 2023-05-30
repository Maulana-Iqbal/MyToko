<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class SupplierTrans extends Model
{
    use HasFactory;
    protected $table='supplier_trans';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;
    

    public function produk(){
    	return $this->belongsTo(Produk::class)->withTrashed();
    }

    public function gudang(){
    	return $this->belongsTo(Gudang::class)->withTrashed();
    }

    
}
