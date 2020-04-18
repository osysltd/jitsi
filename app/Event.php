<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'url', 'price', 'ywallet', 'start', 'end', 'descr'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function cat()
    {
        return $this->belongsTo('App\Cat');
    }

    public function tran()
    {
        return $this->hasMany('App\Tran');
    }

    public function getPasswordAttribute()
    {
        $match = [
          'user' => $this->url, 'host' => 'conference.' . env('PROSODY_HOST', app('Illuminate\Http\Request')->getHost()),
            'store' => 'muc_management', 'type' => 'string', 'key' => 'password'
        ];
        return \App\Prosody::where($match)->firstOrFail()->value;
    }
}
