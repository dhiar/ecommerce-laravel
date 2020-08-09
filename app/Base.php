<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'shop_name',
        'province',
        'city',
        'address',
        'phone',
        'description',
        'logo',
        'facebook',
        'twitter',
        'linkedin',
        'youtube',
        'rajaongkir',
        'midtrans'
    ];
}
