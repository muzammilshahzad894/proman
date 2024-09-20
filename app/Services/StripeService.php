<?php

namespace App\Services;


use Stripe\Charge;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Illuminate\Http\Request;
use App\Services\Interfaces\GatewayAdapterInterface;

class StripeService  implements GatewayAdapterInterface
{ 

  private $getStripeSecretKey; 
  
  public function __construct(  )
  {
    /*$this->getStripeSecretKey = config('gateway.stripe.secret');

    Stripe::setApiKey( $this->getStripeSecretKey);*/

    $mode = config('gateway.mode');
        if ($mode == 'demo') {
            
            $this->getStripeSecretKey = "";
            Stripe::setApiKey($this->getStripeSecretKey);
        } else {
            $this->getStripeSecretKey = config('gateway.stripe.secret');
            Stripe::setApiKey($this->getStripeSecretKey);
        }

  }


  public function chargeCard( Request $request )
  { 

      $amount = number_format($request->amount,2,'.','');
        info("creating stripe transaction WITH DATE: ".print_r($request->all(),1));
        $mode = config('gateway.mode');
        if ($mode == 'demo') {
            $stripe = array(
                "publishable_key" => "",
                "secret_key"      => "",
            );
        } else {
            $stripe = array(
                "publishable_key" => config('gateway.stripe.publish'),
                "secret_key"      => config('gateway.stripe.secret'),
            );
        }
        info("Stripe Gateway Mode: ".print_r($mode,1));

        try {
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            if (!$request->charge_customer ) {

                $token = \Stripe\Token::create([
                    'card' => [
                        'number'    => $request->cnumber,
                        'exp_month' => $request->card_expiry_month,
                        'exp_year'  => $request->card_expiry_year,
                        'cvc'       => $request->cvv,
                    ],
                ]);

                $customer = \Stripe\Customer::create(array(
                    'email'       => $request['email'],
                    'description' => $request->first_name . " " . $request->last_name,
                    'source'      => $token['id'],
                ));
                $customer_profile         = $customer->id;
                $customer_payment_profile = 0;
            } else {
                $customer_profile         = $request->customer_profile;
                $customer_payment_profile = 0;
            }

            $currency_code = config('site.checkout_currency')==""?"usd":config('site.checkout_currency');
            info("STRIPE CREATED CUSTOMER ID: ".$customer_profile);
            $charge = \Stripe\Charge::create(array(

                "description" => config('general.site_name') . " Reservation ($request->first_name $request->last_name) ",
                'amount'      => $amount * 100,
                'currency'    => $currency_code,
                'customer'    => $customer_profile,
            ));

            $customer_profile_id = $customer_profile;
            info("STRIPE RESULT: ".print_r($charge,1));
            
            $data['success']                   =true;
            $transaction_id                   = $charge->id;
            $card_last_four                   = $charge->source->last4;
            $data['transactionId']           = $transaction_id;
            $data['last4']           = $card_last_four;
            $data['customerProfile']         = $customer_profile_id;
            $data['customerProfilePayment'] = "";
            $data['message']                  = "done";
            $data['status']                   = "success";
            return (object) $data;
            

        } catch (\Exception $e) {
            info("stripe charge error " . $e->getMessage());
                $data['success']                   =false;
                $data['status']                   = "error";
                $data['transactionId']            = 0;
                $data['last4']                    = 0;
                $data['customerProfile']        = "";
                $data['customerProfilePayment'] = "";
                $data['message']                    = "ERROR: ".$e->getMessage();
                $data['error']                    = "ERROR: ".$e->getMessage();

                return (object) $data;

        }

    
  }

  public function createCustomerProfile( Request $request )
  {
      
        $amount = number_format($request->amount,2,'.','');
        info("creating stripe transaction for amount: $amount");
        $mode = config('gateway.mode');
        if ($mode == 'demo') {
            $stripe = array(
                "publishable_key" => "",
                "secret_key"      => "",
            );
        } else {
            $stripe = array(
                "publishable_key" => config('gateway.stripe.publish'),
                "secret_key"      => config('gateway.stripe.secret'),
            );
        }

        try {
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            

                $token = \Stripe\Token::create([
                    'card' => [
                        'number'    => $request->cnumber,
                        'exp_month' => $request->card_expiry_month,
                        'exp_year'  => $request->card_expiry_year,
                        'cvc'       => $request->cvv,
                    ],
                ]);

                $customer = \Stripe\Customer::create(array(
                    'email'       => $request['email'],
                    'description' => $request->first_name . " " . $request->last_name,
                    'source'      => $token['id'],
                ));
                $customer_profile         = $customer->id;
                $customer_payment_profile = 0;
            

            info("STRIPE CUSTOMER CREATION RESULT: ".print_r($customer,1));
            info("STRIPE CREATED CUSTOMER ID: ".$customer_profile);
            

            $customer_profile_id = $customer_profile;
            $data['success']                   =true;
            $card_last_four                   = substr($request->cnumber, -4);
            $data['last4']           = $card_last_four;
            $data['customerProfile']         = $customer_profile_id;
            $data['customerProfilePayment'] = "";
            $data['message']                  = "done";
            $data['status']                   = "success";
            return (object) $data;
            

        } catch (\Exception $e) {
            info("stripe charge error " . $e->getMessage());
                $data['success']                   =false;
                $data['status']                   = "error";
                $data['transactionId']            = 0;
                $data['last4']                    = 0;
                $data['customerProfile']        = "";
                $data['customerProfilePayment'] = "";
                $data['message']                    = "ERROR: ".$e->getMessage();
                $data['error']                    = "ERROR: ".$e->getMessage();

                return (object) $data;

        }
  }

