<?php
/**
 * Created by PhpStorm.
 * User: Ali
 * Date: 4/24/2018
 * Time: 3:20 PM
 */

namespace App\Services;

use App\EmailTemplate;
use App\Models\Reservation;
use App\Models\User;
use App\ReservationEmail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Storage;

class TwilioSmsService
{

    function __construct()
    {

    }
    /**
     * @param Reservation $reservation
     */
    public function sendNewReservationDetialMS($reservation)
    {
        try {

            //$template = EmailTemplate::query()->bySubject(EmailTemplate::RESERVATION_DETAILS)->first();

            if ($reservation->discount_coupon) {
                $template = EmailTemplate::query()->bySubject(EmailTemplate::RESERVATION_DETAILS_WITH_DISCOUNT)->first();
            }else{
                $template = EmailTemplate::query()->bySubject(EmailTemplate::RESERVATION_DETAILS)->first();
            }
            $date     = Carbon::today();
            Log::info('SMS Sending for date ' . $date->toDateString());

            if ($template) {

                // Populating the variables in the Email Body of the template
                $template->getSmsBody($reservation->getSMSVariables());
                $template->dynamic_body = str_replace('<br>', '', $template->dynamic_body);
                $template->dynamic_body = str_replace('<br />', '', $template->dynamic_body);
                $template->dynamic_body = strip_tags($template->dynamic_body);

                $this->sendSMS($reservation->phone, $template->dynamic_body);

                // save sms in db
                $this->saveSMSToStorage($reservation, $template);

                Log::info('SMS send successfully to  ' . $reservation->full_name);

            }
        } catch (\Exception $e) {
                Log::error('SMS send FAILED to  ' . $reservation->full_name);
        }
    }

    /**
     * @param $phone
     * @param $msql()g
     * @return array
     */

