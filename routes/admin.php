<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" and "auth" middleware group. Now create something great!
|
*/

Route::get('/','Admin\DashboardController@index')->name('dashboard');

//Profile
Route::get('profile/{username}/{action?}','Admin\ProfileController@show')->name('profile');
//Users
Route::any('users/{action?}/{username?}/{anypath?}','Admin\DashboardController@users')->name('users');
//Clients
Route::any('clients/{action?}/{username?}/{anypath?}','Admin\DashboardController@clients')->name('clients');
//Pages
Route::any('pages/{action?}/{username?}/{anypath?}','Admin\DashboardController@pages')->name('pages');
//Posts
Route::any('posts/{type?}/{anypath?}','Admin\DashboardController@posts')->name('posts');
//Upload
Route::any('uploads/{type?}/{anypath?}','Admin\DashboardController@uploads')->name('uploads');
Route::get('upload','Admin\DashboardController@showUpload');
Route::post('upload','Admin\DashboardController@upload')->name('upload');

Route::get('system','Admin\SystemController@index')->name('system');
Route::post('system','Admin\SystemController@store')->name('system');
Route::post('system/resize','Admin\SystemController@resize')->name('system.resize');