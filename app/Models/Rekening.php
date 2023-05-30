<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class Rekening extends Model
{
    use HasFactory;
    protected $table='rekening';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;

    protected $hidden = [
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_at',
    ];

    public function produk(){
    	return $this->hasMany(Produk::class);
    }

    public function stock(){
    	return $this->hasMany(Stock::class);
    }

    public function website(){
        return $this->belongsTo(Website::class);
    }

    public function kas(){
       return $this->hasMany(Kas::class);
    }

    public function pengeluaran(){
       return $this->hasMany(Pengeluaran::class);
    }
}
