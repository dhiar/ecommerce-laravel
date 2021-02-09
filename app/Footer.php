<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\{Navigation, Page};

class Footer extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['page_id','navigation_id'];

    public function page(){
        return $this->belongsTo(Page::class,'page_id');
    }

    public function navigation(){
        return $this->belongsTo(Navigation::class,'navigation_id');
    }
}
