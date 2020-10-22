<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\bioProfile;
use App\User;
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

//改在kernel全域處理了
//Route::middleware(['apiLog'])->group(function () {
//});

Route::post('register', 'Auth\RegisterController@registerAPI');
Route::post('login', 'Auth\LoginController@loginAPI');

Route::middleware(['checkToken'])->group(function () {
    Route::post('info', 'Auth\LoginController@userInfo');
    Route::post('logout', 'Auth\LoginController@logoutAPI');
    Route::post('uploadImageAPI', 'Auth\LoginController@uploadImageAPI');
    Route::post('reset', 'Auth\LoginController@resetPasswordAPI');
    Route::post('profile', 'Auth\LoginController@editUserProfile');

    Route::post('diet/standard', 'UserDietController@updateStandard');

    //API_016_showWeight
    Route::post('userWeight', 'BioProfileController@showWeight');

});


Route::post('forget', 'Auth\LoginController@forgetPasswordAPI');


//
//    Route::resource('bio', 'bioRecords');
//    Route::apiResource('bio', 'bioRecords');

//Route::post('bio', 'bioRecords@store');
//url:/data/
//Route::get('userBio/{id}', 'bioRecords@showByUser');

//Route::get('bio/{id}', 'bioRecords@show');

//Route::delete('bio/{id}','bioRecords@destroy');
//Route::delete('bio/{id}','bioRecords@destroy');
//->name('bio.destroy');
//url:/data/{bio_id}


//Route::PUT('bio/{id}', 'bioRecords@update');
    //->name('bio.update');
//url:/data/{bio_id}


Route::apiResource('bioProfile', 'BioProfileController');
Route::get('userBio/{id}', 'BioProfileController@showByUser');


Route::apiResource('userWater', 'UserWaterController');
Route::get('water/{id}', 'UserWaterController@waterUser');
Route::post('water/sum', 'UserWaterController@waterDay');


Route::apiResource('userDiet', 'UserDietController');
Route::post('diet', 'UserDietController@showDiet');
Route::post('diet/day', 'UserDietController@showDietByDay');




//測試用
Route::get('testA',function(Request $request)
{
    $uuu = User::find(5)->currentStandard();//->first()->weight;
    return $uuu;
});
