<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Product, ProductCategory, ProductBrand};

class CategoryBrand extends Model
{
    protected $table = 'category_brand';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'id_category',
        'id_brand',
    ];

    public function category(){
        return $this->belongsTo(ProductCategory::class,'id_category','id');
    }

    public function brand(){
        return $this->belongsTo(ProductBrand::class,'id_brand','id');
    }

    public function products(){
        return $this->hasMany(Product::class, 'id_category_brand', 'id');
    }
}
