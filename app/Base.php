<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'shop_name',
        'province_id',
        'city_id',
        'district_id',
        'address',
        'phone',
        'description',
        'logo'
    ];
}
