<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Admin, User};

class Testimony extends Model
{
    protected $table = 'testimonys';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'content',
        'testimonyable_type',
        'testimonyable_id'
    ];

    /**
     * Get all of the models that own testimonys.
     */
    public function testimonyable()
    {
        return $this->morphTo();
    }
}
