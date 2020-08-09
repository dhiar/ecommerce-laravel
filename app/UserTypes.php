<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserTypes extends Model
{
    protected $table = 'user_types';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function users(){
        return $this->hasMany(User::class);
    }
}
