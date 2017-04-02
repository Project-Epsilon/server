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
Route::post('login', 'Auth\LoginController@login');
Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

//Service Callbacks...
Route::get('transfer/bank/deposit', 'Transfer\Bank\DepositController@paypalCallback');

// Authenticated Routes...
Route::group(['middleware' => 'jwt.auth'], function () {

    Route::get('logout', 'Auth\LoginController@logout');

    Route::post('auth/otp', 'Auth\OTPController@request');
    Route::post('auth/otp/unlock', 'Auth\OTPController@unlock');

    Route::get('user', 'User\UserController@index');

    Route::group(['middleware' => 'otp'], function(){

        Route::group(['prefix' => 'user', 'namespace' => 'User'], function(){

            Route::post('', 'UserController@update');
            Route::delete('', 'UserController@destroy');

            Route::resource('contact', 'ContactController', ['except' => ['edit', 'show', 'create']]);

        });

        Route::group(['prefix' => 'transfer', 'namespace' => 'Transfer'], function (){

            Route::post('bank/deposit', 'Bank\DepositController@deposit');
            Route::post('bank/withdraw', 'Bank\WithdrawalController@withdraw');

            Route::get('bank/transfer', 'Bank\TransferController@index');
            Route::get('bank/transfer/{id}', 'Bank\TransferController@show');

            Route::post('user/cancel', 'User\ReceiveController@cancel');
            Route::post('user/receive', 'User\ReceiveController@receive');
            Route::post('user/send', 'User\SendController@send');
            Route::post('user', 'User\TransferController@getByToken');

            Route::get('user/transfer', 'User\TransferController@index');
            Route::get('user/transfer/{id}', 'User\TransferController@show');

        });

        Route::group(['prefix' => 'wallet', 'namespace' => 'Wallet'], function(){

            Route::get('transaction/{id}', 'TransactionController@show');

            Route::get('', 'WalletController@index');
            Route::get('{id}', 'WalletController@show');
            Route::post('{id}', 'WalletController@update');

        });

    });

});

Route::get('app/currencies', 'AppController@currencies');
Route::get('app/callback', 'AppController@callback');