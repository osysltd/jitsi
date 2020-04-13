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
    return view('index', [
        'data' => \App\Param::all(),
        'menu' => \App\Cat::orderBy('sort', 'asc')->get(),
        'users' => \App\User::all()->take(4)
    ]);
});

$router->get('/site/create', ['middleware' => 'auth', 'uses' => 'Controller@createEvent']);
$router->post('/site/create', ['middleware' => 'auth', 'uses' => 'Controller@createEvent']);

$router->get('/site/login', 'Controller@redirectToProvider');
$router->get('/site/authcallback', 'Controller@handleProviderCallback');
$router->get('/site/logout', ['middleware' => 'auth', function () use ($router) {
    Auth::logout();
    return redirect()->to('/');
}]);

$router->get('/robots.txt', 'HelperController@createRobots');
$router->get('/sitemap.xml', 'HelperController@createSitemap');
