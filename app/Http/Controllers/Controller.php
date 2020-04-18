<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PHPUnit\Util\Json;

class Controller extends BaseController
{
    /**
     * Sign Up for free event
     */

    public function eventSignup($id)
    {
        $event = \App\Event::findOrFail($id);
        if (!Session::get($event->url)) {
            Session::put($event->url, true);
            $event->cnt += 1;
            $event->save();
        }
        return redirect()->to('/site/event/' . $event->id);
    }

    /**
     * Create event with POST
     */
    public function createEvent(Request $request)
    {
        try {
            $this->validate(
                $request,
                ['title' => 'required|string|max:100'],
                ['price' => 'required|integer|max:5'],
                ['ywallet' => 'required|integer|min:20'],
                ['start' => 'required|date|before:end'],
                ['end' => 'required|date|after:start'],
                ['descr' => 'required|string|max:1500'],
            );

            $room_id = str_replace([':', ' '], '-', $request->start) . '_' . Auth::user()->login;
            $event = \App\Event::updateOrCreate(
                [
                    'user_id' => Auth::user()->id,
                    'url' => $room_id,
                    'start' => $request->start,
                ],
                [
                    'title' => $request->title,
                    'ywallet' => $request->ywallet,
                    'price' => $request->price,
                    'end' => $request->end,
                    'descr' => $request->descr
                ]
            );
        } catch (\Illuminate\Validation\ValidationException $th) {
            Session::flash('message', json_encode($th->errors()));
            return redirect()->to('/site/event');
        }
        \App\Prosody::setRoomPassword(Auth::user()->id, $room_id);
        return redirect()->to('/site/event/' . $event->id);
    }

    /**
     * List events for JavaScript Calendar
     * @return Json
     */
    public function listEvents(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');
        if ($start && $end) {
            $data = \App\Event::whereDate('start', '>=', $start)->whereDate('end', '<=', $end)->get([
                \DB::raw("CONCAT(LEFT(`descr`, 100), '...') as `description`"),
                \DB::raw("CONCAT('/site/event/', `id`) as `url`"),
                'id', 'title', 'start', 'end'
            ]);
            return response()->json($data);
        }
        return redirect()->to('/');
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
