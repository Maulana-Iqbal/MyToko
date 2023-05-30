<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;
class Notifikasi extends Model
{
    use HasFactory;
    protected $table='notifikasi';
    protected $guarded=[];
    use Blameable;
}
