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

$router->post('/users', 'UserController@createNewUser');
$router->post('/auth/login', 'AuthController@authenticate');

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'categories'], function () use ($router) {
    $router->post('/', 'categoriesController@createNewCategory');
    $router->put('/{categoryId}', 'categoriesController@updateCategory');
});
