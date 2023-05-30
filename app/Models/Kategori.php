<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class Kategori extends Model
{
    use HasFactory;
    protected $table='kategori';
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
    	return $this->hasMany(Produk::class);
    }

    public function portofolio(){
    	return $this->hasMany(Portofolio::class);
    }

    public function group(){
    	return $this->belongsTo(Kategori::class,'induk_id','id');
    }
}
