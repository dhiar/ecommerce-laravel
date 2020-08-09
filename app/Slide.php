<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'id_menu',
        'title',
        'url',
        'icon',
        'active'
    ];
}
