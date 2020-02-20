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

// API Routes
$router->group(['prefix' => 'api'], function () use ($router) {
    // Authors
    $router->get('authors',  ['uses' => 'AuthorController@showAuthors']);
    $router->get('authors/{id}', ['uses' => 'AuthorController@findAuthor']);
    $router->post('authors', ['uses' => 'AuthorController@createAuthor']);
    $router->put('authors/{id}', ['uses' => 'AuthorController@updateAuthor']);
    $router->delete('authors/{id}', ['uses' => 'AuthorController@deleteAuthor']);
});
