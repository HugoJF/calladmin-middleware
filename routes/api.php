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

Route::post('test', function (Request $request) {
	return $request->all();
});

Route::prefix('v1')->group(function () {
	Route::get('/reports/missing-video', 'ReportsController@missingVideo');

	Route::post('/reports/{report}/chat', 'ReportsController@attachChat');
	Route::post('/reports/{report}/player-data', 'ReportsController@attachPlayerData');

	Route::patch('/reports/{report}/attach-video', 'ReportsController@attachVideo');
});

Route::post('reports', 'ReportsController@store')->name('store');

