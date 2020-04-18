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
     * Generates the user password.
     *
     * @return string
     */
    private static function generatePassword($length)
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < (int) $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    /**
     * Sets the user password.
     *
     * @return Prosody
     */
    public static function setUserPassword($user_id)
    {
        $user = \App\User::findOrFail($user_id);
        $match = [
            'user_id' => $user->id, 'user' => $user->login,
            'host' => env('PROSODY_HOST', app('Illuminate\Http\Request')->getHost()),
            'store' => 'accounts', 'type' => 'string', 'key' => 'password'
        ];
        return self::updateOrCreate($match, ['value' => self::generatePassword(10)]);
    }

    /**
     * Sets the room password.
     *
     * @return Prosody
     */
    public static function setRoomPassword($user_id, $room_id)
    {
        $user = \App\User::findOrFail($user_id);
        $match = [
            'user_id' => $user->id, 'user' => $room_id,
            'host' => 'conference.'.env('PROSODY_HOST', app('Illuminate\Http\Request')->getHost()),
            'store' => 'muc_management', 'type' => 'string', 'key' => 'password'
        ];
        return self::updateOrCreate($match, ['value' => self::generatePassword(8)]);
    }
}
