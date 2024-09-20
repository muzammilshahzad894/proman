<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use App\Http\Controllers\Admin\ReservationsController;
use App\Models\Gifts\GiftCert;
use App\Models\Gifts\GiftOrder;
use App\Models\Gifts\GiftOrderEmail;
use App\Models\State;
use Illuminate\Http\Request;
use App\StaticPage;
use Mail;
use Session;
use DB,View;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
 
}
