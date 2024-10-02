<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session, Cache;
use App\Setting;
use App\Http\Requests\SendEmailRequest;
use App\EmailHistory;
use App\SendSurveyTime;
use Twilio\Rest\Client;
use App\Services\PaymentService;

class SettingController extends Controller
{
    protected $viewsFolder = 'settings.';

    public function showGeneral()
    {
        $setting = Setting::where('key', 'general')->first();

        $config = $setting->config;

        Cache::forget('general');

        return view($this->viewsFolder . 'general', compact('setting', 'config'));
    }

    public function updateGeneral(Request $request)
    {
        $input = $request->all();

        $this->validateGenral($request);
        $configs = $request->config;

        if ($request->hasFile('config.favicon')) {
            $path = store_to_uploads($request->file('config.favicon'), 'favicon', 'logo');
            $configs = array_merge($configs, ['favicon' => $path]);
        }

        $setting = Setting::where('key', 'general')->first();
        $setting->updateConfig($configs);
        //return $setting;

        Session::flash('success', "Settings were updated successfully");

        return back();
    }

    public function showProgrammerSettings()
    {
        $programmer_settings = Setting::where('key', 'programmer_settings')->first();
        $config = $programmer_settings->config;
        return view($this->viewsFolder . 'programmer_settings', compact('programmer_settings', 'config'));
    }
   
    public function updateProgrammerSettings(Request $request)
    {
        $configs = $request->config;
        $programmer_settings = Setting::where('key', 'programmer_settings')->first();
        $programmer_settings->updateConfig($configs);
        return back();
    }

    public function showMail()
    {
        $setting = Setting::where('key', 'mail')->first();
        $config = $setting->config;
        //$emails = EmailHisotry::getRecent();
        $emails=[];
        return view($this->viewsFolder . 'mail', compact('setting', 'config', 'emails'));
    }



    public function updateMail(Request $request)
    {
        $input = $request->all();
        $input['config'] = array_filter($input['config']);

        $setting = Setting::where('key', 'mail')->first();
        $config = $setting->config;

        if ($input['config']['driver'] == 'mailgun') {
            $this->validateMailgun($request);

            if(empty($input['config']['domain'])) {
                $input['config']['domain'] = @$config['domain'];
            }
            if(empty($input['config']['secret'])) {
                $input['config']['secret'] = @$config['secret'];
            }
        }

        if ($input['config']['driver'] == 'smtp') {
            $this->validateSMTP($request);

            if(empty($input['config']['host'])) {
                $input['config']['host'] = @$config['host'];
            }
            if(empty($input['config']['port'])) {
                $input['config']['port'] = @$config['port'];
            }
            if(empty($input['config']['username'])) {
                $input['config']['username'] = @$config['username'];
            }
            if(empty($input['config']['password'])) {
                $input['config']['password'] = @$config['password'];
            }
        }

        $setting->updateConfig($input['config']);
        info('updated config:');
        info(print_r($setting->config,1));

        Session::flash('success', "Settings were updated successfully");
        \Cache::forget('mail');

        return back();
    }

    public function updateMailDriver(Request $request)
    {
        $this->validate($request, [
            'config.driver' => 'required'
        ]);

        try{
            $input = $request->all();
            $input['config'] = array_filter($input['config']);

            $setting = Setting::where('key', 'mail')->first();

            $setting->updateConfig($input['config']);

            \Cache::forget('mail');

            return response()->json([
                'message' => 'Driver updated successfully!',
                'status' => true
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false
            ]);
        }
    }

    public function showGateway($value = '')
    {
        $setting = Setting::where('key', 'gateway')->first();

        $config = $setting->config;
        return view($this->viewsFolder . 'gateway', compact('setting', 'config'));
    }

    public function showGatewayAdmin($value='')
    {
        $setting = Setting::where('key','gateway')->first();

        $config = $setting->config;


        return view($this->viewsFolder . 'gateway-admin', compact('setting', 'config'));

    }

    public function updateGateway(Request $request)
    {
        $input = $request->all();
        $this->validateGateway($request);

        $setting = Setting::where('key', 'gateway')->first();
        $setting->updateConfig($input['config']);
        \Cache::forget('gateway');

        Session::flash('success', "Settings were updated successfully");

        return back();
    }


    public function gateway_mode_toggle(Request $request)
    {
        $input = $request->all();

        $this->validateGateway($request);

        $setting = Setting::where('key', 'gateway')->first();
        $setting->updateConfig($input['config']);
        \Cache::forget('gateway');

        Session::flash('success', "Settings were updated successfully");

        return back();
    }


    public function destroy_email($setting_id, $email = "")
    {
        $setting = Setting::find($setting_id);

        $success = $setting->removeEmail($email)->save();


        \Cache::forget('general');
        return response()->json('success', 200);
        //return response()->json(compact('success'));
    }

