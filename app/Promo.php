<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'promo_time',
        'is_active'
    ];
}
