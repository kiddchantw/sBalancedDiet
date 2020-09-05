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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Route::middleware(['apiLog'])->group(function () {

Route::post('register', 'Auth\RegisterController@registerAPI');
Route::post('login', 'Auth\LoginController@loginAPI');

Route::middleware(['checkToken'])->group(function () {
    Route::post('info', 'Auth\LoginController@userInfo');
    Route::post('logout', 'Auth\LoginController@logoutAPI');
});

//});

