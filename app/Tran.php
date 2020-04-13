<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tran extends Model
{
    public function events()
    {
        return $this->belongsToMany('App\Event');
    }
}
