<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class ProductImage extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'id_product',
        'image'
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'id_product', 'id');
    }
}
