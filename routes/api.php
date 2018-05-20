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

Route::middleware('auth:api')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // /p/ : private channels
    Route::get('/p/{channel}/{feed_format?}', 'ChannelController@list');
    Route::post('/p/{channel}', 'ChannelController@push');
});

// Channel param is casted in the routeserviceprovider and regex-validated
