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

Route::get('/copyproject/{id}', 'ProjectController@copy_project')->name('pages.copyproject')->middleware('verified', 'role');
Route::post('/copyproject/{id}', 'ProjectController@create')->middleware('verified');

Route::get('/projectindex', 'ProjectController@index')->name('pages.projectindex')->middleware('verified', 'role');
Route::post('/projectindex', 'ProjectController@search');

Route::get('/wonprojectsummary', 'ProjectController@indexwon')->name('pages.wonprojectsummary')->middleware('verified', 'role');
Route::post('/wonprojectsummary', 'ProjectController@search')->middleware('verified');

//Select Menu Route
Route::post('/wonprojectsummary', 'ProjectController@indexwon')->name('pages.wonprojectsummarySearch');


Route::get('/hoursgraph', 'ProjectController@hours_graph')->name('pages.hoursgraph')->middleware('verified', 'role');
Route::post('/hoursgraph','ProjectController@submit_billing')->middleware('verified');

Route::get('/monthendbilling', 'ProjectController@billing')->name('pages.monthendbilling')->middleware('verified', 'role');


Route::get('/editproject/{id}', 'ProjectController@edit_project')->name('pages.editproject')->middleware('verified');
Route::post('/editproject/{id}', 'ProjectController@update')->middleware('verified');


Route::get('/drafterhours', 'ProjectController@drafter_hours')->name('pages.drafterhours')->middleware('verified', 'role');


Route::delete('{id}', 'ProjectController@destroy')->middleware('verified');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::get('/timesheet', 'TimesheetController@check')->name('pages.timesheet')->middleware('verified');
Route::post('/timesheet', 'TimesheetController@timesheetSave')->name('pages.timesheetSave')->middleware('verified');

Route::get('/accountdirectory', 'HomeController@account_directory')->name('pages.accountdirectory')->middleware('verified', 'role');
Route::get('/accountdirectory/{id}', 'HomeController@activation')->name('pages.activation')->middleware('verified');

Route::get('/editaccount/{id}', 'HomeController@edit_account')->name('pages.editaccount')->middleware('verified', 'role');
Route::post('/editaccount/{id}', 'HomeController@update_account')->middleware('verified');

Route::get('/timesheetsentstatus', 'TimesheetController@get_user_timesheet_status')->name('pages.timesheetsentstatus')->middleware('verified');

//Project Planner Routes
Route::get('/planner', 'ProjectController@planner')->name('pages.planner')->middleware('verified');
Route::post('/planner', 'ProjectController@paste_dates')->middleware('verified');

Route::get('/manageproject/{id}', 'ProjectController@manage_project')->name('pages.manage_project')->middleware('verified');
Route::post('/manageproject/{id}', 'ProjectController@edit_due_dates')->middleware('verified');

Route::get('/stickynote', 'ProjectController@sticky_note')->name('pages.sticky_note')->middleware('verified');
Route::post('/stickynote', 'ProjectController@employee_gantt')->middleware('verified');

