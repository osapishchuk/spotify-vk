<?php
/**
 * SVDW project
 *
 * @category    SVDW web-service
 * @package     app\Http\Controllers\Vk
 * @author      Oleg Sapishchuk <osapishchuk@gmail.com>
 * @copyright   2016 Oleg Sapishchuk
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software License 3.0 (OSL-3.0)
 */

namespace app\Http\Controllers\Vk;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use VK\VK;
use VK\VKException;

/**
 * Class VkAuthController
 * @package app\Http\Controllers\Vk
 */
class VkAuthController extends Controller
{
    /**
     * @var string $appId {YOUR_APP_ID}
     * @var string $apiSecret {YOUR_API_SECRET}
     * @var string $callbackUrl 'http://{YOUR_DOMAIN}/samples/example-2.php'
     * @var string $apiSetting {ACCESS_RIGHTS_THROUGH_COMMA}
     * @var mixed $accessToken {ACCESS_TOKEN_ARRAY_FROM_CALLBACK}
     * @var VK $vk {ACCESS_TOKEN_FROM_CALLBACK}
     */
    private $appId, $apiSecret, $callbackUrl, $apiSetting, $accessToken, $vk, $authorize_url, $callbackUrlArray;

    /**
     * @var
     */
    private $spotifySession;

    /**
     *
     */
    public function __construct()
    {
        $this->spotifySessionValidate();
        $this->setAppId(Config::get('vk.app_id'));
        $this->setApiSecret(Config::get('vk.api_secret'));
        $this->setApiSetting(Config::get('vk.api_setting'));
        $this->setCallbackUrlArray(Config::get('vk.callback_url'));
        $this->setVk();
        $this->setSpotifySession();
    }

