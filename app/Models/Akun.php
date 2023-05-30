<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class Akun extends Model
{
    use HasFactory;
    protected $table='akun';
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
    public function pengeluaran(){
    	return $this->hasMany(Pengeluaran::class);
    }

    public function penerimaan(){
    	return $this->hasMany(Penerimaan::class);
    }

    public function kas(){
    	return $this->hasMany(Kas::class);
    }


    public function kategori_akun(){
    	return $this->belongsTo(KategoriAkun::class);
    }

}
