<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{User, Menu};
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTypes extends Model
{
    use SoftDeletes;

    protected $table = 'user_types';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
    
    public function users(){
        return $this->hasMany(User::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class)->withTimestamps()->withPivot(['name']);
    }
}
