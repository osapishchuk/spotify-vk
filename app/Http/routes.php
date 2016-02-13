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

Route::get('/', 'MainController@home');
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

Route::group(['namespace' => 'Spotify', 'prefix' => 'spotify', 'middleware' => ['svdw_session']], function()
{
    Route::get('/step_one', 'SpotifyAuthController@stepOne');
    Route::get('/step_two', 'SpotifyAuthController@stepTwo');
    Route::get('/step_three', 'SpotifyAuthController@getUserInfo');
    Route::get('/set_playlist/{owner_id}/{playlist_id}', 'SpotifyAuthController@setPlaylist');
    Route::get('/step_four', 'SpotifyAuthController@showPlaylistList');
    Route::get('/logout', 'SpotifyAuthController@logout');
});

Route::group(['namespace' => 'Vk', 'prefix' => 'vk', 'middleware' => ['svdw_session']], function()
{
    Route::get('/step_one', 'VkAuthController@stepOne');
    Route::get('/step_two', 'VkAuthController@stepTwo');
    Route::get('/step_three', 'VkAuthController@stepThree');
    Route::get('/step_four', 'VkAuthController@stepFour');
    Route::group(['prefix' => 'ajax'], function()
    {
        Route::get('/import_song/{aid}/{oid}/{captchaSid?}/{captchaKey?}/', 'VkAuthController@importSong');
        Route::get('/search_song/{songSpotifyArrayId}/{captchaSid?}/{captchaKey?}/', 'VkAuthController@searchSong');
    });
});
