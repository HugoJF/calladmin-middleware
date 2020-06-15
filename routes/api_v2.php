<?php

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

Route::name('reports.')->prefix('reports')->group(function () {
    Route::post('/', 'ReportsController@store')->name('create');

    Route::post('{report}/demo', 'ReportsController@demo')->name('demo');
});
