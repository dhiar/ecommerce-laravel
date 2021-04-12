<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\{Address, UserTypes, Transaction, Testimony};
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'gender', 'id_address', 'id_user_type', 'phone', 'photo', 'verification_code', 'is_verified'
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
        return $this->belongsTo(UserTypes::class,'id_user_type', 'id');
    }

    public function address(){
        return $this->hasOne(Address::class, 'id_address');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class)->withTimestamps()->withPivot(['invoice']);
    }

    /**
     * Get all of the user's testimonys.
     */
    public function testimonys()
    {
        return $this->morphMany(Testimony::class, 'testimonyable');
    }
}
