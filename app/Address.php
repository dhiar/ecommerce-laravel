<?php

namespace App;
use App\{Transaction, User};

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    // public function transactions()
    // {
    //     return $this->belongsToMany(Transaction::class)->withTimestamps()->withPivot(['invoice']);
    // }
}
