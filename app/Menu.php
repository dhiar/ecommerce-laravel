<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Sub_menu, UserTypes};

class Menu extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function usertypes()
    {
        return $this->belongsToMany(UserTypes::class)->withTimestamps()->withPivot(['name']);
    }

    public function submenus()
    {
        return $this->hasMany(Sub_menu::class)->withTimestamps()->withPivot(['name']);
    }
}
