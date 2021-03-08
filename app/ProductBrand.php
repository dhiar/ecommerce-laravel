<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Product, ProductCategory, CategoryBrand};

class ProductBrand extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'slug'
    ];

    public function category_brand(){
        return $this->hasOne(CategoryBrand::class, 'id_brand', 'id');
    }

    public function category()
    {
        return $this->belongsToMany(ProductCategory::class, 'category_brand', 'id_brand', 'id_category' );
    }
}
