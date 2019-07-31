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

Route::get('/newproject', 'ProjectController@new_project')->name('pages.newproject')->middleware('verified', 'role');
Route::post('/newproject', 'ProjectController@create')->middleware('verified');

Route::get('/projectindex', 'ProjectController@index')->name('pages.projectindex')->middleware('verified', 'role');
Route::post('/projectindex', 'ProjectController@search');

Route::get('/wonprojectsummary', 'ProjectController@indexwon')->name('pages.wonprojectsummary')->middleware('verified', 'role');
Route::post('/wonprojectsummary', 'ProjectController@search')->middleware('verified');

//Select Menu Route
Route::post('/wonprojectsummary', 'ProjectController@indexwon')->name('pages.wonprojectsummarySearch');

Route::get('/editproject/{id}', 'ProjectController@edit_project')->name('pages.editproject')->middleware('verified');
Route::post('/editproject/{id}', 'ProjectController@update')->middleware('verified');



Route::get('/hoursgraph', 'ProjectController@hours_graph')->name('pages.hoursgraph')->middleware('verified', 'role');

Route::delete('{id}', 'ProjectController@destroy')->middleware('verified');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

#save this for later, for now, home will redirect to project index
#Route::get('/home', 'HomeController@index')->name('home');



Route::get('/timesheet/{id?}', 'TimesheetController@check')->name('pages.timesheet')->middleware('verified');
Route::post('/timesheet/{id?}', 'TimesheetController@timesheetSave')->name('pages.timesheetSave')->middleware('verified');

Route::get('/roles', 'HomeController@edit_roles')->name('pages.roles')->middleware('verified', 'role');
Route::get('/roles/{id}', 'HomeController@destroy')->name('pages.rolesDelete')->middleware('verified');
Route::post('/roles', 'HomeController@update')->name('pages.rolesUpdate')->middleware('verified');






