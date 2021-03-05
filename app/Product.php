<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{CategoryBrand, ProductImage, Grosir, Admin};
use Spatie\Tags\HasTags;

class Product extends Model
{
    use HasTags;

    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'id_category_brand',
        'id_admin',
        'name',
        'image',
        'price',
        'weight',
        'stock',
        'condition',
        'description',
        'is_published',
        'is_promo',
        'slug',
        'transaction',
        'promo_price',
        'count_view',
        'count_like',
    ];

    public function category_brand(){
        return $this->belongsTo(ProductCategory::class, 'id_category_brand', 'id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class, 'id_admin', 'id');
    }

    public function images(){
        return $this->hasMany(ProductImage::class, 'id_product', 'id');
    }

    public function grosirs(){
        return $this->hasMany(Grosir::class, 'id_product', 'id');
    }
}
