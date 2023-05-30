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
 * Province Model.
 */
class ProvinceOngkir extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'provincesongkir';

    /**
     * Province has many regencies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function regencies()
    {
        return $this->hasMany(RegencyOngkir::class);
    }

    public function websiteprovinsi(){
    	return $this->hasOne(ProvinceOngkir::class,'provinsi');
    }

}
