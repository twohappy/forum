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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads', 'ThreadsController@index');
Route::post('/threads','ThreadsController@store');
Route::get('/threads/create', 'ThreadsController@create');
Route::get('/threads/{channel}','ThreadsController@index');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
//Route::resource('threads','ThreadController');

// route model binding 这里自动给RepliesController的store方法注入了id为thread的，Thread的实例。
Route::post('/threads/{channel}/{thread}/replies','RepliesController@store');


