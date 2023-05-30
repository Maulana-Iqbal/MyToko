<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kas extends Model
{
    use HasFactory;
    use Blameable;
    use SoftDeletes;


    protected $table='kas';
    protected $guarded=[];
    protected $hidden = [
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_at',
    ];

    public function rekening(){
       return $this->belongsTo(Rekening::class);
    }

    public function akun(){
        return $this->belongsTo(Akun::class);
     }




}
