<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'rekening',
        'name',
        'number'
    ];
}
