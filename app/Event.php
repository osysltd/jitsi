<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function users()
    {
        return $this->belongsTo('App\User');
    }

    public function cats()
    {
        return $this->belongsTo('App\Cat');
    }

    public function trans()
    {
        return $this->belongsToMany('App\Tran');
    }
}
