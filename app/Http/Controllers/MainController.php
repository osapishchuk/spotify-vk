<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use SpotifyWebAPI;

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

    public function home()
    {
        return View::make('home');
    }

    public function finish()
    {
        Session::flush();

        return view('finish');
    }
}
