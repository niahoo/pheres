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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/clients/{apiClientId}/update', 'HomeController@updateClient');
Route::get('/clients/add', 'HomeController@createClient');
Route::get('/p/{channel}/item/{id}', 'ChannelController@single')
        ->name('singleItem');
