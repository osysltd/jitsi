<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tran extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['event_id', 'session', 'to', 'amount'];

    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
