<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class Sales extends Model
{
    use HasFactory;
    protected $table='sales';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

}
