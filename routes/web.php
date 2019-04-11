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


Route::get('/project', 'ProjectController@form')->name('project.form');
Route::post('/project/save', 'ProjectController@save')->name('project.save');











Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
