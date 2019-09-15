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
Route::resource('users', 'UserController');

Route::group(['middleware' => ['jwt.verify']], function () {
    /* Route::resource('events', 'EventController'); */
    Route::prefix('events')->group(function() {
        Route::get('', 'EventController@index');
        Route::post('', 'EventController@store')->middleware('org.user');
    });
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');
    Route::get('test', 'DataController@test');
});

Route::prefix('test')->group(function() {
    Route::get('', 'TestController@index');
    Route::get('/{id}', 'TestController@show');
});
Route::get('open', 'DataController@open');