    /**
     * @param mixed $appId
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }

    /**
     * @param mixed $apiSecret
     */
    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;
    }

    /**
     * @return mixed
     */
    public function getSpotifySession()
    {
        return $this->spotifySession;
    }

    /**
     * @param mixed $spotifySession
     */
    public function setSpotifySession($spotifySession = null)
    {
        if (Session::has('spotify')) {
            $spotifySession = Session::get('spotify');
        }

        $this->spotifySession = $spotifySession;
    }

    /**
     * @param mixed $callbackUrl
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * @return mixed
     */
    public function getApiSetting()
    {
        return $this->apiSetting;
    }

    /**
     * @param mixed $apiSetting
     */
    public function setApiSetting($apiSetting)
    {
        $this->apiSetting = $apiSetting;
    }

    /**
     * @param $callbackUrl
     * @return mixed
     */
    public function setAccessToken($callbackUrl)
    {
        if (Request::input('code')) {
            if (Session::has('vk.access_token')) {
                $this->accessToken = $this->getAccessToken();
            } else {
                $this->accessToken = $this->vk->getAccessToken(Request::input('code'), $callbackUrl);
                if ($this->getAccessToken()) {
                    Session::put('vk.access_token', $this->accessToken);
                }
            }
        } elseif ($this->getAccessToken()) {
            $this->accessToken = $this->getAccessToken();
        }
    }

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @return mixed
     */
    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        if (Session::has('vk.access_token')) {
            return Session::get('vk.access_token');
        }

        return $this->accessToken;
    }

    /**
     * @internal param mixed $vk
     */
    public function setVk()
    {
        try {
            if ($this->getAccessToken() === null) {
                $this->vk = new VK($this->getAppId(), $this->getApiSecret());
            } else {
                $this->vk = new VK($this->getAppId(), $this->getApiSecret(), $this->getAccessToken()['access_token']);
            }
        } catch (VKException $error) {
            echo $error->getMessage();
        }
    }

    /**
     * @return mixed
     */
    public function getAuthorizeUrl()
    {
        return $this->authorize_url;
    }

    /**
     * @param $callbackUrl
     * @internal param mixed $authorize_url
     */
    public function setAuthorizeUrl($callbackUrl)
    {
        $this->authorize_url = $this->vk->getAuthorizeURL($this->getApiSetting(), $callbackUrl);
    }

    /**
     * @return mixed
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    /**
     * @param $callbackUrlArray
     */
    public function setCallbackUrlArray($callbackUrlArray)
    {
        $this->callbackUrlArray = $callbackUrlArray;
    }

    /**
     * @return mixed
     */
    public function getCallbackUrlArray()
    {
        return $this->callbackUrlArray;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stepOne()
    {
        $this->clearVkSession();
        $this->setCallbackUrl($this->getCallbackUrlArray()['step_two']);
        $this->setAuthorizeUrl($this->getCallbackUrl());
        $access_token = $this->getAccessToken();

        return View::make('vk.step_one')->with('data', ['authorize_url' => $this->getAuthorizeUrl(), 'access_token' => $access_token]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function stepTwo()
    {
        if ($this->getAccessToken() === null) {
            try {
                $this->setCallbackUrl($this->getCallbackUrlArray()['step_two']);
                $this->setAccessToken($this->getCallbackUrl());
            } catch (VKException $error) {
                Log::error($error->getMessage());
                return redirect('/vk/step_one');
            }
            if ($this->getAccessToken() === null) {
                Log::warning('access_token is missing');
                return redirect('/vk/step_one');
            }
        }

        return View::make('vk.step_two')->with('data', ['access_token' => $this->getAccessToken()]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function stepThree()
    {
        if(!$this->checkStepAuth('step_three')) return redirect('vk/step_one');
        $songsNameToSearchArray = $this->getSpotifySession()['songs'];
        if(!is_array($songsNameToSearchArray) || !count($songsNameToSearchArray) ) return redirect('vk/step_one');
        $songsToImport = Session::get('vk.songsToImport')?:'no songs';

        $data = array(
            'songsNameToSearchArray'   =>  $songsNameToSearchArray,
            'songsToImport' => $songsToImport,
            'access_token'  =>  $this->getAccessToken()
        );

        return View::make('vk.step_three')->with('data', $data);
    }

    /**
     * @param $songSpotifyArrayId
     * @param null $captchaSid
     * @param null $captchaKey
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function searchSong($songSpotifyArrayId, $captchaSid = null, $captchaKey = null)
    {
        if (Request::ajax()) {
            if (!$this->checkStepAuth('step_three')) return Response::json(array(
                    'response' => array(
                        'status' => 'error',
                        'message' => 'Token Auth failed'
                    )
                )
            );
            $songsNameToSearchArray = $this->getSpotifySession()['songs'];
            if(!is_array($songsNameToSearchArray) || !count($songsNameToSearchArray) ) return Response::json(array(
                    'response' => array(
                        'status' => 'error',
                        'message' => 'Spotify Session missing'
                    )
                )
            );
            $songName = $songsNameToSearchArray[$songSpotifyArrayId];
            if (strlen($songName) === 0) return Response::json(array(
                    'response' => array(
                        'status' => 'error',
                        'message' => 'Song name missing'
                    )
                )
            );

            $config = array(
                'v' => '2.0',
                'q' => $songName,
            );

            if ($captchaKey && $captchaSid) {
                $config['captcha_sid'] = $captchaSid;
                $config['captcha_key'] = $captchaKey;
            }

            $res = $this->vk->api('audio.search', $config);

            if (array_key_exists('error', $res)) {
                if((int)$res['error']['error_code'] === 14) {
                    return Response::json(array(
                            'response' => array(
                                'status' => 'error',
                                'message' => $res['error']['error_msg'],
                                'captcha_sid' => $res['error']['captcha_sid'],
                                'captcha_img' => $res['error']['captcha_img'],
                                'method'    =>  'audio-search',
                                'q' =>  $songName
                            )
                        )
                    );
                } else {
                    return Response::json(array(
                            'response' => array(
                                'status' => 'error',
                                'message' => $res['error']['error_msg']
                            )
                        )
                    );
                }
            }

            if((int)$res['response'][0] !== 0) {
                $songToImport = array(
                    'aid' => $res['response'][1]['aid'],
                    'oid' => $res['response'][1]['owner_id'],
                    'artist'    =>  $res['response'][1]['artist'],
                    'title' =>   $res['response'][1]['title'],
                    'status' => 0
                );
                Session::put('vk.songsToImport.'.$res['response'][1]['aid'], $songToImport);

                return Response::json(array(
                        'response' => array(
                            'status' => 'success',
                            'message' => 'Song was found',
                            'song_info' => $songToImport,
                        )
                    )
                );
            } else {
                return Response::json(array(
                        'response' => array(
                            'status' => 'failed',
                            'message' => 'Song was not found'
                        )
                    )
                );
            }
        }

        return redirect('vk/step_one');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function stepFour()
    {
       if(!$this->checkStepAuth('step_four')) return redirect('vk/step_one');
        $songsToImport = Session::get('vk.songsToImport');
        if(!is_array($songsToImport) || !count($songsToImport) ) return redirect('vk/step_one');

        $data = array(
            'songsToImport' => $songsToImport,
            'access_token'  =>  $this->getAccessToken()
        );

        return view('vk.step_four', $data);
    }

    /**
     * @param $callbackKeyName
     * @return bool
     */
    private function checkStepAuth($callbackKeyName)
    {
        if ($this->getAccessToken() === null) {
            try {
                $this->setCallbackUrl($this->getCallbackUrlArray()[$callbackKeyName]);
                $this->setAccessToken($this->getCallbackUrl());
            } catch (VKException $error) {
                return false;
            }
            if ($this->getAccessToken() === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * Unset vk session data
     */
    private function clearVkSession()
    {
        if (Session::has('vk')) {
            Session::forget('vk');
        }
    }

    /**
     * @param $aid
     * @param $oid
     * @param null $captchaSid
     * @param null $captchaKey
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function importSong($aid, $oid, $captchaSid = null, $captchaKey = null)
    {
        if (Request::ajax())
        {
            if (!$this->checkStepAuth('step_four')) return Response::json(array(
                    'response' => array(
                        'status' => 'error',
                        'message' => 'Token Auth failed'
                    )
                )
            );

            if ((int)$aid === 0 || (int)$oid === 0) return Response::json(array(
                    'response' => array(
                        'status' => 'error',
                        'message' => 'Audio or Author id is wrong'
                    )
                )
            );

            $songsToImport = Session::get('vk.songsToImport');
            if(!is_array($songsToImport) || !count($songsToImport) )return Response::json(array(
                    'response' => array(
                        'status' => 'error',
                        'message' => 'Song session was not set. Please follow all steps again'
                    )
                )
            );

            if ((int)Session::get('vk.songsToImport.' . $aid . 'status') === 1) return Response::json(array(
                    'response' => array(
                        'status' => 'success',
                        'message' => 'Song already imported'
                    )
                )
            );

            $config = array(
                'v' => '2.0',
                'aid' => $aid,
                'oid' => $oid,
            );

            if ($captchaKey && $captchaSid) {
                $config['captcha_sid'] = $captchaSid;
                $config['captcha_key'] = $captchaKey;
            }
            $res = $this->vk->api('audio.add', $config);

            if(array_key_exists('response',$res)) {

                Session::put('vk.songsToImport.'.$aid.'.status', 1);

                return Response::json(array(
                        'response' => array(
                            'status' => 'success',
                            'message' => 'Song imported'
                        )
                    )
                );
            }
            if (array_key_exists('error', $res)) {
                if($res['error']['error_code'] == 14) {
                    return Response::json(array(
                            'response' => array(
                                'status' => 'error',
                                'message' => $res['error']['error_msg'],
                                'captcha_sid' => $res['error']['captcha_sid'],
                                'captcha_img' => $res['error']['captcha_img'],
                                'method'    =>  'audio-add',
                                'aid' => $aid,
                                'oid'   => $oid,
                            )
                        )
                    );
                } else {
                    return Response::json(array(
                            'response' => array(
                                'status' => 'error',
                                'message' => $res['error']['error_msg']
                            )
                        )
                    );
                }
            }

            return Response::json(array(
                    'response' => array(
                        'status' => 'error',
                        'message' => 'vk response code unsupported'
                    )
                )
            );
        }

        return redirect('/');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function spotifySessionValidate()
    {
        if (!Session::has('spotify')) return redirect('/spotify/step_one');
    }
}