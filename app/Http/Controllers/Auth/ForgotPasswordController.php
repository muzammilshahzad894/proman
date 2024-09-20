<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Session; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Setting;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $setting = Setting::query()->where('key', 'site')->first();
        $config = (object)@$setting->config;

        return view('auth.passwords.email', compact('config'));
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Validate the email for the given request.
     *
     * @param \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $data = $request->all();

        if(isset($data['g-recaptcha-response']))
        {
            $request->merge(['g-recaptcha-response' => $data['g-recaptcha-response']]);

            $this->validate($request, ['email' => 'required|email', 'g-recaptcha-response' => 'required'], ['g-recaptcha-response.required' => 'Captcha Field is required']);
        }
        else
        {
            $this->validate($request, ['email' => 'required|email']);
        }
    }


     protected function sendResetLinkResponse($response)
    {   
        Session::flash('success', "Reset Link sent on email."); 

        return back()->with('status', trans($response));
    }


}
