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
    return view('home');
});
$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('login', 'UserController@login');
        $router->post('create', 'UserController@createUser');
        $router->delete('{id}/delete', 'UserController@destroyUser');
        $router->group(['middleware' => 'jwt.auth'], function() use ($router) {
            $router->get('profile', 'UserController@getProfile');
            $router->post('update', 'UserController@updateUser');
        });
    });
});
