<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Permission extends Model
{
    use HasFactory;
    protected $table='permissions';
    protected $guarded=[];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function group(){
    	return $this->belongsTo(Permission::class,'induk_id','id');
    }

    public function sub(){
    	return $this->hasMany(Permission::class,'induk_id','id');
    }

}
