<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Transfer extends Model
{
    use HasFactory;
    protected $table='transfer';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;

    public function rekening(){
       return $this->belongsTo(Rekening::class);
    }

    public function akun(){
        return $this->belongsTo(Akun::class);
     }

     public function akun_dari(){
        return $this->belongsTo(Akun::class,'dari');
     }
}
