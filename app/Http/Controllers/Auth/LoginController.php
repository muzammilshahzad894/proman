<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use User; 
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
     use AuthenticatesUsers {
        logout as performLogout;
    }

    

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/dashboard';
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $this->performLogout($request);
        return redirect('login');
    }



    public function login_by_email($email)
    {
      $user = User::where('email', $email)->first();
      if ($user) {
          Auth::login($user, true);
          return redirect('/home');
      }else{
        return "user not found";
      }
    }
}
