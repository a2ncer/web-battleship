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

Route::middleware('api')->get('/session', function (Request $request) {
    return $request->session()->getId();
});

Route::group(['prefix' => 'game'], function () {


    Route::get('/{game}', 'GameController@show');

    Route::post('/create', 'GameController@create');

    Route::patch('/{game}/join', 'GameController@join');

    //Route::get('/{game}/updates', 'GameController@show');

    Route::post('/{game}/board/addShip', 'BoardController@addShip');

    Route::post('/{game}/board/attack', 'BoardController@attack');


});



