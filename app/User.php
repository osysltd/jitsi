<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            \App\Prosody::setUserPassword($model->id, $model->login);
        });
    }


    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'oauth_id', 'login', 'nickname', 'name', 'email', 'avatar', 'birthday', 'sex', 'text', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token', 'password', 'token', 'refreshToken', 'expiresIn'
    ];

    /**
     * Get all of the user events.
     */

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    /**
     * Get all of the user recoeds.
     */

    public function prosodys()
    {
        return $this->hasMany('App\Prosody');
    }


    public function password()
    {
        $match = [
            'host' => env('APP_PROSODY_HOST', app('Illuminate\Http\Request')->getHost()),
            'store' => 'accounts', 'type' => 'string', 'key' => 'password'
        ];
        return $this->hasOne('App\Prosody')->where($match);
    }


    /*
    * Part of code has been copied from Laravel\Socialite\Two\User
    * @path \vendor\laravel\socialite\src\Two\User.php
    */

    /**
     * The user's access token.
     *
     * @var string
     */
    public $token;

    /**
     * The refresh token that can be exchanged for a new access token.
     *
     * @var string
     */
    public $refreshToken;

    /**
     * The number of seconds the access token is valid for.
     *
     * @var int
     */
    public $expiresIn;

    /**
     * Set the token on the user.
     *
     * @param  string  $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Set the refresh token required to obtain a new access token.
     *
     * @param  string  $refreshToken
     * @return $this
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Set the number of seconds the access token is valid for.
     *
     * @param  int  $expiresIn
     * @return $this
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }
}
