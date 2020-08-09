<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transaction;

class DeliveryStatus extends Model
{
    protected $table = 'delivery_status';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class)->withTimestamps()->withPivot(['invoice']);
    }
}
