<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{ProductCategory, ProductImage, Grosir, Admin};

class Product extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'id_product_category',
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
        'viewer',
    ];

    public function category(){
        return $this->belongsTo(ProductCategory::class, 'id_product_category', 'id');
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
