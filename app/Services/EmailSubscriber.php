<?php

namespace App\Services;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class EmailSubscriber 
{   

    private static $constantRes; 
        
    public static function subscribe( $reservation  )
    {
       //self::mailChimpSubscriber( $reservation ); 
       if ( env('CLIENT_NAME') == 'ozark' ) {
            return;
            self::ozarkEmailSubscriber( $reservation ); 
       }

       if ( config('site.email_subcriber_api') == 'mailchimp' ) {
         
            self::mailChimpSubscriber( $reservation ); 
       }
       if ( config('site.email_subcriber_api') =='mailer_lite' ) {
            return;
            self::mailerliteSubscriber( $reservation ); 
       }
       if ( config('site.email_subcriber_api') =='vertical_response' ) {
            return;
            self::verticalresponseSubscriber( $reservation ); 
       }
       if ( config('site.email_subcriber_api') =='constant_contact' ) {
            return;
            self::constantcontactSubscriber( $reservation ); 
       }

    }
    public function constantcontactSubscriber($reservation)
    {
        $email = $reservation->customer->email;
        $first_name = @$reservation->customer->first_name; 
        $last_name = @$reservation->customer->last_name; 
        $phone = @$reservation->customer->phone;
        $company = @$reservation->customer->company;

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.constantcontact.com/v2/lists?api_key='.config('site.constantcontact_api_key'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.config('site.constantcontact_access_token')
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $lists = json_decode($response);
        foreach ($lists as $list) {
          $list_id =  $list->id;
          break;
        }  

         $subscriber =[
                    'email_addresses' => 
                    [
                       [
                          'email_address' => $email,
                        ],
                    ],
                    'lists' => 
                    [
                      [
                        'id' => $list_id,
                      ],
                    ],
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'company_name' => $company
                  ];
                  

                $curl = curl_init();
                  curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.constantcontact.com/v2/contacts?api_key='.config('site.constantcontact_api_key'),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($subscriber),
                    CURLOPT_HTTPHEADER => array(
                      'Authorization: Bearer '.config('site.constantcontact_access_token'),
                      'Content-Type: application/json'
                    ),
                  ));

                $response = curl_exec($curl);
                curl_close($curl);
                info(json_encode($response)); 


    }
    public static function verticalresponseSubscriber($reservation)
    {
       $email = $reservation->customer->email;
        $first_name = @$reservation->customer->first_name; 
        $last_name = @$reservation->customer->last_name; 
        $phone = @$reservation->customer->phone; 
        $company = @$reservation->customer->company; 

      // $groupsApi = (new MailerLiteApi\MailerLite(config('site.mailerlite_api_key')))->groups();
          $subscriber = [
              'email' => $email,
              'first_name' => $first_name,
              "company" => $company,
              "mobile_phone" => $phone,
          ];
        // $response = $groupsApi->addSubscriber(GROUP_ID, $subscriber); // Change GROUP_ID with ID of group you want to add subscriber to
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://vrapi.verticalresponse.com/api/v1/contacts',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($subscriber),
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer '.config('site.verticalresponse_access_token'),
          'Content-Type: application/json',
        ),
      ));


         try {
         
          $response = curl_exec($curl);
            curl_close($curl);
         
         info( 'verticalresponseSubscriber');
         // info( $response );

       } catch (\Exception $e) {
          
          info('error adding verticalresponseSubscriber') ;

          info(json_encode($postData)); 
       }

    }

    public static function mailerliteSubscriber($reservation)
    {
       info('MailerLite function start');
       $email = $reservation->customer->email;
        $first_name = @$reservation->customer->first_name; 
        $last_name = @$reservation->customer->last_name; 
        $phone = @$reservation->customer->phone; 
         $company = @$reservation->customer->company; 

      // $groupsApi = (new MailerLiteApi\MailerLite(config('site.mailerlite_api_key')))->groups();
          $subscriber = [
              'email' => $email,
              'fields' => [
                  'name' => $first_name,
                  'last_name' => $last_name,
                  'company' => $company
              ]
          ];
          info('MailerLite function subscriber DATA:'.print_r($subscriber,1));
        // $response = $groupsApi->addSubscriber(GROUP_ID, $subscriber); // Change GROUP_ID with ID of group you want to add subscriber to

           $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.mailerlite.com/api/v2/subscribers',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($subscriber),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'X-MailerLite-ApiKey:'.config('site.mailerlite_api_key'),
          ),
        ));

         try {
         
          $response = curl_exec($curl);
            curl_close($curl);
         
         info('MailerLite function subscriber added. DONE');
         // info( $response );

       } catch (\Exception $e) {
          
          info('error adding mailerliteSubscriber') ;

          info(json_encode($postData)); 
       }

    }
    public static function ozarkEmailSubscriber( $reservation  )
    {
       $OZARK_API_KEY =  env('OZARK_API_KEY'); 
       $OZARK_TOKEN =  env('OZARK_TOKEN'); 


       $jsonData = [
           "email_addresses" => [ ],
           "first_name" => "",
           "last_name" => "",
           "cell_phone" => "",
           "lists" => [["id" => ""]],
           "status" => "ACTIVE"
       ];

       // set values 
       $jsonData['email_addresses'][0] =  ['email_address' => $reservation->customer->email];
       $jsonData['first_name'] =  @$reservation->customer->first_name; 
       $jsonData['last_name'] =  @$reservation->customer->last_name; 
       $jsonData['cell_phone'] =  @$reservation->customer->phone; 
        
          
       $headers = [
            'headers' => [
               'Cache-control' => 'no-cache',
               'Authorization' => 'Bearer '.$OZARK_TOKEN,
               'Accept' => 'application/json',
               'Content-Type' => 'application/json',
           ]
        ];

         

        $client = new Client($headers);

        $url = 'https://api.constantcontact.com/v2/contacts?action_by=ACTION_BY_VISITOR&api_key='.$OZARK_API_KEY; 
       
        try {
            
            self::$constantRes = $client->post( $url ,[ \GuzzleHttp\RequestOptions::JSON => $jsonData ] );             
            
            \Log::info( $reservation->first_name . ' ' . $reservation->last_name . ' added in constantcontact list');

        } catch (\Exception $e) {
            
         
         \Log::info(  $e ); 

         \Log::info( 'error in adding ozark constantcontact list');

        }


    } 

    public static function mailChimpSubscriber($reservation)
    {


      try {
        

      info("MailchimpSubscriber: User Information : Function Start");

        $email = $reservation->email;
        $first_name = @$reservation->first_name ; 
        $last_name = @$reservation->last_name ; 
        $phone = @$reservation->phone; 

        $address = @$reservation->fullAddress(); 

        $mailchimp_tag_name  = config('site.mailchimp_tag_name'); 

        if (empty($mailchimp_tag_name)) {
          $mailchimp_tag_name = "Added via ".config('general.site_name');
        }

      // API to mailchimp ########################################################
      // $authToken='fb166a76b65f1cca3c98878c62cddf28-us1';
      // $list_id='be9726092e';
       $authToken = config('site.mailchimp_api_key');
       $list_id  = config('site.mailchimp_api_list_id'); 

      //  $server_id  = config('site.mailchimp_api_server_id'); 
       // The data to send to the API

       $status = "subscribed";
       if (config('site.mailchimp_double_opt_in')){
           $status = "pending";
       }

       $postData = array(
           "email_address" => "$email", 
           "status" => $status,
           'tags'   => [$mailchimp_tag_name], 
           'merge_fields' => [
                'FNAME' => $first_name,
                'LNAME' => $last_name,
                'PHONE' => $phone,
                'ADDRESS' => $address,
              ]
       );

       info( 'mailChimpSubscriber Sending DATA: '.print_r($postData,1));


       $server_id=explode('-',$authToken)[1];
       //dd($server_id);
       // Setup cURL us14
       $ch = curl_init('https://'.$server_id.'.api.mailchimp.com/3.0/lists/'.$list_id.'/members');

       curl_setopt_array($ch, array(
           CURLOPT_POST => TRUE,
           CURLOPT_RETURNTRANSFER => TRUE,
           CURLOPT_HTTPHEADER => array(
               'Authorization: apikey '.$authToken,
               'Content-Type: application/json'
           ),
           CURLOPT_POSTFIELDS => json_encode($postData)
       ));
       // Send the request
       
       
         
         $response = curl_exec($ch);
         curl_close($ch);
         //dd('success');
         info( 'mailChimpSubscriber done successfully. RESPONSE: '.print_r($response,1));
         // info( $response );
         
        } catch (\Exception $e) {
          
          //dd('error');
          info('error adding mailchip') ;

          info(json_encode($postData)); 
       }

       // ####################################################################### 

    }  
}
