<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;
class Quotation extends Model
{
    use HasFactory;
    use Blameable;
    protected $table='quotation';
    protected $guarded=[];

    public function transaksi(){
        return $this->belongsTo(StockOrder::class,'kode_trans','nomor');
    }
}
