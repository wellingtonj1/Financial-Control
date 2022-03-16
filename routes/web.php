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
})->name('welcome');

Route::get          ('login',       'Auth\LoginController@showLoginForm')->name('login');
Route::post         ('login',       'Auth\LoginController@login'        );

Route::get          ('register',    'UserController@create'             )->name('register');
Route::post         ('register',    'UserController@insert'             );

Route::group(['middleware' => ['auth']], function () {

    Route::get('/home', function () {
        return view('welcome');
    })->name('home');

    Route::post('logout',                  'Auth\LoginController@logout')->name('logout');

    Route::get('bills/payable',            'BillsPayController@index')->name('billstopay');
    Route::post('bills/payable',           'BillsPayController@store');
    Route::get('bills/payable/{id}/edit',  'BillsPayController@edit');
    Route::get('bills/payable/create',     'BillsPayController@create');
    Route::put('bills/payable',            'BillsPayController@update');
    Route::delete('bills/payable',         'BillsPayController@delete');
    
    Route::get('bills/receivable',         'BillsReceiveController@index')->name('billstoreceive');
    Route::get('bills/receivable/create',  'BillsReceiveController@create')->name('billstoreceive.create');


});
