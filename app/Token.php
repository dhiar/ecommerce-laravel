<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'email',
        'token'
    ];
}
