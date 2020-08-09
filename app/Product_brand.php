<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Product_brand extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'name'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