    public function validateMailgun($request)
    {

        $rules = [
            'config.from.name' => 'required',
            'config.from.address' => 'required|email',

        ];

        $this->validate($request, $rules);

    }


    public function validateSMTP($request)
    {

        $rules = [

            'config.from.name' => 'required',
            'config.from.address' => 'required|email',
        ];

        $this->validate($request, $rules);

    }


    public function validateGenral($request)
    {

        $rules = [
            'config.site_name' => 'required',
            'config.site_url' => 'required',
            'config.default_email' => 'required',
            'config.phone' => 'required',
            'config.admin_phone' => 'required',
        ];

        $this->validate($request, $rules);

    }


    public function validateGateway($request)
    {

        $rules = [
            'config.is_live' => 'required|in:0,1',
            'config.login_id' => 'required',
            'config.transaction_key' => 'required',
        ];

        //$this->validate($request, $rules);

    }

    public function sendEmail(SendEmailRequest $request)
    {

        // Create array of data
        $emailData = [
            'action' => 'email', // view , email
            'template' => 'simple',
            'subject' => 'Test Email',
            'to' => $request->get('to'),
            'emailContent' => [
                'message' => $request->get('message'),
            ]
        ];
        // send email
        try {
            $sent = sendEmail($emailData);
            if($sent){
                Session::flash('success', "Email sent successfully!");
                return back();
            }

            Session::flash('error', "Email sending failed!");
            return back()->withInput();

        } catch (\Exception $e) {
            Session::flash('error', "Email sending failed!");
            return back()->withInput();
        }

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function siteSetting()
    {
        $setting = Setting::query()->where('key', 'site')->first();
        $config = (object)@$setting->config;
        \Cache::forget('site');
        \Cache::forget('pages_static');
        \Cache::forget('sentry');

        return view('settings.new_site', compact('setting', 'config'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSiteSetting(Request $request)
    {
        \Cache::forget('site');
        $setting = Setting::query()->firstOrNew(
            ['key' => 'site'],
            [
                'view_name' => 'settings.new_site',
                'title' => 'Site Settings',
                'description' => '',
            ]
        );
        /*if ($request->config['script_mode']==1 && $request->config['tour_availability']!='equipment') {
            Session::flash('info','Tour availability must set to EQUIPMENT when script mode is TOURS ONLY');
            return back();
        }
        if ($request->config['script_mode']==2 && $request->config['tour_availability']!='guides') {
            Session::flash('info','Tour availability must set to GUIDES when script mode is TOURS ONLY');
            return back();
        }*/
        // dd($request->config);
        if (!$request->config) {
            $request->config = []; // we are using array_merge in sub functions
        }
        $setting->updateConfig($request->config);
        \Cache::forget('pages_static');
        \Cache::forget('site');
        \Cache::forget('sentry');

        // update sentry settings
        // $data['is_sentry_enabled'] = @$request->config['is_sentry_enabled'];
        // $data['sentry_laravel_dsn'] = @$request->config['sentry_laravel_dsn'];
        // $this->updateSentry($data);

        // $request->session()->flash('success', 'Updated successfully!');

        return back();
    }

    public function site_toggle_sidebar_pin(Request $request)
    {
        \Cache::forget('site');
        $setting = Setting::query()->firstOrNew(
            ['key' => 'site'],
            [
                'view_name' => 'settings.new_site',
                'title' => 'Site Settings',
                'description' => '',
            ]
        );
        $config['hide_side_bar']=config('site.hide_side_bar')?0:1;
        $setting->updateConfig($config);
        return $config;
    }


    /**
     * @return mixed
     */
    public function twilioSetting()
    {
        $messages = [];
        $error = null;

        $setting = Setting::query()->where('key', 'twilio')->first();

        if(! $setting) {
            return view('settings.twilio', compact( 'messages', 'error'));
        }

        $twilioSetting = (object) @$setting->config;
        $token = null;
        if($twilioSetting->token)
        {
            $divide = strlen($twilioSetting->token);
            for ($x = 1; $x < $divide; $x++) {
                $token = $token.'x';
            }
        }
        if (isset($_GET['show_sent'])) {
            try {
                $client = new Client(@$twilioSetting->sid, @$twilioSetting->token);
                // Get Recent Calls
                foreach ($client->account->messages->read() as $key => $call) {

                    $dateCreated = (array)$call->dateCreated;

                    $messages[$key]['from'] = $call->from;
                    $messages[$key]['to'] = $call->to;
                    $messages[$key]['body'] = $call->body;
                    $messages[$key]['date'] = date('m/d/Y  H:s', strtotime($dateCreated['date']));
                    $messages[$key]['status'] = $call->status;

                    //$time = $call->startTime->format("Y-m-d H:i:s");
                    //echo "Call from $call->from to $call->to body: $call->body \n";
                }
                return $messages;
            } catch (\Exception $e) {
                $error  = $e->getMessage();
            }
        }

        return view('settings.twilio', compact('twilioSetting', 'token', 'messages', 'error'));
    }
    public function buddySetting()
    {
        $messages = [];
        $error = null;

        $setting = Setting::query()->where('key', 'buddy')->first();
        if(! $setting) {
            return view('settings.buddy', compact( 'messages', 'error'));
        }

        $buddySetting = (object) @$setting->config;
        $partner_id = null;
        if($buddySetting->partner_id)
        {
            $divide = strlen($buddySetting->partner_id) - 10;
            for ($x = 1; $x < $divide; $x++) {
                $partner_id = $partner_id.'x';
            }
            $partner_id = $partner_id.substr($buddySetting->partner_id, -10);
        }

        return view('settings.buddy', compact('buddySetting', 'partner_id', 'messages', 'error'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTwilioSetting(Request $request)
    {
        $this->validate($request, [
            'config.sid' => 'required',
            'config.token' => 'required',
            'config.from' => 'required',
        ]);

        $setting = Setting::query()->firstOrNew(
            ['key' => 'twilio'],
            [
                'view_name' => 'settings.twilio',
                'title' => 'Twilio Settings',
                'description' => '',
            ]
        );


        $setting->updateConfig($request->config);

        $request->session()->flash('success', 'Settings saved.');
        return back();
    }
    public function updateTwilioSetting(Request $request)
    {
        $setting = Setting::query()->firstOrNew(
            ['key' => 'twilio'],
            [
                'view_name' => 'settings.twilio',
                'title' => 'Twilio Settings',
                'description' => '',
            ]
        );
        $setting->updateConfig($request->config);
        $request->session()->flash('success', 'Settings Saved.');
        return response([
            'status' => true,
         ]);
    }
    public function storebuddySetting(Request $request)
    {
        $this->validate($request, [
            'config.partner_id' => 'required',
        ]);

        $setting = Setting::query()->firstOrNew(
            ['key' => 'buddy'],
            [
                'view_name' => 'settings.buddy',
                'title' => 'Buddy Settings',
                'description' => '',
            ]
        );


        $setting->updateConfig($request->config);

        $request->session()->flash('success', 'Settings saved.');
        return back();
    }
    public function confirm_password(Request $request)
    {
        $mail_key = '';

       if(\Hash::check($request->password,Auth()->user()->password))
       {
           $status = true;
           $setting = Setting::query()->where('key', 'sms')->first();
           $smsSetting = (object) @$setting->config;
           $token = @$smsSetting->token;

           $gatewaySetting = Setting::query()->where('key', 'gateway')->first();
           $gatewaySetting = (object) @$gatewaySetting->config;
           if( @$gatewaySetting->gateway == "heartland")
           {
            $transaction_key = @$gatewaySetting->heartland;
            $key = $transaction_key['secret'];
           }elseif(  @$gatewaySetting->gateway ==  "stripe")
           {
            $transaction_key = @$gatewaySetting->stripe;
            $key = $transaction_key['secret'];
           }elseif(  @$gatewaySetting->gateway ==  "braintree")
           {
            $transaction_key = @$gatewaySetting->braintree;
            $key = $transaction_key['private_key'];
           }elseif(@$gatewaySetting->gateway == "squareup")
           {
            $transaction_key = @$gatewaySetting->squareup;
            $key = $transaction_key['access_token'];
           }
           else{
            $transaction_key = @$gatewaySetting->authorize;
            $key = $transaction_key['key'];
           }



           $setting = Setting::query()->where('key', 'mail')->first();
           $mailSetting = (object) @$setting->config;

           if($mailSetting->driver == 'mailgun' && $request->maildriver == 'mailgun')
           {
                $mail_key = $mailSetting->secret;
           }
           elseif($mailSetting->driver == 'smtp' && $request->maildriver == 'smtp')
           {
                $mail_key = $mailSetting->password;
           }
           elseif($mailSetting->driver == 'log' && $request->maildriver == 'log')
           {
                $mail_key = $mailSetting->password;
           }

       }
       else{
         $status = false;
         $token = null;
           $key = null;
           $mail_key = null;
       }
       return response([
           'status' => $status,
           'token' => $token,
            'transaction_key' => $key,
            'mail_key' => $mail_key
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function surveySetting()
    {
        $setting = Setting::query()->where('key', 'survey')->first();
        // $time = SendSurveyTime::query()->first();
        $config = (object) @$setting->config;
        \Cache::forget('survey');
        return view('settings.survey_settings', compact('setting', 'config'));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeSurveySetting(Request $request)
    {
        $configs = $request->config;

        // Storing logo image
        if ($request->hasFile('logo')) {
            $path = storeImage($request->file('logo'), 'survey_logo', 'logo');
            $configs = array_merge($configs, ['logo' => $path]);
        }

        $setting = Setting::query()->firstOrNew([
            'key' => 'survey',
            'view_name' => 'settings.survey',
            'title' => 'Survey Settings',
        ]);

        $setting->updateConfig($configs);

        $request->session()->flash('success', 'Updated successfully!');
        return redirect()->back();
    }

    // public function storeSurveySettingTime(Request $request)
    // {
    //     $SendSurveyTime = SendSurveyTime::query()->first();
    //     if(is_null($SendSurveyTime))
    //     {
    //         $SendSurveyTime = new SendSurveyTime;
    //         $SendSurveyTime->time = $request->time;
    //         $SendSurveyTime->save();
    //     }
    //     else{
    //         $SendSurveyTime->time =  $request->time;
    //         $SendSurveyTime->save();
    //     }

    //     $request->session()->flash('success', 'Time Updated successfully!');
    //     return redirect()->back();
    // }

    public function test_gateway_connection()
    {
        $payment_service = new PaymentService();
        $response=$payment_service->test_gateway_connection();

        if ($response->status=="success") {
            return response([
                    'message'=>$response->message,
                    'code'=>$response->code,
                ],200);
        }else{
            return response([
                    'message'=>$response->message,
                    'code'=>$response->code,
                ],422);
        }

    }

    public function update_term_of_use_accepted(Request $request)
    {
        $this->validate($request,[
            'term_of_use_accepted'=>'required'
        ]);

        $setting = Setting::query()->where('key', 'site')->first();
        $config['term_of_use_accepted']=$request->term_of_use_accepted?1:0;
        $config['term_of_use_accepted_datetime']=date('m/d/Y h:i A');
        $setting->updateConfig($config);

        $request->session()->flash('success', 'You accepted Terms Of Use');
        return redirect('admin/dashboard');
    }

    public function showRezoGateway($value = '')
    {
        $setting = Setting::where('key', 'rezo_gateway')->first();
        $config = $setting->config;
        return view($this->viewsFolder . 'rezo_gateway', compact('setting', 'config'));
    }

    public function updateRezoGateway(Request $request)
    {
        $input = $request->all();
        $this->validateGateway($request);
        $setting = Setting::where('key', 'rezo_gateway')->first();
        if($input['config']['mode'] == null)
        {
            $input['config']['mode'] = $setting->config['mode'];
            $input['config']['stripe']['publish'] = $setting->config['stripe']['publish'];
            $input['config']['stripe']['secret'] = $setting->config['stripe']['secret'];
        }

        $setting->updateConfig($input['config']);
        \Cache::forget('rezo_gateway');

        Session::flash('success', "Settings were updated successfully");

        return back();
    }


    public function rezo_gateway_mode_toggle(Request $request)
    {
        $input = $request->all();

        $this->validateGateway($request);

        $setting = Setting::where('key', 'rezo_gateway')->first();
        $setting->updateConfig($input['config']);
        \Cache::forget('rezo_gateway');

        Session::flash('success', "Settings were updated successfully");

        return back();
    }
    public function smsSetting()
    {
        $messages = [];
        $error = null;

        $setting = Setting::query()->where('key', 'sms')->first();
        if(! $setting) {
            return view('settings.sms', compact( 'messages', 'error'));
        }
        $smsSetting = (object) @$setting->config;
        $token = null;
        if($smsSetting->token)
        {

            $last_5_token_number = substr($smsSetting->token, -3);
            $token = 'XXXXXXXXX'.$last_5_token_number;
        }
        return view('settings.sms', compact('smsSetting', 'token', 'messages', 'error'));
    }
    public function storeSmsSetting(Request $request)
    {
        $this->validate($request, [
            'config.token' => 'required',
        ]);

        $setting = Setting::query()->firstOrNew(
            ['key' => 'sms'],
            [
                'view_name' => 'settings.sms',
                'title' => 'Sms Settings',
                'description' => '',
            ]
        );


        $setting->updateConfig($request->config);

        $request->session()->flash('success', 'Settings saved.');
        return back();
    }
    public function updateSmsSetting(Request $request)
    {
        $setting = Setting::query()->firstOrNew(
            ['key' => 'sms'],
            [
                'view_name' => 'settings.sms',
                'title' => 'Sms Settings',
                'description' => '',
            ]
        );
        $setting->updateConfig($request->config);
        $request->session()->flash('success', 'Settings Saved.');
        return response([
            'status' => true,
         ]);
    }
}
