<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/test/session-put', function (\Illuminate\Http\Request $request) {
    $request->session()->put('name', config('app.name'));
    return response()->json([
        'session.name' => $request->session()->get('name')
    ]);
});

$router->get('/test/session-get', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'session.name' => $request->session()->get('name'),
    ]);
});

$router->get('/test/cookie-set', function () {
    $response = new Illuminate\Http\Response('Cookie name: name-of-cookie');
    $response->withCookie(new Symfony\Component\HttpFoundation\Cookie(
        'name-of-cookie',
        'value-of-cookie',
        '2147483647'
    ));
    return $response;
});

$router->get('/test/cookie-get', function (\Illuminate\Http\Request $request) {
    dd($request->cookie('name-of-cookie'));
});

$router->get('/test/csrf', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'csrf_token' => $request->session()->get('_token'),
    ]);
});

$router->get('/test/user-login', function (\Illuminate\Http\Request $request) {
    $user = new Illuminate\Auth\GenericUser(['id' => 1, 'name' => 'User']);
   Auth::login($user);
    if (Auth::check()) {
        return 'User has been authenticated';
    }
});

$router->get('/test/user-check', function (\Illuminate\Http\Request $request) {
    if (Auth::check()) {
        return 'SQL exception expected if the user id has been resolved from session';
    }
});

$router->get('/test/user-midware', ['middleware' => 'auth', function (Request $request, $id) {
    $user = new Illuminate\Auth\GenericUser(['id' => 1, 'name' => 'User']);
    Auth::login($user);
    $user = Auth::user();
    return 'SQL exception expected if the user id has been resolved from session';
}]);


$router->get('/test/oauth-callback', function (\Illuminate\Http\Request $request) {
    $user = Laravel\Socialite\Facades\Socialite::driver('yandex')->user();
    Auth::login($user);
    if (Auth::check()) {
        return redirect()->to('/index');
    }
});

$router->get('/test/oauth-redirect', function (\Illuminate\Http\Request $request) {
    // Lumen providers will automatically be stateless
    return Laravel\Socialite\Facades\Socialite::with('yandex')->stateless(false)->redirect();
});

