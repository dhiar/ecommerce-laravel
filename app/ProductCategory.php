<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class ProductCategory extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'slug',
        'icon'
    ];

    public function products(){
        return $this->hasMany(Product::class, 'id_product_category', 'id');
    }
}
