<?php

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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['prefix' => 'oauth'], function ($api) {
        $api->post('token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
    });

    $api->group(['namespace' => 'App\Http\Controllers', 'middleware' => ['auth:api', 'cors']], function ($api) {

    });

});

// API Routes
$router->group(['prefix' => 'api', 'middleware' => ['auth:api', 'cors']], function () use ($router) {
    // Authors
    $router->get('users',  ['uses' => 'UserController@index']);
    $router->get('users/{id}', ['uses' => 'UserController@show']);
    $router->post('users', ['uses' => 'UserController@store']);
    $router->put('users/{id}', ['uses' => 'UserController@update']);
    $router->delete('users/{id}', ['uses' => 'UserController@destroy']);
});
