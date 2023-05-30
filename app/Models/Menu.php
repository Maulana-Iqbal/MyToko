<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;

class Menu extends Model
{
    use HasFactory;
    protected $table='menu';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;
}
