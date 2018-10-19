<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api','middleware' => ['auth:api','role:admin']],function(){
    Route::get('employees','EmployeesController@index');
    Route::post('employees','EmployeesController@store');

    Route::put('employees/{employee}/bonus-percentage','EmployeeBonusPercentageController@update');
    
    Route::get('salaries-to-be-paid','SalariesToBePaidController@index');
});
