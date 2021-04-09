<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Address, Admin, User, Rekening};

class Rekening extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'rekening',
        'name',
        'number'
    ];

    public function admin()
    {
        return $this->belongsToMany(Admin::class, 'admin_rekening', 'id_rekening', 'id_admin')->withTimestamps();
    }
}
