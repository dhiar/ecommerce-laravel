<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{ProductCategory, Admin};

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
        'description',
        'is_published',
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
}
