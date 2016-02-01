<?php
/**
 * SVDW project
 *
 * @category    SVDW web-service
 * @package     app\Http\Controllers\Spotify
 * @author      Oleg Sapishchuk <osapishchuk@gmail.com>
 * @copyright   2016 Oleg Sapishchuk
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software License 3.0 (OSL-3.0)
 */

namespace app\Http\Controllers\Spotify;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use SpotifyWebAPI;

class SpotifyAuthController extends Controller
{
    private $clientId, $clientSecret, $redirectUrl;

    public function __construct()
    {
        $this->setClientId(Config::get('spotify.client_id'));
        $this->setClientSecret(Config::get('spotify.client_secret'));
    }


    public function stepOne()
    {
        $this->setRedirectUrl('step_two');
        $session = new SpotifyWebAPI\Session($this->getClientId(), $this->getClientSecret(), $this->getRedirectUrl());

        $scopes = array(
            'playlist-read-private',
            'user-read-private',
            'playlist-modify-private',
            'playlist-modify-public',
            'playlist-read-private',

        );

        $authorizeUrl = $session->getAuthorizeUrl(array(
            'scope' => $scopes
        ));

        header('Location: ' . $authorizeUrl);
        die();
    }

    public function stepTwo(Request $request)
    {
        if(!Input::get('code')) redirect('/step_one');
        $this->setRedirectUrl('step_two');
        $session = new SpotifyWebAPI\Session($this->getClientId(), $this->getClientSecret(), $this->getRedirectUrl());
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        try {
            $session->requestAccessToken(Input::get('code'));
            $accessToken = $session->getAccessToken();
            $api->setAccessToken($accessToken);
            $refreshToken = $session->getRefreshToken();
        } catch ( Exception $e) {
            Log::error($e->getMessage());
            return redirect('/spotify/step_one');
        }
        $request->session()->put('spotify.access_token', $accessToken);
        $request->session()->put('spotify.access_token', $refreshToken);
        $data = array(
            'test'  =>  'test',
        );
        return View::make('spotify.step_two')->with('data', $data);
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
                $request->session()->put('spotify.user_id',$playlist->owner->id);
                $request->session()->put('spotify.playlist_id', $playlist->id);
            }
        }

        return View::make('spotify.step_three')->with('data', $data);
        return redirect('/spotify/step_four');
    }

    public function showPlaylistList(Request $request)
    {
        $session = new SpotifyWebAPI\Session(Config::get('spotify.client_id'), Config::get('spotify.client_secret'), Config::get('spotify.redirect_url.showPlaylistList'));
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        try {
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
        } catch ( Exception $e) {
            Log::error($e->getMessage());
            return redirect('/spotify/step_one');
        }
        if(!$request->session()->get('spotify.user_id') && $request->session()->get('spotify.playlist_id')) return redirect('/spotify/step_one');
        $playlistTracks = $api->getUserPlaylistTracks($request->session()->get('spotify.user_id'), $request->session()->get('spotify.playlist_id'));

        $spotifyPlaylist = array();
        $data = array();
        foreach ($playlistTracks->items as $track) {
            $track = $track->track;
            $spotifyPlaylist[$track->id] = $track->name . ' ' . $track->artists[0]->name;
            $data[] = array(
                'id'    => $track->id,
                'name'    => $track->name,
                'artists'    => $track->artists[0]->name,
            );
        }
        $request->session()->put('spotify.songs', $spotifyPlaylist);

        return View::make('spotify.step_four')->with('data', $data);
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId=null)
    {
        if (!$clientId) {
            $clientId = Config::get('spotify.client_id');
        }
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param mixed $clientSecret
     */
    public function setClientSecret($clientSecret=null)
    {
        if (!$clientSecret) {
            $clientSecret = Config::get('spotify.client_secret');
        }
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param mixed $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $redirectUrl = Config::get('spotify.redirect_url.'.$redirectUrl);
        $this->redirectUrl = $redirectUrl;
    }

    public function logout()
    {
        $this->clearSession();
        if (Session::has('vk')) {
            Session::forget('vk');
        }
        return redirect('/');
    }

    private function clearSession()
    {
        if (Session::has('spotify')) {
            Session::forget('spotify');
        }
    }
}