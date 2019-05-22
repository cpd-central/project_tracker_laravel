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
    return view('welcome');
});


Route::get('/newproject', 'newprojectcontroller@create')->name('pages.newproject');
Route::post('/newproject', 'newprojectcontroller@store');

Route::get('/projectindex', 'newprojectcontroller@index')->name('pages.projectindex');

Route::get('/editproject/{id}', 'newprojectcontroller@edit')->name('pages.editproject');
Route::post('/editproject/{id}', 'newprojectcontroller@update');

Route::delete('{id}', 'newprojectcontroller@destroy');

Auth::routes();
//Items added for 'new' project
//Route::get('/newproject','newprojectcontroller@create')->name('project.newproject');
//Route::post('/newproject','newprojectcontroller@store')->name('project.save');
//
//Route::get('/projectindex','newprojectcontroller@index')->name('project.projectindex');
//
//Route::get('/editproject/{id}','newprojectcontroller@edit')->name('project.editproject');
//Route::post('/editproject/{id}','newprojectcontroller@update');
//
//Route::delete('{id}','newprojectcontroller@destroy');
//
//
//
//
//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
