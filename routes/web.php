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
})->middleware('guest');;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/clients/{apiClientId}/update', 'HomeController@updateClient');
Route::get('/clients/add', 'HomeController@createClient');
Route::get('/p/{channel}/item/{id}', 'ChannelController@singleItem')
        ->name('singleItem');
Route::get('/p/{channel}', 'ChannelController@index')
        ->name('channelIndex');
Route::get('/p/{channel}/bm.js', 'ChannelController@bookmarkletScript')
        ->name('bookmarkletScript');
Route::get('/p/{channel}/bm.css', 'ChannelController@bookmarkletCssWrapper')
        ->name('bookmarkletScriptWithCssExt');
Route::get('/p/{channel}/push', 'ChannelController@userItemPush')
        ->name('userItemPush');
Route::get('/login-check.txt', 'Auth\LoginController@loginChekTxt')
        ->name('loginChekTxt');
