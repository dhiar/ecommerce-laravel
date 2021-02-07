<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'name'
    ];

    public function footers(){
        return $this->hasMany(Footer::class, 'id_footer', 'id');
    }
}
