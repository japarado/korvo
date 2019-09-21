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

Route::get('{student_id}/createReport', 'FileController@index');

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::group(['middleware' => ['jwt.verify']], function () {

    Route::resource('speakers', 'SpeakerController')->middleware('org.user');
    Route::post('speakers/{speaker_id}/events/{event_id}', "SpeakerController@assignToEvent");
    Route::delete('speakers/{speaker_id}/events/{event_id}', "SpeakerController@removeFromEvent");

    Route::prefix('users')->group(function(){
        Route::get('', 'UserController@index');
        Route::get('{id}', 'UserController@show');
        Route::delete('{id}', 'UserController@destroy')->middleware('osa.user');
    });

    Route::prefix('students')->group(function() {
        Route::get('', 'StudentController@index');
        Route::get('{id}', 'StudentController@show');
        Route::put('{id}', 'StudentController@update');
        Route::post('', 'StudentController@store')->middleware('org.user');
        Route::post('{student_id}/events/{event_id}', 'StudentController@assignToEvent')->middleware('org.user');
        Route::delete('{student_id}/events/{event_id}', 'StudentController@removeFromEvent')->middleware('org.user');
    });

    Route::prefix('events')->group(function() {
        Route::get('', 'EventController@index');
        Route::get('archived', 'EventController@archived')->middleware('osa.user');
        Route::get('{id}', 'EventController@show');
        Route::put('{id}', 'EventController@update')->middleware('org.user', 'event.owner');
        Route::post('', 'EventController@store')->middleware('org.user');
        Route::post('{event_id}/students/{student_id}', 'EventController@addStudent')->middleware('org.user');
        Route::post('{event_id}/speakers/{speaker_id}', 'EventController@addSpeaker')->middleware('org.user');
        Route::delete('{event_id}/students/{student_id}', 'EventController@removeStudent')->middleware('org.user');
        Route::delete('{event_id}/speakers/{speaker_id}', 'EventController@removeSpeaker')->middleware('org.user');
        Route::delete('{id}', 'EventController@destroy')->middleware('osa.user');

        Route::group(['middleware' => ['event.inspectors']], function() {
            Route::put('approve/{id}', 'EventController@approve');
            Route::put('reject/{id}', 'EventController@reject');
        });
    });

    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');
    Route::get('test', 'DataController@test');
});


/**
 * Test routes beyond here, please heed them no mind ^^
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
