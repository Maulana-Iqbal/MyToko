<?php

/*
 * This file is part of the IndoRegion package.
 *
 * (c) Azis Hapidin <azishapidin.com | azishapidin@gmail.com>
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Regency Model.
 */
class RegencyOngkir extends Model
{

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'regenciesongkir';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'province_id'
    ];

    /**
     * Regency belongs to Province.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provinceOngkir()
    {
        return $this->belongsTo(ProvinceOngkir::class);
    }

    

}
