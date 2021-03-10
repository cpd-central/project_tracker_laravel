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

Route::get('/newproject', 'ProjectController@new_project')->name('pages.newproject')->middleware('verified');
Route::post('/newproject', 'ProjectController@create')->middleware('verified');

Route::get('/copyproject/{id}', 'ProjectController@copy_project')->name('pages.copyproject')->middleware('verified');
Route::post('/copyproject/{id}', 'ProjectController@create')->middleware('verified');

Route::get('/projectindex', 'ProjectController@index')->name('pages.projectindex')->middleware('verified', 'role', 'pagevisits');
Route::post('/projectindex', 'ProjectController@search');

Route::get('/wonprojectsummary', 'ProjectController@indexwon')->name('pages.wonprojectsummary')->middleware('verified', 'role', 'pagevisits');
Route::post('/wonprojectsummary', 'ProjectController@search')->middleware('verified');

//Select Menu Route
Route::post('/wonprojectsummary', 'ProjectController@indexwon')->name('pages.wonprojectsummarySearch');


Route::get('/hoursgraph', 'ProjectController@hours_graph')->name('pages.hoursgraph')->middleware('verified', 'pagevisits');
Route::post('/hoursgraph','ProjectController@submit_billing')->middleware('verified');

Route::get('/hourstable', 'ProjectController@hours_table')->name('pages.hourstable')->middleware('verified', 'pagevisits');
Route::post('/hourstable', 'ProjectController@code_search')->middleware('verified');

Route::get('/monthendbilling', 'ProjectController@billing')->name('pages.monthendbilling')->middleware('verified', 'pagevisits');

Route::get('/billinghistory', 'ProjectController@bill_history')->name('pages.billinghistory')->middleware('verified', 'pagevisits');
Route::post('/billinghistorysearch', 'ProjectController@bill_history_search')->middleware('verified');

Route::get('/editproject/{id}', 'ProjectController@edit_project')->name('pages.editproject')->middleware('verified');
Route::post('/editproject/{id}', 'ProjectController@update')->middleware('verified');

Route::get('/devindex', 'HomeController@dev_index')->name('pages.devindex')->middleware('verified', 'pagevisits');

Route::get('/devrequest', 'HomeController@dev_request')->name('pages.devrequest')->middleware('verified', 'pagevisits');
Route::get('/devrequest/{id}', 'HomeController@dev_view')->name('pages.devview')->middleware('verified');
Route::post('/devrequest', 'HomeController@dev_create')->middleware('verified');
Route::post('/devrequest/{id}', 'HomeController@dev_close')->middleware('verified');

Route::delete('/devdelete/{id}', 'HomeController@dev_delete')->middleware('verified');

Route::get('/drafterhours', 'ProjectController@drafter_hours')->name('pages.drafterhours')->middleware('verified', 'role', 'pagevisits');


Route::delete('{id}', 'ProjectController@destroy')->middleware('verified');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::get('/timesheet', 'TimesheetController@check')->name('pages.timesheet')->middleware('verified', 'pagevisits');
Route::post('/timesheet', 'TimesheetController@timesheetSave')->name('pages.timesheetSave')->middleware('verified');

Route::get('/accountdirectory', 'HomeController@account_directory')->name('pages.accountdirectory')->middleware('verified', 'role', 'pagevisits');
Route::get('/accountdirectory/{id}', 'HomeController@activation')->name('pages.activation')->middleware('verified');

Route::get('/editaccount/{id}', 'HomeController@edit_account')->name('pages.editaccount')->middleware('verified', 'role');
Route::post('/editaccount/{id}', 'HomeController@update_account')->middleware('verified');

Route::get('/logs', 'HomeController@logs')->name('pages.logs')->middleware('verified', 'role');
Route::post('/logs', 'HomeController@logs')->middleware('verified');

Route::get('/timesheetsentstatus', 'TimesheetController@get_user_timesheet_status')->name('pages.timesheetsentstatus')->middleware('verified', 'pagevisits');

//Project Planner Routes
Route::get('/planner', 'ProjectController@planner')->name('pages.planner')->middleware('verified', 'pagevisits');
Route::post('/planner', 'ProjectController@paste_dates')->middleware('verified');

Route::get('/manageproject/{id}', 'ProjectController@manage_project')->name('pages.manage_project')->middleware('verified');
Route::post('/manageproject/{id}', 'ProjectController@edit_due_dates')->middleware('verified');

Route::get('/stickynote', 'ProjectController@sticky_note')->name('pages.sticky_note')->middleware('verified', 'pagevisits');
Route::post('/stickynote', 'ProjectController@employee_gantt')->middleware('verified');

Route::get('/project_tracker', 'ProjectController@project_tracker')->name('pages.project_tracker')->middleware('verified', 'pagevisits');
Route::post('/project_tracker', 'ProjectController@tracker_save')->middleware('verified');
