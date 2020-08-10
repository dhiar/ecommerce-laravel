<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Transaction, Product};

class TransactionDetail extends Model
{
    protected $table = 'transaction_details';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'id_transaction',
        'id_product',
        'count',
        'subtotal_weight',
        'subtotal_price'
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
