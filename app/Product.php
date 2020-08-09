<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Product_brand, Product_type};

class Product extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'id_product_type',
        'id_product_brand',
        'price',
        'stock',
        'description',
        'discount',
        'photo1',
        'photo2',
        'photo3',
        'photo4',
        'slug'
    ];

    public function product_brand(){
        return $this->belongsTo(Product_brand::class);
    }

    public function product_type(){
        return $this->belongsTo(Product_type::class);
    }
}
