<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Slide extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'url',
        'description',
        'image',
        'active',
        'id_product'
    ];

    public function product(){
        return $this->belongsTo(Product::class,'id_product','id');
    }
}
