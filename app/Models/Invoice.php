<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;
class Invoice extends Model
{
    use HasFactory;
    use Blameable;
    protected $table='invoice';
    protected $guarded=[];

    public function transaksi(){
        return $this->belongsTo(StockOrder::class,'nomor','nomor');
    }
}
