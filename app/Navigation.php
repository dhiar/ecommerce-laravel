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
        return $this->belongsToMany(Page::class, 'footers', 'id_navigation', 'id_page')->withTimestamps();
    }
}