    public function sendSMS($phone, $msg)
    {
        if(!config('site.sms_app') && !config('site.sms_token')){
            info('SMS APP SETTINGS OFF, SO NOT SENDING MSG');
            return false;
        }
        // format number required by twilio
        $to = '+1' . str_replace(['/', '(', ')', ' ', '-'], '', $phone);
        //$to = '+15053955372';
        try {
            $form_params = [
                'phone' => $to,
                'message' => $msg
            ];
            $bearerToken = config('site.sms_token');
            $headers    =   [
                    'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $bearerToken,
                    ],
                    'http_errors' => false,
                ];
            $url = "https://sms.rezosystems.com/api/clients/twilio/send-sms";
            $client = new \GuzzleHttp\Client($headers);
            $request = $client->request('POST',$url, ['form_params' =>
                    $form_params]);
            if($request->getStatusCode() == 200)
            {
                $status  = true;
                $message = 'Message sent at: ' . \Carbon\Carbon::now() . ' to: ' . $to . ' message: ' . $msg;
                \Log::info($message);
            }else{
                $response = $request->getBody()->getContents();
                $apiResult = json_decode($response, true);
                $message = $apiResult['message'][0];
                \Log::info($message);
                $status  = false;
            }
            return array('status' => $status, 'message' => $message);
        } catch (\Exception $e) {

            \Log::error('error sending email Exception: ' . $e->getMessage());
            $message = $e->getMessage();
            $status  = false;
        }
    }
    public function sendTwilioSMS($phone, $msg)
    {

        if (!config('site.sms_app') && !config('site.sms_token')) {
            info('TWILIO SETTINGS OFF, SO NOT SENDING MSG');
            return false;
        }
        // format number required by twilio
        $to = '+1' . str_replace(['/', '(', ')', ' ', '-'], '', $phone);
        //$to = '+15053955372';
        // Your Account SID and Auth Token from twilio.com/console
        $client = new \Twilio\Rest\Client(config('twilio.sid'), config('twilio.token'));

        // Use the client to do fun stuff like send text messages!
        try {
            $client->messages->create(
                $to, // the number you'd like to send the message to
                array(
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => config('twilio.from'),
                    // the body of the text message you'd like to send
                    'body' => $msg,
                )
            );

            $status  = true;
            $message = 'Message sent at: ' . \Carbon\Carbon::now() . ' to: ' . $to . ' message: ' . $msg;
            \Log::info($message);
        } catch (\Exception $e) {

            \Log::error('error sending email Exception: ' . $e->getMessage());
            $message = $e->getMessage();
            $status  = false;
        }
        return array('status' => $status, 'message' => $message);
    }

    public function saveSMSToStorage($reservation, $template)
    {
        if(!config('site.sms_app') && !config('site.sms_token')){
            info('SMS APP SETTINGS OFF, SO NOT SENDING MSG');
            return false;
        }
        // Putting reservation email template into the file of html
        $file_name = uniqid('SMS-') . '.txt';
        $view      = view('emails.default-sms')->with('data', $template)->render();
        Storage::disk('emails')->put($file_name, $view);

        // Storing record into the db
        return ReservationEmail::query()->create([
            'reservation_id'      => $reservation->id,
            'name'                => $file_name,
            'to'                  => $reservation->phone,
            'type'                => 'sms',
            'is_tour_reservation' => '0',
            'subject'             => $template->subject,
        ]);
    }
    public function saveSMSToStorageCustomSMS($reservation, $template,$to_number)
    {

        if(!config('site.sms_app')  && !config('site.sms_token')){
            info('SMS APP SETTINGS OFF, SO NOT SENDING MSG');
            return false;
        }
        // Putting reservation email template into the file of html
        $file_name = uniqid('SMS-') . '.txt';
        $view      = view('emails.default-sms')->with('data', $template)->render();
        Storage::disk('emails')->put($file_name, $view);

        // Storing record into the db
        return ReservationEmail::query()->create([
            'reservation_id'      => $reservation->id,
            'name'                => $file_name,
            'to'                  => $to_number,
            'type'                => 'sms',
            'is_tour_reservation' => '0',
            'subject'             => $template->subject,
        ]);
    }

    public function waiver_link_send($reservation,$is_tour_reservation)
    {
        try {

            $template = EmailTemplate::query()->bySubject(EmailTemplate::WAIVER_LINK_SMS)->first();


            if ($template) {

                // Populating the variables in the Email Body of the template
                $template->getEmailBody($reservation->waiver_link_send_sms_vars());
                $template->dynamic_body = str_replace('<br>', '', $template->dynamic_body);
                $template->dynamic_body = str_replace('<br />', '', $template->dynamic_body);
                $template->dynamic_body = strip_tags($template->dynamic_body);

                $this->sendSMS($reservation->phone, $template->dynamic_body);

                // save sms in db
                if ($is_tour_reservation==1) {
                    $content      = view('emails.default-sms')->with('data', $template)->render();
                    save_reservation_emails($reservation->id, 1, $reservation->full_name(), $reservation->phone, $template->subject, $content);
                }else{
                    $this->saveSMSToStorage($reservation, $template);
                }

                Log::info('SMS send successfully to  ' . $reservation->id);
                return true;

            }
        } catch (\Exception $e) {
                Log::error('SMS send FAILED to  ' . $reservation->id);
        }
    }
    public function gratuity_link_send($reservation,$is_tour_reservation)
    {
        try {

            $template = EmailTemplate::query()->bySubject(EmailTemplate::RENTAL_CONFIRMATION_SMS)->first();


            if ($template) {

                // Populating the variables in the Email Body of the template
                $template->getEmailBody($reservation->gratuity_link_send_sms_vars());
                $template->dynamic_body = str_replace('<br>', '', $template->dynamic_body);
                $template->dynamic_body = str_replace('<br />', '', $template->dynamic_body);
                $template->dynamic_body = strip_tags($template->dynamic_body);

                $this->sendSMS($reservation->phone, $template->dynamic_body);

                // save sms in db
                if ($is_tour_reservation==1) {
                    $content      = view('emails.default-sms')->with('data', $template)->render();
                    save_reservation_emails($reservation->id, 1, $reservation->full_name(), $reservation->phone, $template->subject, $content);
                }else{
                    $this->saveSMSToStorage($reservation, $template);
                }

                Log::info('SMS send successfully to  ' . $reservation->id);
                return true;

            }
        } catch (\Exception $e) {
                Log::error('SMS send FAILED to  ' . $reservation->id);
        }
    }

    public function sendAbandanCartNotifications($abandon_carts)
    {
        try {

            $template = EmailTemplate::query()->bySubject(EmailTemplate::ABANDON_CART)->first();


            if ($template) {

                foreach ($abandon_carts as $abandon_cart) {
                    $variables=array(
                        '[Customer_Name]' => $abandon_cart->customerName(),
                        '[site_name]' => config('general.site_name'),
                        '[site_phone]' => config('general.admin_phone'),

                    );

                    $text="A cart is abandoned at ".config('general.site_name')." at ".to_date($abandon_cart->created_at,1)." with following details:
                    Customer Name: ".$abandon_cart->customerName()."
                    Email: ".$abandon_cart->billingInfo()->email."
                    Mobile Phone: ".$abandon_cart->billingInfo()->phone."
                    ";

                    $this->sendSMS($abandon_cart->billingInfo()->phone, $text);

                // save sms in db

                    info("SMS send successfully to ABANDON_CART ID ".$abandon_cart->id);
                }

            }
        } catch (\Exception $e) {
                Log::error('SMS send FAILED to  ' . $reservation->full_name);
        }


    }

    public function sendAbandanCartNotificationsCustomer($abandon_carts)
    {
        try {

            $template = EmailTemplate::query()->bySubject(EmailTemplate::ABANDON_CART)->first();


            if ($template) {

                foreach ($abandon_carts as $abandon_cart) {
                    $variables=array(
                        '[Customer_Name]' => $abandon_cart->customerName(),
                        '[site_name]' => config('general.site_name'),
                        '[site_phone]' => config('general.phone'),

                    );

                    $template->getEmailBody($variables);
                    info("ABANDON_CART ID ".$abandon_cart->id);
                        $abandon_cart->update([
                        'checked'=>1,
                        'customer_sms_sent'=>1,
                    ]);

                    $template->dynamic_body = str_replace('<br>', '', $template->dynamic_body);
                    $template->dynamic_body = str_replace('<br />', '', $template->dynamic_body);
                    $template->dynamic_body = strip_tags($template->dynamic_body);

                    $this->sendSMS($abandon_cart->billingInfo()->phone, $template->dynamic_body);

                // save sms in db

                info("SMS send successfully to ABANDON_CART ID ".$abandon_cart->id);
                }


                return true;

            }
        } catch (\Exception $e) {
                Log::error('SMS send FAILED to  ' . $reservation->full_name);
        }


    }

    public function wavier_reminder($reservation)
    {
        try {

            $template = EmailTemplate::query()->bySubject(EmailTemplate::WAIVER_REMINDER_SMS)->first();


            if ($template) {

                // Populating the variables in the Email Body of the template
                $template->getEmailBody($reservation->waiver_reminder_sms_vars());
                $template->dynamic_body = str_replace('<br>', '', $template->dynamic_body);
                $template->dynamic_body = str_replace('<br />', '', $template->dynamic_body);
                $template->dynamic_body = strip_tags($template->dynamic_body);

                $this->sendSMS($reservation->phone, $template->dynamic_body);

                // save sms in db
                $this->saveSMSToStorage($reservation, $template);

                Log::info('SMS send successfully to  ' . $reservation->id);
                return true;

            }
        } catch (\Exception $e) {
                Log::error('SMS send FAILED to  ' . $reservation->id);
        }
    }

    public function bike_due_back_reminder($reservation)
    {
        try {

            $template = EmailTemplate::query()->bySubject(EmailTemplate::BIKE_DUE_BACK_REMINDER_SMS)->first();


            if ($template) {

                // Populating the variables in the Email Body of the template
                $template->getEmailBody($reservation->waiver_reminder_sms_vars());
                $template->dynamic_body = str_replace('<br>', '', $template->dynamic_body);
                $template->dynamic_body = str_replace('<br />', '', $template->dynamic_body);
                $template->dynamic_body = strip_tags($template->dynamic_body);

                $this->sendSMS($reservation->phone, $template->dynamic_body);

                // save sms in db
                $this->saveSMSToStorage($reservation, $template);

                Log::info('SMS send successfully to  ' . $reservation->full_name);
                return true;

            }
        } catch (\Exception $e) {
                Log::error('SMS send FAILED to  ' . $reservation->full_name);
        }
    }

    public function bike_due_back_one_hour_reminder($reservation)
    {
        try {

            $template = EmailTemplate::query()->bySubject(EmailTemplate::BIKE_DUE_BACK_ONE_HOUR_REMINDER_SMS)->first();


            if ($template) {

                // Populating the variables in the Email Body of the template
                $template->getEmailBody($reservation->waiver_reminder_sms_vars());
                $template->dynamic_body = str_replace('<br>', '', $template->dynamic_body);
                $template->dynamic_body = str_replace('<br />', '', $template->dynamic_body);
                $template->dynamic_body = strip_tags($template->dynamic_body);

                $this->sendSMS($reservation->phone, $template->dynamic_body);

                // save sms in db
                $this->saveSMSToStorage($reservation, $template);

                Log::info('SMS send successfully to  ' . $reservation->full_name);
                return true;

            }
        } catch (\Exception $e) {
                Log::error('SMS send FAILED to  ' . $reservation->full_name);
        }
    }

    public function custom_sms($reservation,$message,$to_number)
    {
        try {

                $this->sendSMS($to_number, $message);

                // save sms in db
                $template=new EmailTemplate;
                $template->dynamic_body=$message;
                $template->subject="Custom SMS";
                $this->saveSMSToStorageCustomSMS($reservation, $template,$to_number);

                Log::info('SMS send successfully to  ' . $reservation->full_name);
                return true;


        } catch (\Exception $e) {
                Log::error('SMS send FAILED to  ' . $reservation->full_name);
        }
    }

}
