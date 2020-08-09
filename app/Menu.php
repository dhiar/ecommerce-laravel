<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserTypes;

class Menu extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function usertypes()
    {
        return $this->belongsToMany(UserTypes::class)->withTimestamps()->withPivot(['name']);
    }
}
