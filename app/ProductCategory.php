<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{ProductCategory, ProductBrand, CategoryBrand};

class ProductCategory extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'slug',
        'icon'
    ];

    public function category_brands(){
        return $this->hasMany(CategoryBrand::class, 'id_category', 'id');
    }

    public function brands()
    {
        return $this->belongsToMany(ProductBrand::class, 'category_brand', 'id_category', 'id_brand')->withTimestamps();
    }
}
