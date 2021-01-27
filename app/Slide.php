<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'url',
        'image',
        'active'
    ];
}
