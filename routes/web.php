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
	if (\Illuminate\Support\Facades\Auth::check()) {
		return redirect()->route('dashboard');
	}

	return view('home');
});

Route::get('auth', 'AuthController@login')->name('login');
Route::get('logout', 'AuthController@logout')->name('logout');
Route::get('redirect-to-steam', 'AuthController@redirectToSteam')->name('redirect-to-steam');
Route::get('dashboard', 'ReportsController@index')->name('dashboard');

Route::prefix('reports')->name('reports.')->group(function () {
	Route::get('/', 'ReportsController@index')->name('index');
	Route::get('/d', 'ReportsController@store')->name('store1');
	Route::delete('{report}', 'ReportsController@delete')->name('delete');
});

Route::prefix('users')->name('users.')->group(function () {
	Route::get('/', 'UsersController@index')->name('index');
	Route::get('{user}', 'UsersController@show')->name('show');

	Route::patch('{user}/admin', 'UsersController@admin')->name('admin');
	Route::patch('{user}/unadmin', 'UsersController@unadmin')->name('unadmin');

	Route::patch('{user}/ignoreReports', 'UsersController@ignoreReports')->name('ignore-reports');
	Route::patch('{user}/unignoreReports', 'UsersController@unignoreReports')->name('unignore-reports');

	Route::patch('{user}/ignoreTargets', 'UsersController@ignoreTargets')->name('ignore-targets');
	Route::patch('{user}/unignoreTargets', 'UsersController@unignoreTargets')->name('unignore-targets');

});

Route::prefix('votes')->name('votes.')->group(function () {
	Route::get('/', 'VotesController@index')->name('index');
	Route::post('/', 'VotesController@store')->name('store');
});

Route::prefix('settings')->name('settings.')->group(function () {
	Route::get('/edit', 'SettingsController@edit')->name('edit');
	Route::patch('/', 'SettingsController@update')->name('update');
});




/**
 *
 * - Download Demo (check if demo exists)
 * - Vote Logic
 * - Final decision logic
 * - Karma logic
 * - Discord notification
 * - Application Settings
 * - User list
 * - Mod/Unmod logic
 * - Ban/Unban logic
 * - Ignore reports
 * - Ignore targets
 * - Vote list
 * - Search logic
 * - Recording daemon
 *
 *
 */