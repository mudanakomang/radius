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

Route::get('/clearcache', function()
{
    \Illuminate\Support\Facades\Artisan::call('dump-autoload');
    echo 'clear cache complete';
});

Auth::routes();

Route::group(['middleware'=>'auth'],function(){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/nas','NASController@index');
    Route::get('/nas/create','NASController@create');
    Route::post('/nas/delete','NASController@deleteNas')->name('nas.delete');
    Route::post('/nas/store','NASController@store')->name('nas.store');
    Route::get('/nas/edit/{id}','NASController@edit');
    Route::post('/nas/{id}','NASController@update')->name('nas.update');

});