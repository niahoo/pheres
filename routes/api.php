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
    Route::get('/{channel}/{feed_format}', 'ChannelController@list');
    Route::get('/{channel}/item/{id}', 'ChannelController@single')
        ->name('singleItem');
    Route::post('/{channel}', 'ChannelController@push');
});

// Channel param is casted in the routeserviceprovider and regex-validated
