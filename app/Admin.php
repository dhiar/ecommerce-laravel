<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\{UserTypes, Testimony};

class Admin extends Authenticatable
{
    use Notifiable, HasApiTokens;
    use SoftDeletes;

    protected $guard = 'admin';

    protected $dates = ['deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'id_user_type', 'email', 'password', 'job_title', 'phone', 'photo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function usertype(){
        return $this->belongsTo(UserTypes::class,'id_user_type');
    }

    /**
     * Get all of the admin's testimonys.
     */
    public function testimonys()
    {
        return $this->morphMany(Testimony::class, 'testimonyable');
    }
}
