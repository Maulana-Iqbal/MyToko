<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Jurnal extends Model
{
    use HasFactory;
    protected $table='jurnal';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;


    public function akun(){
        return $this->belongsTo(Akun::class);
     }

}
