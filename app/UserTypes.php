<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTypes extends Model
{
    protected $table = 'user_types';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name'];
}
