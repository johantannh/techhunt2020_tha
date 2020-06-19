<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/users/upload', 'EmployeeController@getUploadPage')->name('show_upload');

// Considered an API call
Route::post('/users/upload', 'EmployeeController@uploadEmployees')->name('submit_upload');