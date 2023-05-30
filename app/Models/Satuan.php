<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class Satuan extends Model
{
    use HasFactory;
    protected $table='satuan';
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

    public function stock(){
    	return $this->hasMany(Stock::class);
    }
}
