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

Auth::routes(['register' => false]);

Route::get('/', function () {
    return redirect('login');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/home', 'HomeController@index');
    Route::post('/{hash}', 'HourBankController@index');
    Route::get('/start/{id}', 'HourBankController@startTask');
    Route::get('/end/{id}', 'HourBankController@endSchedule');
    Route::get('/delete-schedule/{id}', 'HourBankController@deleteSchedule');
});

Route::get('/{hash}', 'HourBankController@index');
