<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Footer;

class Page extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['title','content','slug'];

    public function footer(){
        return $this->hasOne(Footer::class, 'page_id', 'id');
    }
}
