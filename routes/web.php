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

$router->group(['prefix' => 'v1'], function ($api) {

    $api->group(['middleware' => ['auth:api', 'cors']], function ($api) {
        // User routes
        $api->get('users', ['uses' => 'UserController@index']);
        $api->get('users/{id}', ['uses' => 'UserController@show']);
        $api->post('users', ['uses' => 'UserController@store']);
        $api->put('users/{id}', ['uses' => 'UserController@update']);
        $api->delete('users/{id}', ['uses' => 'UserController@destroy']);

        // Role routes
        $api->get('roles', ['uses' => 'RoleController@index']);
        $api->get('roles/{id}', ['uses' => 'RoleController@show']);
        $api->post('roles', ['uses' => 'RoleController@store']);
        $api->put('roles/{id}', ['uses' => 'RoleController@update']);
        $api->delete('roles/{id}', ['uses' => 'RoleController@destroy']);
        $api->get('permission-list', ['uses' => 'RoleController@permissionList']);

        // News routes
        $api->get('news', ['uses' => 'NewsController@index']);
        $api->get('news/{id}', ['uses' => 'NewsController@show']);
        $api->post('news', ['uses' => 'NewsController@store']);
        $api->put('news/{id}', ['uses' => 'NewsController@update']);
        $api->delete('news/{id}', ['uses' => 'NewsController@destroy']);

        $api->post('news/{id}/comment', ['uses' => 'NewsCommentController@store']);
        $api->post('news/{id}/like', ['uses' => 'NewsLikeController@like']);
        $api->delete('news/{id}/unlike', ['uses' => 'NewsLikeController@unlike']);
    });

});
