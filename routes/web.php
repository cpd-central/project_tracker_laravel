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

#Stevewashere
Route::get('/newproject', 'ProjectController@new_project')->name('pages.newproject')->middleware('verified');
Route::post('/newproject', 'ProjectController@create')->middleware('verified');

Route::get('/projectindex', 'ProjectController@index')->name('pages.projectindex')->middleware('verified');
Route::post('/projectindex', 'ProjectController@search')->middleware('verified');

Route::get('/wonprojectsummary', 'ProjectController@indexwon')->name('pages.wonprojectsummary')->middleware('verified');
Route::post('/wonprojectsummary', 'ProjectController@search')->middleware('verified');



Route::get('/editproject/{id}', 'ProjectController@edit_project')->name('pages.editproject')->middleware('verified');
Route::post('/editproject/{id}', 'ProjectController@update')->middleware('verified');



Route::get('/hoursgraph', 'ProjectController@hours_graph')->name('pages.hoursgraph')->middleware('verified');

Route::delete('{id}', 'ProjectController@destroy')->middleware('verified');

Auth::routes(['verify' => true]);

Route::get('/home', 'ProjectController@index')->name('home')->middleware('verified');

#save this for later, for now, home will redirect to project index
#Route::get('/home', 'HomeController@index')->name('home');





