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
    return view('home');
});
Route::get('/users/upload', 'EmployeeController@getUploadPage')->name('show_upload');
Route::get('/users/dashboard', 'EmployeeController@getEmployeeDashboard')->name('get_dashboard');
Route::get('/users/getdashboarddata', 'EmployeeController@getDashboardData')->name('get_dashboard_data');

// Considered API calls
Route::get('/users', 'EmployeeController@getEmployeesData')->name('get_emps_data');
Route::post('/users/upload', 'EmployeeController@uploadEmployees')->name('submit_upload');

Route::get('/users/{id}', 'EmployeeController@getEmployee')->name('get_employee');
Route::post('/users/{id}', 'EmployeeController@createEmployee')->name('create_employee');
Route::patch('/users/{id}', 'EmployeeController@updateEmployee')->name('update_employee');
Route::delete('/users/{id}', 'EmployeeController@deleteEmployee')->name('delete_employee');