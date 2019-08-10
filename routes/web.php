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

Route::get('/','LoginController@showLoginForm')->name('login');
Route::post('login','LoginController@login')->name('login');
Route::post('logout','LoginController@logout')->name('logout');

Route::get('minify','Admin\DashboardController@minify')->name('minify');
Route::get('test',function(){
    //return view('test');
});
