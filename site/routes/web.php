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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });
$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('create', 'UserController@createUser');
        $router->post('login', 'UserController@login');
        $router->get('{id}', 'UserController@getUser');
        $router->delete('{id}', 'UserController@destroyUser');
        $router->put('{id}', 'UserController@updateUser');
    });
});
