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
    Route::patch('/{service}', 'ServiceController@update')->name('update');
    Route::delete('/{service}', 'ServiceController@destroy')->name('destroy');
});
Route::prefix('maintenance')->name('maint.')->group(function() {
    Route::post('/new/{vehicle}', 'MaintController@newWorksheet')->name('create');
    Route::get('/{worksheet}', 'MaintController@showSheet')->name('showsheet');
    Route::get('/{worksheet}/text', 'MaintController@showSheetText')->name('showsheet.text');
    Route::patch('/{worksheet}', 'MaintController@update')->name('update');
    Route::post('/{worksheet}/service', 'MaintController@addService')->name('addservice');
    Route::post('/{worksheet}/service/all', 'MaintController@addDueServices')->name('dueservice');
    Route::delete('/{worksheet}/service/{service}', 'MaintController@removeService')->name('remservice');
    Route::delete('/{worksheet}', 'MaintController@deleteSheet')->name('delsheet');
    Route::put('/{worksheet}', 'MaintController@closeSheet')->name('closesheet');
});
