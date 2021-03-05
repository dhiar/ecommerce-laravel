<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Product, CategoryBrand};

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
}
