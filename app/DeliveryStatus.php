<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryStatus extends Model
{
    protected $table = 'delivery_status';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name'];
}
