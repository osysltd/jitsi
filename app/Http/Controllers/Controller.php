<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    public function createEvent()
    {
        return view('create', [
            'data' => \App\Param::all(),
            'menu' => \App\Cat::orderBy('sort', 'asc')->get(),
        ]);
    }

    /*
    * Additional configuration for Oauth2
    * php.ini [curl] section
    * Download https://curl.haxx.se/ca/cacert.pem
    * Set value for the CURLOPT_CAINFO option an absolute path to downloaded file, ex.
    * curl.cainfo = "path\to\app\resources\cacert.pem"
    */

    public function redirectToProvider()
    {
        // Lumen providers will automatically be stateless
        return Socialite::with('yandex')->stateless(true)->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('yandex')->user();
        Auth::login($user);
        if (Auth::check()) {
            return redirect()->to('/');
        }
    }
}
