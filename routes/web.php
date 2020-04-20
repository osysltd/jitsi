<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
*/

use Illuminate\Http\Request;

$router->get('/', function () use ($router) {
    return view('index', [
        'data' => \App\Param::all(),
        'menu' => \App\Cat::orderBy('sort', 'asc')->get(),
        'users' => \App\User::where('fav', 1)->orderBy('created_at', 'asc')->get()
    ]);
});

/**
 * Profile and Events
 */

// GET: List events for Calendar
$router->get('/site/list', 'Controller@listEvents');

// GET: Event information
$router->get('/site/event/{id}', 'Controller@viewEvent');

// GET: Event creation and Profile update
$router->get('/site/event', ['middleware' => 'auth', function () use ($router) {
    return view('event', [
        'data' => \App\Param::all(),
        'menu' => \App\Cat::orderBy('sort', 'asc')->get(),
    ]);
}]);

// POST: Event creation
$router->post('/site/event', ['middleware' => 'auth', 'uses' => 'Controller@createEvent']);

// POST: Profile information update
$router->post('/site/profile',  ['middleware' => 'auth', function (Request $request) {
    try {
        $this->validate($request, ['text' => 'required|string|max:255']);
        Auth::user()->update(['text' => $request->text]);
    } catch (\Illuminate\Validation\ValidationException $th) {
        Session::flash('message', $th->getMessage());
    }
    return redirect()->to('/site/event');
}]);

// GET: Profile new password generate
$router->get('/site/password', ['middleware' => 'auth', function () use ($router) {
    \App\Prosody::setUserPassword(Auth::user()->id);
    return redirect()->to('/site/event');
}]);

/**
 * Payment
 * POST: Signup for Event
 * GET: Signup for free event
 * POST: Payment callback
 */
$router->post('/site/signup/{id}',  'HelperController@payCreate');
$router->get('/site/signup/{id}',  'Controller@eventSignup');
$router->get('/site/paycallback/{id}/{hash}',  'HelperController@payCallback');

/**
 * Authentication routes
 */
$router->get('/site/login', 'Controller@redirectToProvider');
$router->get('/site/authcallback', 'Controller@handleProviderCallback');
$router->get('/site/logout', function () use ($router) {
    Auth::logout();
    return redirect()->to('/');
});

/**
 * Service routes
 */
//$router->get('/robots', 'HelperController@createRobots');
$router->get('/robots.txt', 'HelperController@createRobots');
$router->get('/sitemap.xml', 'HelperController@createSitemap');
