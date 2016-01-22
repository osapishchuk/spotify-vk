<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'ExampleController@connectSpotify');
    Route::get('/saveSpotifyToken', 'ExampleController@saveSpotifyToken');
    Route::get('/getUserInfo', 'ExampleController@getUserInfo');
    Route::get('/showPlaylistList', 'ExampleController@showPlaylistList');
});

Route::group(['namespace' => 'Vk', 'prefix' => 'vk', 'middleware' => ['vk_session']], function()
{
    Route::get('/step_one', 'VkAuthController@stepOne');
    Route::get('/step_two', 'VkAuthController@stepTwo');
    Route::get('/step_three', 'VkAuthController@stepThree');
    Route::get('/step_four', 'VkAuthController@stepFour');
});
