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

Route::get('/', 'ReportsController@index')->name('home');

Route::get('auth', 'AuthController@login')->name('login');
Route::get('logout', 'AuthController@logout')->name('logout');
Route::get('redirect-to-steam', 'AuthController@redirectToSteam')->name('redirect-to-steam');
Route::get('dashboard', 'ReportsController@index')->name('dashboard');
Route::get('search', 'ReportsController@search')->name('search');

Route::prefix('reports')->name('reports.')->group(function () {
	Route::get('/', 'ReportsController@index')->name('index');
	Route::get('{report}', 'ReportsController@show')->name('show');

	Route::post('{report}/vote', 'ReportsController@vote')->name('vote');

	Route::patch('{report}/decision', 'ReportsController@decision')->name('decision')->middleware('can:decide,report');

	Route::delete('{report}', 'ReportsController@delete')->name('delete');
});

Route::prefix('users')->name('users.')->group(function () {
	Route::get('/', 'UsersController@index')->name('index')->middleware('can:index,App\User');
	Route::get('{user}', 'UsersController@show')->name('show')->middleware('can:show,user');

	Route::patch('{user}/admin', 'UsersController@admin')->name('admin')->middleware('can:admin,user');

	Route::patch('{user}/ignoreReports', 'UsersController@ignoreReports')->name('ignore-reports')->middleware('can:ignoreReports,user');

	Route::patch('{user}/ignoreTargets', 'UsersController@ignoreTargets')->name('ignore-targets')->middleware('can:ignoreTargets,user');

	Route::patch('{user}/admin', 'UsersController@admin')->name('admin')->middleware('can:admin,user');

	Route::patch('{user}/ban', 'UsersController@ban')->name('ban')->middleware('can:ban,user');
});

Route::prefix('votes')->name('votes.')->group(function () {
	Route::get('/', 'VotesController@index')->name('index')->middleware('can:index,App\Vote');
});

Route::prefix('settings')->name('settings.')->group(function () {
	Route::get('/edit', 'SettingsController@edit')->name('edit');
	Route::patch('/', 'SettingsController@update')->name('update');
});

/**
 * - Discord notification
 * - Application Settings
 */