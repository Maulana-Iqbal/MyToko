<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class Pemasok extends Model
{
    use HasFactory;
    protected $table='pemasok';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function historyStock()
    {
        return $this->hasMany(HistoryStock::class);
    }


}
