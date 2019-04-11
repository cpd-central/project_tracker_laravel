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


Route::get('/newproject', 'ProjectController@new_project')->name('project.newproject');
Route::post('/newproject/save', 'ProjectController@save')->name('project.save');











Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
