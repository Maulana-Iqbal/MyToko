<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class KategoriAkun extends Model
{
    use HasFactory;
    protected $table='kategori_akun';
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

    public function akun(){
    	return $this->hasMany(Akun::class);
    }

}
