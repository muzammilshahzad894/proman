<?php 
namespace App\Services;
use GuzzleHttp\Client;
use DB;
use App\InsurancePersons;
use Stripe\Stripe;

Class BuddyService{

//BUDDY DOCUMENTATION LINK
//https://documenter.getpostman.com/view/2732462/S17oxA6N?version=latest#b7fee9a9-3a19-4e1f-95b8-f9325e03ff39

    
    //setting buddy 
    /*
      -SITE SETTING ME JA K BUDDY API SETTING ON KARNA HAI.
      -EK ADDON CREATE KARNA HAI, OSKA RADIO YES/NO on KAR DENA HAI. 
      -ADDON KO PRODUCT SE CONNET KARNA HAI. 
      -IS ADDON KI ID SITE SETTINGS ME BUDDY ADDON ID ME ENTER KARNI HAI.
      -SITE STATE K DROP DOWN SE CLIENT KI STATE SELECT KARNI HAI.

      -YEH SB KARNY K BAAD BUDDY ENABLE HO JAYE GA. AB FRONTEND RESERVATION PE PRODUCT KA BUDDY WALA ADDON AGAR SELECT KREIN GAY TO WO BUDDY SE OSKI PRICE FETCH KARAY GA.



      changing keys: helper me ja k Buddy Partner ID change kar sakty hain sandbox/production
      stripe key isi page pe stripe k function se change ho gi.
      base URL change karny k liye 2 jaga pe change hoga
        1) Helper k buddy url constant me.
        2) Helper k quotation function me.




    */


    public function postData($reservation,$request)
    {   

        //this function is handling ORDERING buddy insurance.
        try {

          $total_insurance_amount=0;

          foreach ($reservation->insurance_persons as $insurance_person_index => $insurance_person) {
            




            //$reservee=DB::table('reservees')->where('reservation_id',$reservation->id)->where('inventory_id',$insurance_person->inventory_id)->first();

            //$amount=$request->buddy_price; //THIS WILL BE QUOTATION AMOUNT CALCULATED FOR SINGLE PERSON.
            $amount=@session('reservee')[$insurance_person_index]['reservee_buddy_price'];

            if ($insurance_person->first_name=="-" || is_null($amount)) {
              continue;
            }

            $total_insurance_amount +=$amount;
            $singleCustomer=
              array (
                'customer' => 
                array (
                  'name' => $insurance_person->first_name." ".$insurance_person->last_name,
                  'dob' => $insurance_person->dob,
                  'email' => $insurance_person->email,
                  'address' => 
                  array (
                    'line1' => $insurance_person->address,
                    'city' => $insurance_person->city,
                    'state' => $insurance_person->state,
                    'zip' => $insurance_person->zip,
                  ),
                ),
                'policy' => 
                array (
                  'startDate' => $reservation->booking_date,
                  'endDate' => $reservation->return_date,
                  'who' => 'ME',
                  'activities' => 
                  array (
                    0 => " Reservation # ".$reservation->id,
                  ),
                  'premiumTotal' => $amount,
                ),
              );
              $allCustomers[]=$singleCustomer;

            }


            info("buddy stripe charging amount $total_insurance_amount");
            $payment_response=$this->chargeForBuddy($total_insurance_amount,$request);
            info("buddy stripe charging response: ".print_r($payment_response,1));
            if (!$payment_response->success) {

              InsurancePersons::where('reservation_id',$reservation->id)->update([
                'transaction_id'=>null,
                'buddy_order_id'=>null,
                'buddy_response_msg'=>$payment_response->message,
                'buddy_success_response'=>false,
                'buddy_insurance_amount'=>$amount,
              ]);


              return  (Object) [
                'success'=>false,
                'Payment_error'=>true,
                'message'=>$payment_response->message,
                'error'=>$payment_response->message,
              ];
            }else{
              $transactionId=$payment_response->transactionId;
            }
            
            $buddyPostData=[
                              'partnerID' => config('buddy.partner_id'),
                              'venue' => [
                                'name' => config('general.site_name'),
                                'state' => config('site.vanue_statue'),
                              ],
                              'offering' => 'BUDDY_ACCIDENT_V2',
                              'orders' => $allCustomers,
                              'paymentInfo' => [
                                'paymentType' => 'STRIPE_CONNECT',
                                'chargeID' => $transactionId,
                              ],
                            ];

            info("sending data to buddy: ".print_r($buddyPostData,1));

            $url = config('buddy.url');

            $headers = array(
                "Content-Type: application/json"
              );

            
            $body = json_encode($buddyPostData);
            $options = [$headers, $body];
            //$client = new Client();
            //dd($client->post($url, $options));
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS =>$body,
              CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);


            curl_close($curl);
            info("BUDDY RESPONSE: ".  print_r($response,1));
            $response=json_decode($response);

            if ($response->ok) {
              info("Data sent successfully");
              InsurancePersons::where('reservation_id',$reservation->id)->update([
                'transaction_id'=>$transactionId,
                'buddy_order_id'=>$response->orderID,
                'buddy_success_response'=>true,
                'buddy_insurance_amount'=>$amount,
              ]);
            }else{
              info("sending data to buddy FAILED: ".  print_r($response,1));
              InsurancePersons::where('reservation_id',$reservation->id)->update([
                'transaction_id'=>$transactionId,
                'buddy_order_id'=>null,
                'buddy_response_msg'=>@$response->errorCode."-".@$response->message."-".@$response->error,
                'buddy_success_response'=>false,
                'buddy_insurance_amount'=>$amount,
              ]);
            }

            

            /*$insurance_person->update([
              'transaction_id'=>$transactionId,
              'buddy_success_response'=>true,
              'buddy_insurance_amount'=>$amount,
            ]);*/
            return $response;
        } catch (\Exception $e) {
          info("sending data to buddy FAILED IN Exception: ".print_r($e->getMessage(),1));
          return  (Object) [
                'success'=>false,
                'message'=>$e->getMessage(),
                'error'=>$e->getMessage(),
              ];
        }

        
        //return  $reservation;
        
        //return $response->ok;
    }

    public function chargeForBuddy($amount,$request)
    {
        
          info("CHARGING $amount for buddy");

          try {
            \Stripe\Stripe::setApiKey('');
            

                $token = \Stripe\Token::create([
                     'card' => [
                        'number' => $request->cnumber,
                        'exp_month' => $request->card_expiry_month,
                        'exp_year' => $request->card_expiry_year,
                        'cvc' => $request->cvv,
                      ],
                ]);

            $charge = \Stripe\Charge::create(array(

                'amount' => $amount*100,
                'currency' => 'usd',
                'source' => $token['id'],
                'description' => 'CHARGE FOR BUDDY.',
            ));

            
            info("STRIPE RESULT: ".print_r($charge,1));
            
            $data['success']                   =true;
            $transaction_id                   = $charge->id;
            $card_last_four                   = $charge->source->last4;
            $data['transactionId']           = $transaction_id;
            $data['last4']           = $card_last_four;
            $data['message']                  = "done";
            $data['status']                   = "success";

            info("CHARGING buddy successful.");
            return (Object) $data;
            

        } catch (\Exception $e) {
            info("CHARGING buddy stripe charge error " . $e->getMessage());
            
                $data['success']                   =false;
                $data['status']                   = "error";
                $data['transactionId']            = 0;
                $data['last4']                    = 0;
                $data['customerProfile']        = "";
                $data['customerProfilePayment'] = "";
                $data['message']                    = "ERROR: ".$e->getMessage();
                $data['error']                    = "ERROR: ".$e->getMessage();

                return  (Object)  $data;

        } 
    }

}

 ?>