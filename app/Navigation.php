<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{Footer, Page};

use App\Transformers\PageTitleTransformer;

class Navigation extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'name'
    ];

    public function pages(){
        return $this->belongsToMany(Page::class, 'footers', 'navigation_id', 'page_id')->withTimestamps();
    }

    public function footers(){
        return $this->hasMany(Footer::class, 'navigation_id', 'id');
    }
}
