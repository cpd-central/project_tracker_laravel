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


Route::get('/newproject', 'ProjectController@new_project')->name('pages.newproject');
Route::post('/newproject', 'ProjectController@create');

Route::get('/projectindex', 'ProjectController@index')->name('pages.projectindex');
Route::post('/projectindex', 'ProjectController@search');

Route::get('/editproject/{id}', 'ProjectController@edit_project')->name('pages.editproject');
Route::post('/editproject/{id}', 'ProjectController@update');

Route::get('/wonprojectsummary', 'ProjectController@summary')->name('pages.wonprojectsummary');

Route::delete('{id}', 'ProjectController@destroy');

Auth::routes();

Route::get('/home', 'ProjectController@index')->name('home');


#save this for later, for now, home will redirect to project index
#Route::get('/home', 'HomeController@index')->name('home');





