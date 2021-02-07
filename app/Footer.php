<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\{Navigation, Page};

class Footer extends Model
{
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['id_page','id_navigation'];

    public function page(){
        return $this->belongsTo(Page::class,'id_page');
    }

    public function navigation(){
        return $this->belongsTo(Navigation::class,'id_navigation');
    }
}
