<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DefaultData extends Model
{
    use HasFactory;
    protected $table='default';
    protected $guarded=[];

    public function website()
    {
        return $this->belongsTo(Website::class,'website_id');
    }
    public function gudang()
    {
        return $this->belongsTo(Gudang::class,'gudang_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Pemasok::class,'pemasok_id');
    }
    public function customer()
    {
        return $this->belongsTo(Pelanggan::class,'pelanggan_id');
    }
    public function sales()
    {
        return $this->belongsTo(Sales::class,'sales_id');
    }
}
