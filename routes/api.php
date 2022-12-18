<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
Auth::routes(['verify' => true]);

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');
Route::get('getUserFingerprint/{id}', 'Api\UserController@fingerprintStatus');
Route::get('email', 'Api\AuthController@email');
Route::get('email/verify/{id}', 'Api\AuthController@verify')->name('verification.verify');


Route::group(['middleware' => 'auth:api','verified'], function(){
    
    Route::get('user/{id}', 'Api\UserController@show');
    Route::get('user', 'Api\UserController@index');
    Route::post('user', 'Api\UserController@store');
    Route::put('userFingerprint/{id}', 'Api\UserController@updateFingerprint');
    Route::put('user/{id}', 'Api\UserController@update');
    Route::delete('user/{id}', 'Api\UserController@destroy');

    Route::post('logout', 'Api\AuthController@logout');
    Route::post('auth', 'Api\AuthController@authCheck');

    Route::get('history/{id}', 'Api\HistoryController@index');
    Route::get('historyLast/{id}', 'Api\HistoryController@last');
    Route::get('historyDetail/{id}', 'Api\HistoryController@show');
    Route::post('history', 'Api\HistoryController@store');
    Route::put('history/{id}', 'Api\HistoryController@update');
    Route::delete('history/{id}', 'Api\HistoryController@destroy');

    Route::get('schedule', 'Api\ScheduleController@index');
    Route::get('schedulebyuser', 'Api\ScheduleController@showbyuser');
    Route::get('scheduleDetail/{id}', 'Api\ScheduleController@show');
    Route::post('schedule', 'Api\ScheduleController@store');
    Route::put('schedule/{id}', 'Api\ScheduleController@update');
    Route::delete('schedule/{id}', 'Api\ScheduleController@destroy');

    Route::get('bloodPressure', 'Api\BloodPressureController@index');
    Route::get('bloodPressurebyuser', 'Api\BloodPressureController@showbyuser');
    Route::get('bloodPressurelastdata', 'Api\BloodPressureController@showlastdata');
    Route::get('bloodPressureDetail/{id}', 'Api\BloodPressureController@show');
    Route::post('bloodPressure', 'Api\BloodPressureController@store');
    Route::put('bloodPressure/{id}', 'Api\BloodPressureController@update');
    Route::delete('bloodPressure/{id}', 'Api\BloodPressureController@destroy');

});
