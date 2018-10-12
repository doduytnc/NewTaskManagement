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
})->name('welcome');

Route::group(['prefix' => 'customers'], function () {
    Route::get('/','CustomerController@index')->name('customers.index');
});

Route::get('/tasks' , 'TaskController@index')->name('tasks.index');
Route::get('/tasks/create' , 'TaskController@create')->name('task.create');
Route::post('/tasks' , 'TaskController@store')->name('tasks.store');
Route::get('/search','TaskController@search')->name('search');
Route::get('/delete/{id}' , 'TaskController@destroy')->name('task.delete');
Route::get('/edit/{id}', 'TaskController@edit')->name('task.edit');
Route::post('/update/{id}', 'TaskController@update')->name('task.update');