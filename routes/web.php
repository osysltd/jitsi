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

use Illuminate\Http\Request;


$router->get('/', function () use ($router) {
    return view('index', [
        'data' => \App\Param::all(),
        'menu' => \App\Cat::orderBy('sort', 'asc')->get(),
        'users' => \App\User::all()->take(4)
    ]);
});

$router->get('/site/create', ['middleware' => 'auth', 'uses' => 'Controller@createEvent']);
$router->post('/site/savedetails',  ['middleware' => 'auth', function (Request $request) {
    try {
        $this->validate($request, ['text' => 'required|max:255']);
        Auth::user()->update(['text' => $request->text]);
    } catch (\Illuminate\Validation\ValidationException $th) { }
    return redirect()->to('/site/create');
}]);
$router->get('/site/changepw', ['middleware' => 'auth', function () use ($router) {
    \App\Prosody::setUserPassword(Auth::user()->id, Auth::user()->login);
    return redirect()->to('/site/create');
}]);

$router->get('/site/login', 'Controller@redirectToProvider');
$router->get('/site/authcallback', 'Controller@handleProviderCallback');
$router->get('/site/logout', function () use ($router) {
    Auth::logout();
    return redirect()->to('/');
});

$router->get('/robots.txt', 'HelperController@createRobots');
$router->get('/sitemap.xml', 'HelperController@createSitemap');
