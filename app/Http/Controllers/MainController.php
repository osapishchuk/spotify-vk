<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use SpotifyWebAPI;

/**
 * Class MainController
 * @package App\Http\Controllers
 */
class MainController extends Controller
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

    /**
     * @return mixed
     */
    public function home()
    {
        return View::make('home');
    }

    /**
     * @return View
     */
    public function finish()
    {
        Session::flush();

        return view('finish');
    }
}
