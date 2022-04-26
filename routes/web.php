<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'MainController@dashboard')->name('dashboard');
Route::resource('vehicles', 'VehiclesController');
Route::prefix('services')->name('services.')->group(function() {
    Route::get('/new/{vehicle}', 'ServiceController@create')->name('create');
    Route::post('/new/{vehicle}', 'ServiceController@store')->name('store');
    Route::get('/{service}', 'ServiceController@edit')->name('edit');
    Route::delete('/{service}', 'ServiceController@destroy')->name('destroy');
});
