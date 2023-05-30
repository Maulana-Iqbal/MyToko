<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Penerimaan extends Model
{
    use HasFactory;
    protected $table='penerimaan';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;

    public function rekening(){
       return $this->belongsTo(Rekening::class);
    }

    public function akun(){
        return $this->belongsTo(Akun::class);
     }

     public function akun_ke(){
        return $this->belongsTo(Akun::class,'ke');
     }
}
