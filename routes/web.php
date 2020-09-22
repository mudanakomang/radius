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
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
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

    Route::resource('userprofile','UserProfileController');
    Route::get('userprofile/attribute/add/{group}','UserProfileController@addAttribute');
    Route::get('userprofile/attribute/{group}','UserProfileController@attribute');
    Route::post('userprofile/attribute/store','UserProfileController@storeAttribute')->name('userprofile.attribute.store');
    Route::post('userprofile/attribute/delete','UserProfileController@deleteAttribute')->name('userprofile.attribute.delete');

    Route::resource('user','RadUserController');

});