  public function chargeProfile(Request $request)
  {
    $request->merge([
            'charge_customer'=>1
        ]);
        return $this->chargeCard($request);
  }

  public function authProfile(Request $request)
  {
    // return $this->authCustomerProfile($request);
  }

  public function refundTransaction(Request $request)
    {
        
        $amount = number_format($request->refund_amount,2,'.','');
            try {
                \Stripe\Stripe::setApiKey($this->getStripeSecretKey);
                    $re = \Stripe\Refund::create(array(
                          "charge" => $request->transaction_id,
                          'amount'   => $amount*100,
                ));
                    $response['refund_id']=$re->id;
                    $response['transaction_id']=$re->id;
                    $response['status']='success';
                    $response['message']="succeeded";
                    $response['type']="refund";
            } catch (\Exception $e) {
                $response['refund_id']=0;
                $response['status']='error';
                $response['message']=$e->getMessage();
                $response['error']=$e->getMessage();
            }
            return (object) $response;
            
            
    }

  public function voidTransaction(Request $request)
  {
    # code...
  }

  public function get_transaction_details(Request $request)
  {
    # code...
  }

  public function getTransactionList(Request $request)
  {
    # code...
  }

  public function test_gateway_connection()
  {
        info("IN STRIPE TEST GATEWAY FUNCTION");
        $mode = config('gateway.mode');
        if ($mode == 'demo') {
            $stripe = array(
                "publishable_key" => "",
                "secret_key"      => "",
            );
        } else {
            $stripe = array(
                "publishable_key" => config('gateway.stripe.publish'),
                "secret_key"      => config('gateway.stripe.secret'),
            );
        }

        try {
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            

            $customers=\Stripe\Customer::all(['limit' => 1]);

            return (object) [
                'status'=>'success',
                'message'=>$customers,
                'code'=>200,
            ];
            

        } catch (\Exception $e) {
            return (object) [
                'status'=>'error',
                'message'=>$e->getMessage(),
                'code'=>422,
            ];

        }

        
      
  }
  public function AuthCard(Request $request)
  {
        info("creating stripe transaction WITH DATE: ".print_r($request->all(),1));
        $mode = config('gateway.mode');
        if ($mode == 'demo') {
            $stripe = array(
                "publishable_key" => "",
                "secret_key"      => "",
            );
        } else {
            $stripe = array(
                "publishable_key" => config('gateway.stripe.publish'),
                "secret_key"      => config('gateway.stripe.secret'),
            );
        }

        try {
             \Stripe\Stripe::setApiKey($stripe['secret_key']);
             $stripe_client = new \Stripe\StripeClient(
                $stripe['secret_key']
              );
            $currency_code = config('site.checkout_currency')==""?"usd":config('site.checkout_currency');
            $payment_method = \Stripe\PaymentMethod::create([
                'type' => 'card',
                'card' => [
                    'number'    => $request->cnumber,
                    'exp_month' => $request->card_expiry_month,
                    'exp_year'  => $request->card_expiry_year,
                    'cvc'       => $request->cvv,
                ],
            ]);
            $payment_intent = \Stripe\PaymentIntent::create([
                'amount' => $request->products_deposit_amount,
                'currency' => $currency_code,
                'payment_method_types' => ['card'],
                'capture_method' => 'manual',
                'payment_method' => $payment_method->id,
              ]);
            $stripe_client->paymentIntents->confirm(
                $payment_intent->id,
                ['payment_method' => $payment_method->id]
              );
            $intent = $stripe_client->paymentIntents->capture(
                $payment_intent->id,
                ['amount_to_capture' => $request->products_deposit_amount]
              );
            $data['success']                   =true;
            $transaction_id                   = $intent->id;
            $card_last_four                   = $payment_method->card->last4;
            $data['transactionId']           = $transaction_id;
            $data['last4']           = $card_last_four;
            $data['customerProfile']         = $payment_method->id;
            $data['customerProfilePayment'] = "";
            $data['message']                  = "done";
            $data['status']                   = "success";
            return (object) $data;
            

        } catch (\Exception $e) {
            info("stripe charge error " . $e->getMessage());
                $data['success']                   =false;
                $data['status']                   = "error";
                $data['transactionId']            = 0;
                $data['last4']                    = 0;
                $data['customerProfile']        = "";
                $data['customerProfilePayment'] = "";
                $data['message']                    = "ERROR: ".$e->getMessage();
                $data['error']                    = "ERROR: ".$e->getMessage();

                return (object) $data;

        }
  } 
  public function captureTransaction(Request $request)
  {
      //
  }
  
