<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
