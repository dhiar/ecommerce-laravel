<?php

namespace App;
use App\{Transaction, User, Admin};

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name','province_id','city_id','district_id'];

    // public function transactions()
    // {
    //     return $this->belongsToMany(Transaction::class)->withTimestamps()->withPivot(['invoice']);
    // }

    public function user(){
        return $this->hasOne(User::class, 'id_address', 'id');
    }

    public function admin(){
        return $this->hasOne(Admin::class, 'id_address', 'id');
    }
}
