<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail, DB;
use App\Http\Requests;
use App\EmailHisotry;

class EmailController extends Controller
{

    public static function getAdminName()
    {
       return   config('general.site_name');
    }

    public static function getAdminEmail()
    {
       return  config('general.default_email');
    }


    public static function emailPreview( $emailData )
    {

         return view('emails.'. $emailData['template'] )->with('emailData', $emailData );

    }

    public static function sendEmail( $emailData ) {

       try {
        $emailData['view'] = view('emails.'. $emailData['template'] )->with('emailData', $emailData );
       // $email = EmailHisotry::storeEmail($emailData);

        // test view
        if ( !empty($emailData['action']) && $emailData['action'] == 'view' ) {

            return view('emails.'. $emailData['template'] )->with('emailData', $emailData );
        }

        Mail::send('emails.'.$emailData['template'], ['emailData' => $emailData] , function ($message) use ($emailData) {

            $message->subject( $emailData['subject'] );
            $message->to( $emailData['to'] , (isset($emailData['toName']) ) ? $emailData['toName'] : '' );

            if( isset($emailData['cc']) && !empty($emailData['cc']) ) {
                $message->cc( $emailData['cc'] , $emailData['ccName'] );
            }

            if(isset($emailData['bcc']) && !empty($emailData['bcc']) ) {
                $message->cc( $emailData['bcc'] , $emailData['bccName'] );
            }
            if( !isset($emailData['from']) || empty( $emailData['fromName']) ) {

                $message->from( config('mail.from.address') , config('mail.from.name')  );

            } else {
                $message->from( $emailData['from']  , $emailData['fromName']  );

            }


        });
        if (Mail::flushMacros()) {
            // $email->status = 0;
            // $email->save();
            return false;
        } else {
            // $email->status = 1;
            // $email->save();
            return true;
        }
        //code...
       } catch (\Throwable $th) {
        //throw $th;
        return false;
       }


    }


}
