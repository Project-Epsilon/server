<?php

use Illuminate\Http\Request;
include '/home/james/projects/Epsilon/server/vendor/paypal/rest-api-sdk-php/sample/payments/CreatePaymentUsingPayPal.php';
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
Route::post('login', 'Auth\LoginController@login');
Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

// Authenticated Routes...=
Route::group(['middleware' => 'auth.jwt'], function () {

    Route::get('logout', 'Auth\LoginController@logout');

    Route::resource('users', 'Users\UsersController',  ['except' => [
        'store', 'create', 'edit'
    ]]);

    Route::get('test', function (){
        return redirect('/home/james/projects/Epsilon/server/vendor/paypal/rest-api-sdk-php/sample/payments/CreatePaymentUsingPayPal.php
');

});

// Create payment
Route::post('addmoney', 'Payments\BankTransferController@addmoney');

Route::post('cashout', 'Payments\BankTransferController@cashout');



});
