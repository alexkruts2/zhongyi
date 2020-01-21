<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    return view('welcome');
    return redirect()->route('login');
});
Route::get('/login', 'Admin\HomeController@loginView')->name('login');
Route::post('/login', 'Admin\UserController@login');
Route::group(['prefix' => 'recipe'], function () {
    Route::group(['middleware' => 'doctor'], function () {
        Route::get('/', 'Admin\HomeController@test')->name('doctor.test');
    });
});
