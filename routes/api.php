<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication Route...
Route::post('login', 'Auth\API\LoginController@login');
Route::get('auth/{provider}', 'Auth\API\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\API\LoginController@handleProviderCallback');

// Authenticated Routes...=
Route::group(['middleware' => 'auth.jwt'], function () {

    Route::get('logout', 'Auth\API\LoginController@logout');

    Route::group(['namespace' => 'API'], function (){

        Route::resource('users', 'Users\UsersController',  ['except' => [
            'store', 'create', 'edit'
        ]]);

    });

});
