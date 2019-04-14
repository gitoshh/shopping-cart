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

Route::post('/users', 'UserController@createNewUser');
Route::post('/auth/login', 'AuthController@authenticate');

Route::group(['middleware' => 'auth'], function () {
    // Categories
    Route::post('/categories', 'categoriesController@createNewCategory');
    Route::get('/categories', 'categoriesController@fetchCategories');
    Route::get('/categories/{categoryId}', 'categoriesController@fetchCategoryById');
    Route::delete('/categories/{categoryId}', 'categoriesController@removeCategory');

    // Items
    Route::get('/items', 'ItemsController@fetchItems');
    Route::post('/items', 'ItemsController@createNewItem');
    Route::delete('/items/{itemId}', 'ItemsController@deleteItem');
});
