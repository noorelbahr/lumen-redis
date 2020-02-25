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

        // IMPORTANT!
        // route name (as) will be used to check user permission.
        // permission always granted if there is no route name in the request.
        //
        // available permission list can be updated in config/permissions.php file.

        // User routes
        $api->get('users', ['uses' => 'UserController@index', 'as' => 'users.list']);
        $api->get('users/{id}', ['uses' => 'UserController@show', 'as' => 'users.detail']);
        $api->post('users', ['uses' => 'UserController@store', 'as' => 'users.create']);
        $api->put('users/{id}', ['uses' => 'UserController@update', 'as' => 'users.update']);
        $api->delete('users/{id}', ['uses' => 'UserController@destroy', 'as' => 'users.delete']);

        // Role routes
        $api->get('roles', ['uses' => 'RoleController@index', 'as' => 'roles.list']);
        $api->get('roles/permission-list', ['uses' => 'RoleController@permissionList', 'as' => 'roles.permissions']);
        $api->get('roles/{id}', ['uses' => 'RoleController@show', 'as' => 'roles.detail']);
        $api->post('roles', ['uses' => 'RoleController@store', 'as' => 'roles.create']);
        $api->put('roles/{id}', ['uses' => 'RoleController@update', 'as' => 'roles.update']);
        $api->delete('roles/{id}', ['uses' => 'RoleController@destroy', 'as' => 'roles.delete']);

        // News routes
        $api->get('news', ['uses' => 'NewsController@index', 'as' => 'news.list']);
        $api->get('news/{id}', ['uses' => 'NewsController@show', 'as' => 'news.detail']);
        $api->post('news', ['uses' => 'NewsController@store', 'as' => 'news.create']);
        $api->put('news/{id}', ['uses' => 'NewsController@update', 'as' => 'news.update']);
        $api->delete('news/{id}', ['uses' => 'NewsController@destroy', 'as' => 'news.delete']);

        $api->post('news/{id}/comment', ['uses' => 'NewsCommentController@comment', 'as' => 'news.comment']);
        $api->post('news/{id}/like', ['uses' => 'NewsLikeController@like', 'as' => 'news.like']);
        $api->delete('news/{id}/unlike', ['uses' => 'NewsLikeController@unlike', 'as' => 'news.unlike']);
    });

});
