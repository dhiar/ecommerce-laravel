<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Address, DeliveryStatus, TransactionDetail, Admin};

class Transaction extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'invoice',
        'shipping_cost',
        'total_weight',
        'total_price',
        'id_address',
        'id_admin_owner',
        'id_delivery_status',
        'phone_code',
        'phone_number',
        'phone_formatted',
        'token',
        'token_created_at'
    ];

    public function address(){
        return $this->belongsTo(Address::class, 'id_address', 'id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class, 'id_admin_owner', 'id');
    }

    public function delivery_status(){
        return $this->belongsTo(DeliveryStatus::class,'id_delivery_status','id');
    }

    public function transaction_details(){
        return $this->hasMany(TransactionDetail::class, 'id_transaction', 'id');
    }
}
