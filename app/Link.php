<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
//    protected $fillable = array(
//        'link',
//        'short_link'
//    );

    protected $guarded = array();

    public function user() {
        $this->belongsTo('App\User');
    }
}
