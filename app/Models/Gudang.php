<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class Gudang extends Model
{
    use HasFactory;
    protected $table='gudang';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;

    public function produk(){
    	return $this->hasMany(Produk::class)->withTrashed();
    }
    public function user(){
    	return $this->belongsTo(User::class);
    }
}
