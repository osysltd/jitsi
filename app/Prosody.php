<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Prosody extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prosody';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'sort_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'user', 'host', 'store', 'type', 'key', 'value'];

    /**
     * Sets the user password.
     *
     * @return void
     */
    public static function setUserPassword($id, $login)
    {
        if ((bool) $id && (bool) $login) {
            $match = [
                'user_id' => $id, 'user' => $login,
                'host' => env('APP_PROSODY_HOST', app('Illuminate\Http\Request')->getHost()),
                'store' => 'accounts', 'type' => 'string', 'key' => 'password'
            ];
            $model = self::updateOrCreate($match, ['value' => self::generatePassword()]);
        } else {
            throw new ModelNotFoundException('User setUserPassword <' . $login . '> id: ' . $id);
        }
    }

    /**
     * Generates the user password.
     *
     * @return string
     */
    private static function generatePassword()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
