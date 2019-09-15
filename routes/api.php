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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::group(['middleware' => ['jwt.verify']], function () {

    Route::prefix('users')->group(function(){
        Route::get('', 'UserController@index');
        Route::get('{id}', 'UserController@show');
        Route::delete('{id}', 'UserController@destroy')->middleware('osa.user');
    });

    Route::prefix('events')->group(function() {
        Route::get('', 'EventController@index');
        Route::get('{id}', 'EventController@show');
        Route::post('', 'EventController@store')->middleware('org.user');
    });

    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');
    Route::get('test', 'DataController@test');
});


/**
 * Test routes beyond here, please heed no mind to them ^^
 */

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');
    Route::get('test', 'DataController@test');
});

Route::prefix('test')->group(function() {
    Route::get('', 'TestController@index');
    Route::get('/{id}', 'TestController@show');
});
Route::get('open', 'DataController@open');
