<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\DeliveryStatus;

class Transaction extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'invoice',
        'email',
        'weight',
        'shipping_charges',
        'total',
        'alamat',
        'kabupaten',
        'propinsi',
        'kodepos',
        'id_delivery_status',
        'token',
        'token_created_at'
    ];

    public function delivery_status(){
        return $this->belongsTo(DeliveryStatus::class);
    }
}
