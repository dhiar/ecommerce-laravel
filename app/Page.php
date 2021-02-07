<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Footer;

class Page extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['title','content','slug'];

    public function footers(){
        return $this->hasOne(Footer::class, 'id_footer', 'id');
    }
}
