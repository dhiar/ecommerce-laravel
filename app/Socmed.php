<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socmed extends Model
{
    protected $table = 'socmed';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'icon',
        'link'
    ];
}
