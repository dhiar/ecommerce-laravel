<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Address, DeliveryStatus, TransactionDetail, User};

class Transaction extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'invoice',
        'shipping_charges',
        'total_weight',
        'total_price',
        'id_address',
        'id_user',
        'id_delivery_status',
        'token',
        'token_created_at'
    ];

    public function address(){
        return $this->belongsTo(Address::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function delivery_status(){
        return $this->belongsTo(DeliveryStatus::class);
    }

    public function transaction_details()
    {
        return $this->belongsToMany(TransactionDetail::class)->withTimestamps()->withPivot(['subtotal_price']);
    }
}
