<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cod extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'location',
        'url_gmaps',
        'short_url_gmaps'
    ];
}
