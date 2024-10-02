<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail, DB, DateTime;
use App\Http\Requests;
use App\EmailHisotry;
use App\Enums\ReservationType;
use App\EmailTemplate;
use App\Models\SentEmail;

class EmailController extends Controller
{

    public static function getAdminName()
    {
       return config('general.site_name');
    }

    public static function getAdminEmail()
    {
       return config('general.default_email');
    }


    public static function emailPreview( $emailData )
    {
        return view('emails.'. $emailData['template'] )->with('emailData', $emailData );
    }

    public static function sendEmail($emailData) {
       try {
        $emailData['view'] = view('emails.'. $emailData['template'])->with('emailData', $emailData);

        // test view
        if (!empty($emailData['action']) && $emailData['action'] == 'view') {
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
        // throw $th;
        return false;
       }
    }
    
    public static function sendCustomerEmail($reservationData)
    {
        if ($reservationData->status != '1') {
            $emailTemplate = EmailTemplate::query()->bySubject(ReservationType::TYPE_PENDING_RESERVATION->value)->first();
        } else {
            $emailTemplate = EmailTemplate::query()->bySubject(ReservationType::TYPE_BOOKED_RESERVATION->value)->first();
        }
        
        $fullName = explode(' ', $reservationData->customer->name, 2);

        $variables  = array(
            '[guest_name]' => $reservationData->customer->name,
            '[firstname]' => $fullName[0],
            '[lastname]' => $fullName[1],
            '[property]' => $reservationData->property->title,
            '[housekeeper]' => $reservationData->housekeeper->first_name . $reservationData->housekeeper->last_name,
            '[date_start]' => date('m/d/Y', strtotime($reservationData->arrival)),
            '[date_end]' => date('m/d/Y', strtotime($reservationData->departure)),
            '[nights]' => self::getNumberONights($reservationData->arrival, $reservationData->departure),
            '[adults]' => $reservationData->adults,
            '[children]' => $reservationData->children,
            '[pets]' => $reservationData->pets,
            '[lodging]' => price_format($reservationData->payments->sum('lodging_amount'),2),
            '[sales_tax]' => price_format($reservationData->payments->sum('sales_tax'),2),
            '[lodgers_tax]' => price_format($reservationData->payments->sum('lodgers_tax'),2),
            '[total_amount]' => price_format($reservationData->payments->sum('total'),2),

        );
        
        if ($emailTemplate) {
            // replace variables
            $emailContent = $emailTemplate->getEmailBody($variables);
        }

        // $emailContent = str_replace('[pets]', '',  $emailContent);
        // $emailContent = str_replace('Pets', '',  $emailContent);

        $emailData = [
            'action' => 'email',
            'template' => 'empty',
            'subject' => $emailTemplate->subject,
            'from' =>  config('mail.from.address'),
            'fromName' =>  config('mail.from.name'),
            'to' =>$reservationData->customer->email,
            'toName' => $reservationData->customer->name,
            'emailContent' => $emailContent,
        ];
         $data = [
            'GuestEmail' => $reservationData->customer->email,
            'Guest'       => $reservationData->customer->name,
            'Subject'     => $emailTemplate->subject,
            'Status'      => 'Sent',
            'Attachment'   => '',
            'body'        => $emailData['emailContent'],
            'reservation_id'  => $reservationData->id,
            'eemail'      => $emailData,  
        ];
        /* getting data for email savage */
        $content = $emailData['emailContent'];
        $gemail  = $data['GuestEmail'];
        $gemail  = $gemail;
        $guest   = $data['Guest'];
        $eemail  = $emailData;
        $subject = $emailTemplate->subject;
        $reservation_id = $reservationData->id;

        /* getting data for email savage */
        $sendemails = new SentEmail();
        $sendemails->sentto = 'customer';
        $sendemails->gemail = $reservationData->customer['email'];
        $sendemails->guest = $reservationData->customer['name'];
        $sendemails->subject = $subject;
        $sendemails->status = 'sent';
        $sendemails->attachment = '';
        $sendemails->body = $content;
        $sendemails->eemail = json_encode($eemail);
        $sendemails->reservation_id = $reservation_id;
        $sendemails->save();
        
        // history ..
        self::sendEmail($emailData);
    }
    
    public static function sendHouseKeeperEmail( $reservationData )
    {
        $emailTemplate = EmailTemplate::query()->bySubject(ReservationType::TYPE_HOUSEKEEPER_JOB_ASSIGNED->value)->first();

        $fullName = explode(' ', $reservationData->customer->name, 2);
        $variables = array(
            '[guest_name]' => $reservationData->customer->name,
            '[firstname]' => $fullName[0],
            '[lastname]' => $fullName[1],
            '[property]' => $reservationData->property->title,
            '[housekeeper]' => $reservationData->housekeeper->first_name . $reservationData->housekeeper->last_name,
            '[date_start]' => date('m/d/Y', strtotime($reservationData->arrival)),
            '[date_end]' => date('m/d/Y', strtotime($reservationData->departure)),
            '[nights]' => self::getNumberONights($reservationData->arrival, $reservationData->departure),
            '[adults]' => $reservationData->adults,
            '[children]' => $reservationData->children,
            '[pets]' => $reservationData->pets,
            '[sum_detail]' => '',
            '[total_amount]' => price_format($reservationData->total_amount,2),
        );

        if ($emailTemplate) {
            $emailContent =  $emailTemplate->getEmailBody($variables);
        }

        $emailData = [
            'action' => 'email',
            'template' => 'empty',
            'subject' => $emailTemplate->subject,
            'from' => config('mail.from.address'),
            'fromName' => config('mail.from.name'),
            'to' => $reservationData->housekeeper->email,
            'toName' => $reservationData->housekeeper->first_name . $reservationData->housekeeper->last_name,
            'emailContent' => $emailContent,
        ];
        
        $data = [
            'GuestEmail' => $reservationData->housekeeper->email,
            'Guest' => $reservationData->housekeeper->first_name . $reservationData->housekeeper->last_name,
            'Subject' => $emailTemplate->subject,
            'Status' => 'Sent',
            'Attachment' => '',
            'body' => $emailData['emailContent'],
            'reservation_id' => $reservationData->id,
            'eemail' => $emailData,  
        ];
        
        /* getting data for email savage */
        $content = $emailData['emailContent'];
        $gemail  = $data['GuestEmail'];
        $gemail  = $gemail;
        $guest   = $data['Guest'];
        $eemail  = $emailData;
        $subject = $emailTemplate->subject;
        $reservation_id = $reservationData->id;

        /* getting data for email savage */
        $sendemails = new sentemail();
        $sendemails->sentto = 'housekeeper';
        $sendemails->gemail = $reservationData->housekeeper['email'];
        $sendemails->guest = $reservationData->housekeeper['first_name'].' '.$reservationData->housekeeper['last_name'];
        $sendemails->subject = $subject;
        $sendemails->status = 'sent';
        $sendemails->attachment = '';
        $sendemails->body = $content;
        $sendemails->eemail = json_encode($eemail);
        $sendemails->reservation_id = $reservationData->id;
        $sendemails->save(); 

        // history ..
        self::sendEmail($emailData);
    }
    
    public static function sendAdminEmail($reservationData)
    {
        $emailTemplate = EmailTemplate::query()->bySubject(ReservationType::TYPE_BOOKED_RESERVATION->value)->first();

        $fullName = explode(' ', $reservationData->customer->name, 2);
        $variables  = array(
            '[guest_name]' => $reservationData->customer->name,
            '[firstname]' => $fullName[0],
            '[lastname]' => $fullName[1],
            '[property]' => $reservationData->property->title,
            '[housekeeper]' => $reservationData->housekeeper->first_name . $reservationData->housekeeper->last_name,
            '[date_start]' => date('m/d/Y', strtotime($reservationData->arrival)),
            '[date_end]' => date('m/d/Y', strtotime($reservationData->departure)),
            '[nights]' => self::getNumberONights($reservationData->arrival, $reservationData->departure),
            '[adults]' => $reservationData->adults,
            '[children]' => $reservationData->children,
            '[pets]' => $reservationData->pets,
            '[lodging]' => price_format($reservationData->payments->sum('lodging_amount'),2),
            '[sales_tax]' => price_format($reservationData->payments->sum('sales_tax'),2),
            '[lodgers_tax]' => price_format($reservationData->payments->sum('lodgers_tax'),2),
            '[total_amount]' => price_format($reservationData->payments->sum('total'),2),
        );

        if ($emailTemplate) {
            $emailContent =  $emailTemplate->getEmailBody($variables);
        }

        $emailData = [
            'action' => 'email',
            'template' => 'empty',
            'subject' => $emailTemplate->subject,
            'from' => config('mail.from.address'),
            'fromName' => config('mail.from.name'),
            'to' => config('general.default_email'),
            'toName' => config('general.site_name'),
            'emailContent' => $emailContent,
        ];

        self::sendEmail($emailData);
    }
    
    private static function getNumberONights($arrival, $departure)
    {
        $date1 = new DateTime($arrival);
        $date2 = new DateTime($departure);

        return $date2->diff($date1)->format("%a");
    }
}