  public function chargeServiceFee( Request $request )
  { 

      $amount = number_format($request->rezo_service_fee,2,'.','');
        info("creating stripe transaction WITH DATE: ".print_r($request->all(),1));
        $mode = config('rezo_gateway.mode');
        if ($mode == 'demo') {
            $stripe = array(
                "publishable_key" => "",
                "secret_key"      => "",
            );
        } else {
            $stripe = array(
                "publishable_key" => config('rezo_gateway.stripe.publish'),
                "secret_key"      => config('rezo_gateway.stripe.secret'),
            );
        }

        try {
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            if (!$request->charge_customer ) {

                $token = \Stripe\Token::create([
                    'card' => [
                        'number'    => $request->cnumber,
                        'exp_month' => $request->card_expiry_month,
                        'exp_year'  => $request->card_expiry_year,
                        'cvc'       => $request->cvv,
                    ],
                ]);

                $customer = \Stripe\Customer::create(array(
                    'email'       => $request['email'],
                    'description' => $request->first_name . " " . $request->last_name,
                    'source'      => $token['id'],
                ));
                $customer_profile         = $customer->id;
                $customer_payment_profile = 0;
            } else {
                $customer_profile         = $request->customer_profile;
                $customer_payment_profile = 0;
            }

            $currency_code = config('site.checkout_currency')==""?"usd":config('site.checkout_currency');
            info("STRIPE CREATED CUSTOMER ID: ".$customer_profile);
            $charge = \Stripe\Charge::create(array(

                "description" => config('general.site_name') . " Reservation ($request->first_name $request->last_name) ",
                'amount'      => $amount * 100,
                'currency'    => $currency_code,
                'customer'    => $customer_profile,
            ));

            $customer_profile_id = $customer_profile;
            info("STRIPE RESULT: ".print_r($charge,1));
            
            $data['success']                   =true;
            $transaction_id                   = $charge->id;
            $card_last_four                   = $charge->source->last4;
            $data['transactionId']           = $transaction_id;
            $data['last4']           = $card_last_four;
            $data['customerProfile']         = $customer_profile_id;
            $data['customerProfilePayment'] = "";
            $data['message']                  = "done";
            $data['status']                   = "success";
            return (object) $data;
            

        } catch (\Exception $e) {
            info("stripe charge error " . $e->getMessage());
                $data['success']                   =false;
                $data['status']                   = "error";
                $data['transactionId']            = 0;
                $data['last4']                    = 0;
                $data['customerProfile']        = "";
                $data['customerProfilePayment'] = "";
                $data['message']                    = "ERROR: ".$e->getMessage();
                $data['error']                    = "ERROR: ".$e->getMessage();

                return (object) $data;

        }

    
  }
  public function authCustomerProfile( Request $request )
  { 

        $products_deposit_amount = number_format($request->products_deposit_amount,2,'.','');
        info("creating stripe transaction WITH DATE: ".print_r($request->all(),1));
        $mode = config('gateway.mode');
        if ($mode == 'demo') {
            $stripe = array(
                "publishable_key" => "",
                "secret_key"      => "",
            );
        } else {
            $stripe = array(
                "publishable_key" => config('gateway.stripe.publish'),
                "secret_key"      => config('gateway.stripe.secret'),
            );
        }

        try {
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            $stripe_client = new \Stripe\StripeClient(
                $stripe['secret_key']
              );
            $customer_profile         = $request->customer_profile;
            $currency_code = config('site.checkout_currency')==""?"usd":config('site.checkout_currency');
            info("STRIPE CREATED CUSTOMER ID: ".$customer_profile);
            $payment_intent = \Stripe\PaymentIntent::create([
                'amount' => $products_deposit_amount,
                'currency' => $currency_code,
                'payment_method_types' => ['card'],
                'capture_method' => 'manual',
                'customer'    => $customer_profile
              ]);
              dd($payment_intent);
            $stripe_client->paymentIntents->confirm(
                $payment_intent->id,
                ['payment_method' => $payment_method->id]
              );
            $intent = $stripe_client->paymentIntents->capture(
                $payment_intent->id,
                ['amount_to_capture' => $request->products_deposit_amount]
              );
              dd($intent);
            $data['success']                   =true;
            $transaction_id                   = $intent->id;
            $card_last_four                   = $payment_intent->card->last4;
            $data['transactionId']           = $transaction_id;
            $data['last4']           = $card_last_four;
            $data['customerProfile']         = $customer_profile;
            $data['customerProfilePayment'] = "";
            $data['message']                  = "done";
            $data['status']                   = "success";
            return (object) $data;
        } catch (\Exception $e) {
            info("stripe charge error " . $e->getMessage());
                $data['success']                   =false;
                $data['status']                   = "error";
                $data['transactionId']            = 0;
                $data['last4']                    = 0;
                $data['customerProfile']        = "";
                $data['customerProfilePayment'] = "";
                $data['message']                    = "ERROR: ".$e->getMessage();
                $data['error']                    = "ERROR: ".$e->getMessage();

                return (object) $data;

        }

    
  }
}
