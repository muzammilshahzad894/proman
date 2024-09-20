<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Services\Interfaces\GatewayAdapterInterface;
use GlobalPayments\Api\PaymentMethods\CreditCardData;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\Entities\Address;
use GlobalPayments\Api\Entities\Transaction;
use GlobalPayments\Api\ServiceConfigs\Gateways\PorticoConfig;
use GlobalPayments\Api\Services\ReportingService;
use GlobalPayments\Api\Entities\Customer;
use GlobalPayments\Api\PaymentMethods\RecurringPaymentMethod;

class HeartLandService  implements GatewayAdapterInterface
{ 
  public function chargeCard( Request $request )
  { 

        info("creating heartland transaction WITH DATE: ".print_r($request->all(),1));
        $mode = config('gateway.mode');
        if ($mode == 'demo') {
            $heartland = array(
                "publishable_key" => "",
                "secret_key"      => "",
                "url"      => "https://cert.api2.heartlandportico.com",
            );
        } else {
            $heartland = array(
                "publishable_key" => config('gateway.heartland.public'),
                "secret_key"      => config('gateway.heartland.secret'),
                "url"      => "https://api2.heartlandportico.com",
            );
        }
        $config = new PorticoConfig();
        $config->secretApiKey = $heartland['secret_key'];
        $config->serviceUrl = $heartland['url'];
        ServicesContainer::configureService($config);
        $card = new CreditCardData();
        $card->token = $request->payment_token;
        $address = new Address();
        $address->streetAddress1 = $request->address;
        $address->postalCode = $request->zip;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->country = "United States";
        // Payment Token : supt_T7RBDeKkv22Bbitg4BOBrbv3
        try {
            // $response = $card->verify()
            // ->withAddress($address)
            // ->withRequestMultiUseToken(true)
            // ->execute();
            // $multi_card = new CreditCardData();
            // $multi_card->token = $response->token;
            $multi_charge_response = $card->charge($request->amount)
            ->withCurrency("USD")
            ->withAddress($address)
            ->withRequestMultiUseToken(true)
            ->execute();
            $customer_profile_id = $request->payment_token;
            info("HEARTLAND RESULT: ".print_r($multi_charge_response,1));
            $data['success']                   =true;
            $transaction_id                   = $multi_charge_response->transactionId;
            $data['transactionId']           = $transaction_id;
            $data['last4']           =  @$request->heartLandCardLast4;
            $data['customerProfile']         = $customer_profile_id;
            $data['customerProfilePayment'] = "";
            $data['message']                  = "done";
            $data['status']                   = "success";
            return (object) $data;
        } catch (\Exception $e) {
            info("HEARTLAND charge error " . $e->getMessage());
                $data['success']                   =false;
                $data['status']                   = "error";
                $data['transactionId']            = 0;
                $data['last4']                    = 0;
                $data['customerProfile']        = "";
                $data['customerProfilePayment'] = "";
                $data['message']                    = "ERROR: ".$e->getMessage();
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
            $heartland = array(
                "publishable_key" => "",
                "secret_key"      => "",
                "url"      => "https://cert.api2.heartlandportico.com",
            );
        } else {
            $heartland = array(
                "publishable_key" => config('gateway.heartland.public'),
                "secret_key"      => config('gateway.heartland.secret'),
                "url"      => "https://api2.heartlandportico.com",
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

  public function refundTransaction(Request $request)
    {
            try {
                info("creating heartland REFUND FUNCTION: ".print_r($request->all(),1));
                $mode = config('gateway.mode');
                if ($mode == 'demo') {
                    $heartland = array(
                        "publishable_key" => "",
                        "secret_key"      => "",
                        "url"      => "https://cert.api2.heartlandportico.com",
                    );
                } else {
                    $heartland = array(
                        "publishable_key" => config('gateway.heartland.public'),
                        "secret_key"      => config('gateway.heartland.secret'),
                        "url"      => "https://api2.heartlandportico.com",
                    );
                }
                $config = new PorticoConfig();
                $config->secretApiKey = $heartland['secret_key'];
                $config->serviceUrl = $heartland['url'];
                  ServicesContainer::configureService($config);
                $transcation_response = Transaction::fromId($request->transaction_id)
                    ->refund($request->refund_amount)
                    ->withCurrency("USD")
                    ->execute();
                info("heartland REFUND FUNCTION Response: ".print_r($transcation_response,1));
                if($transcation_response->responseMessage == "Success")
                {
                    $response['transaction_id']=$transcation_response->transactionId;
                    $response['status']='success';
                    $response['message']="succeeded";
                    $response['type']="refund";
                }else{
                    $response['refund_id']=0;
                    $response['status']='error';
                    $response['message']=$transcation_response->responseMessage;
                    $response['error']=$transcation_response->responseMessage;
                }
                 
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
  public function refundPaymentTranscation()
  {
    info("IN HEARTLAND TESTING FUNCTION");
    $mode = config('gateway.mode');
    if ($mode == 'demo') {
        $heartland = array(
            "publishable_key" => "",
            "secret_key"      => "",
            "url"      => "https://cert.api2.heartlandportico.com",
        );
    } else {
        $heartland = array(
            "publishable_key" => config('gateway.heartland.public'),
            "secret_key"      => config('gateway.heartland.secret'),
            "url"      => "https://api2.heartlandportico.com",
        );
    }
    $config = new PorticoConfig();
    $config->secretApiKey = $heartland['secret_key'];
    $config->serviceUrl = $heartland['url'];
      ServicesContainer::configureService($config);
      $card = new CreditCardData();
      $card->token = "Sd2dZU2jF6COOLFQCkJ01018";
      $address = new Address();
      $address->streetAddress1 = "6860 Dallas Pkwy";
      $address->postalCode = "75024";
   
      $response = $card->charge(13.04)
      ->withCurrency("USD")
      ->withAddress($address)
      ->execute();
        dd($response);
  }

  public function test_gateway_connection()
  {
        info("IN HEARTLAND TEST GATEWAY FUNCTION");
        $mode = config('gateway.mode');
        if ($mode == 'demo') {
            $heartland = array(
                "publishable_key" => "",
                "secret_key"      => "",
                "url"      => "https://cert.api2.heartlandportico.com",
            );
        } else {
            $heartland = array(
                "publishable_key" => config('gateway.heartland.public'),
                "secret_key"      => config('gateway.heartland.secret'),
                "url"      => "https://api2.heartlandportico.com",
            );
        }
    
        try {
            
            $config = new PorticoConfig();
            $config->secretApiKey = $heartland['secret_key'];
            $config->serviceUrl = $heartland['url'];
            $response = ServicesContainer::configureService($config);
            $dateFormat = 'Y-m-d\TH:i:s.00\Z';
            $dateMinus10 = new \DateTime();
            $dateMinus10->sub(new \DateInterval('P10D'));
            $dateMinus10Utc = gmdate($dateFormat, $dateMinus10->format('U'));
            $nowUtc = gmdate($dateFormat);
          
            $items = ReportingService::findTransactions()
                ->withStartDate($dateMinus10Utc)
                ->withEndDate($nowUtc)
                ->execute();
                if($items)
                {
                    return (object) [
                        'status'=>'success',
                        'message'=>$items,
                        'code'=>200,
                    ];
                }
                else{
                    return (object) [
                        'status'=>'error',
                        'message'=>"Something Went Wrong.",
                        'code'=>302,
                    ];
                }
            

        } catch (\Exception $e) {
            return (object) [
                'status'=>'error',
                'message'=>$e->getMessage(),
                'code'=>422,
            ];

        }

        
      
  }
  
  public function captureTransaction(Request $request)
  {
      //
  }
  public function chargeServiceFee(Request $request)
  {
      //
  }
  public function authProfile(Request $request)
  {
    //
  }

}
