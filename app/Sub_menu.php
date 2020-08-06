<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sub_menu extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['id_menu','title', 'url', 'icon', 'active'];
}
