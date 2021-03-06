<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Product, CategoryBrand};

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
}
