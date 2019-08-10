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
})->name('auth');

Route::get('/','Admin\DashboardController@api')->name("default");  
Route::resource('users/albums','API\UsersAlbumsController')->except(['create','edit']);
Route::resource('users','API\UsersController')->except(['create','edit']);
//User Albums
Route::get('users/{id}/album/{type}','API\UsersController@albums'); 
Route::post('users/{id}/upload/{type}','API\UsersController@upload');  
Route::resource('clients','API\ClientsController')->except(['create']); 
//Upload Files
Route::prefix('upload')->group(function(){
    //Template
    Route::post('template','API\Upload\TemplateController@upload');
    Route::post('template/merge','API\Upload\TemplateController@merge');
    Route::post('template/resize','API\Upload\TemplateController@resize');
    //Images
    Route::post('images','API\Upload\ImagesController@upload');
    Route::post('images/merge','API\Upload\ImagesController@merge');
    Route::post('images/resize','API\Upload\ImagesController@resize');
    //Avatars
    Route::post('avatar','API\Upload\AvatarsController@upload');
    Route::post('avatar/merge','API\Upload\AvatarsController@merge');
    Route::post('avatar/resize','API\Upload\AvatarsController@resize');
    Route::post('avatar/save','API\Upload\AvatarsController@save');
    //Covers
    Route::post('cover','API\Upload\CoversController@upload');
    Route::post('cover/merge','API\Upload\CoversController@merge');
    Route::post('cover/resize','API\Upload\CoversController@resize');
    Route::post('cover/save','API\Upload\CoversController@save');
});
//Template
Route::resource('template','API\TemplateController')->except(['create','edit']);