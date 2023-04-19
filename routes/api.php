<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'v1',
    ],
    function ($router) {
        Route::post('register', 'App\Http\Controllers\UsersController@store');
        Route::post('login', 'App\Http\Controllers\AuthController@login');

        Route::group(
            [
                'middleware' => 'jwtAuth',
            ],
            function ($router) {
                Route::post('logout', 'App\Http\Controllers\AuthController@logout');
                Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
                Route::post('me', 'App\Http\Controllers\AuthController@me');

                Route::post('transaction/transfer', 'App\Http\Controllers\TransactionController@transfer');
                Route::get('transaction/history', 'App\Http\Controllers\TransactionController@history');

                Route::get('banks', 'App\Http\Controllers\BankController@index');
            }
        );
    }
);
