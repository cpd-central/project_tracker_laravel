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


Route::get('/newproject', 'newprojectcontroller@new_project')->name('pages.newproject');
Route::post('/newproject', 'newprojectcontroller@create');

Route::get('/projectindex', 'newprojectcontroller@index')->name('pages.projectindex');
Route::post('/projectindex', 'newprojectcontroller@search');

Route::get('/editproject/{id}', 'newprojectcontroller@edit_project')->name('pages.editproject');
Route::post('/editproject/{id}', 'newprojectcontroller@update');

Route::get('/wonprojectsummary', 'newprojectcontroller@summary')->name('pages.wonprojectsummary');

Route::delete('{id}', 'newprojectcontroller@destroy');

Auth::routes();

Route::get('/home', 'newprojectcontroller@index')->name('home');


#save this for later, for now, home will redirect to project index
#Route::get('/home', 'HomeController@index')->name('home');





