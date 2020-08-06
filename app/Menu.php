<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name'];
}
