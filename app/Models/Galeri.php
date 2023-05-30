<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;
class Galeri extends Model
{
    use HasFactory;
    protected $table='gambar';
    protected $guarded=[];

    protected $hidden = [
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_at',
    ];

    use Blameable;
    public function produk(){
    	return $this->belongsTo(Produk::class);
    }
}
