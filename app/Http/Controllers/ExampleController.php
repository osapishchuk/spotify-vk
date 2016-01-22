<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use SpotifyWebAPI;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function connectSpotify()
    {
        $session = new SpotifyWebAPI\Session(Config::get('spotify.client_id'), Config::get('spotify.client_secret'), Config::get('spotify.redirect_url.saveSpotifyToken'));

        $scopes = array(
            'playlist-read-private',
            'user-read-private'
        );

        $authorizeUrl = $session->getAuthorizeUrl(array(
            'scope' => $scopes
        ));

        header('Location: ' . $authorizeUrl);
        die();
    }

    public function saveSpotifyToken(Request $request)
    {
        $session = new SpotifyWebAPI\Session(Config::get('spotify.client_id'), Config::get('spotify.client_secret'), Config::get('spotify.redirect_url.saveSpotifyToken'));
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $session->requestAccessToken(Input::get('code'));
        $accessToken = $session->getAccessToken();
        $api->setAccessToken($accessToken);
        $refreshToken = $session->getRefreshToken();
        $request->session()->put('access_token', $accessToken);
        $request->session()->put('refresh_token', $refreshToken);

        return redirect()->action('ExampleController@getUserInfo');
    }

    public function getUserInfo(Request $request)
    {
        $session = new SpotifyWebAPI\Session(Config::get('spotify.client_id'), Config::get('spotify.client_secret'), Config::get('spotify.redirect_url.getUserInfo'));
        $api = new SpotifyWebAPI\SpotifyWebAPI();

        if (isset($_GET['code'])) {
            $session->requestAccessToken($_GET['code']);
            $api->setAccessToken($session->getAccessToken());
        } else {
            header('Location: ' . $session->getAuthorizeUrl(array(
                    'scope' => array(
                        'playlist-modify-private',
                        'playlist-modify-public',
                        'playlist-read-private',
                    )
                )));
            die();
        }
        $me = $api->me();
        $playlists = $api->getUserPlaylists($me->id);
        foreach ($playlists->items as $playlist) {
           if($playlist->name === Config::get('spotify.import_playlist_name')) {
               $request->session()->put('user_id',$playlist->owner->id);
               $request->session()->put('playlist_id', $playlist->id);
           }
        }

        return redirect('/showPlaylistList');
    }

    public function showPlaylistList(Request $request)
    {
        $session = new SpotifyWebAPI\Session(Config::get('spotify.client_id'), Config::get('spotify.client_secret'), Config::get('spotify.redirect_url.showPlaylistList'));
        $api = new SpotifyWebAPI\SpotifyWebAPI();

        if (isset($_GET['code'])) {
            $session->requestAccessToken($_GET['code']);
            $api->setAccessToken($session->getAccessToken());
        } else {
            header('Location: ' . $session->getAuthorizeUrl(array(
                    'scope' => array(
                        'playlist-modify-private',
                        'playlist-modify-public',
                        'playlist-read-private',
                    )
                )));
            die();
        }

        $playlistTracks = $api->getUserPlaylistTracks($request->session()->get('user_id'), $request->session()->get('playlist_id'));
        $spotifyPlaylist = array();
        foreach ($playlistTracks->items as $track) {
            $track = $track->track;
            $spotifyPlaylist[] = $track->name . ' ' . $track->artists[0]->name;
        }

        print_r($spotifyPlaylist);
    }
}
