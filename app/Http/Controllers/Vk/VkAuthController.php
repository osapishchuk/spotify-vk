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
use Illuminate\Support\Facades\Session;
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
        $this->setTestData();
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
        return view('vk.step_one', ['authorize_url' => $this->getAuthorizeUrl(), 'access_token' => $access_token]);
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

        return view('vk.step_two', ['access_token' => $this->getAccessToken()]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function stepThree()
    {
        if ($this->getAccessToken() === null) {
            try {
                $this->setCallbackUrl($this->getCallbackUrlArray()['step_three']);
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
        $songsNameToSearchArray = $this->getSpotifySession()['songs'];
        if(!is_array($songsNameToSearchArray) || !count($songsNameToSearchArray) ) return redirect('vk/step_one');
        $songsToImport = array();
        foreach ($songsNameToSearchArray as $songName)
        {
            $res = $this->vk->api('audio.search', [
                'v' => '2.0',
                'q' => $songName
            ]);

            if((int)$res['response'][0] !== 0) {
                $songsToImport[] = array(
                    'aid'   =>  $res['response'][1]['aid'],
                    'oid'   =>  $res['response'][1]['owner_id'],
                );
            }

            sleep(1);
        }
        Session::put('vk.songsToImport', $songsToImport);
        $songsToImportAmount = count($songsNameToSearchArray) - count($songsToImport);

        $data = array(
            'songsNameToSearchArray'   =>  $songsNameToSearchArray,
            'songsToImport' => $songsToImport,
            'songsToImportAmount'   =>  $songsToImportAmount,
            'access_token'  =>  $this->getAccessToken()
        );

        return view('vk.step_three', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function stepFour()
    {
        if ($this->getAccessToken() === null) {
            try {
                $this->setCallbackUrl($this->getCallbackUrlArray()['step_four']);
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
        $songsToImport = Session::get('vk.songsToImport');
        if(!is_array($songsToImport) || !count($songsToImport) ) return redirect('vk/step_one');
        $songsImported = 0;
        $resErrors = array();
        foreach ($songsToImport as $songToImport)
        {
            $res = $this->vk->api('audio.add', [
                'v' => '2.0',
                'aid' => $songToImport['aid'],
                'oid'   => $songToImport['oid'],
            ]);

            if(array_key_exists('response',$res)) {
                $resErrors[] = array(
                    'aid' => $songToImport['aid'],
                    'oid'   => $songToImport['oid'],
                    'response'    =>  $res['response']
                );
                $songsImported++;
            } else {
                $resErrors[] = array(
                    'aid' => $songToImport['aid'],
                    'oid'   => $songToImport['oid'],
                    'error'    =>  $res['error']
                );
            }

//            sleep(1);
        }
        Session::put('vk.songsImported ', $songsImported);

        $data = array(
            'songsImported' => $songsImported,
            'resErrors'   =>  $resErrors,
            'access_token'  =>  $this->getAccessToken()
        );

        return view('vk.step_four', $data);
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

    private function setTestData()
    {
        $array = array(
            0 => 'Smack! Francis Davila',
            1 => '21 Hunter Hayes',
            2 => 'Can You Find Me - Radio Edit Andrew Bennett',
            3 => 'Where I Wanna Be A R I Z O N A',
            4 => 'Fire with Fire (Rykkinnfella Remix) ST. NIKLAS',
            5 => 'Perfect - Matoma Remix One Direction',
            6 => 'Screaming Colors Violet Days',
            7 => 'Beautiful Now / Verge (Mashup) Tanner Patrick',
            8 => 'SummerThing! Afrojack',
            9 => 'Flashlight Petronix',
            10 => 'What a Day B4CH',
            11 => 'Until We Die Gentle Bones',
            12 => 'We Are Kids North',
            13 => 'Toothbrush DNCE',
            14 => 'By My Side Leroy Sanchez',
            15 => 'Lock Up The Rainbow Chris Lago',
            16 => 'Afraid of the Dark MKTO',
            17 => 'Renegades X Ambassadors',
            18 => 'Habits (Stay High) Billy Chuchat',
            19 => 'Here for You Kygo',
            20 => 'Send My Love (To Your New Lover) - Acoustic Sofia Karlberg',
            21 => 'Rainbow The Likes of Us',
            22 => 'Vaporize (Seven Am Remix) M. Maggie',
            23 => 'She\'s Crazy but She\'s Mine Alex Sparrow',
            24 => 'I Was Made For Loving You Will Gittens',
            25 => 'ILYSB LANY',
            26 => 'Rivers (feat. Nico & Vinz) Thomas Jack',
            27 => 'How Hard I Try filous',
            28 => 'Hotel Ceiling Rixton',
            29 => 'Where? (From the Opera "The Rabbits") Kate Miller-Heidke'
        );
        Session::put('spotify.songs',$array);
    }
}