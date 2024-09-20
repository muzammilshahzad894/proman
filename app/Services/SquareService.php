<?php

namespace App\Services;

use Illuminate\Http\Request;
// use net\authorize\api\contract\v1 as AnetAPI;
// use net\authorize\api\controller as AnetController;
use App\Services\Interfaces\GatewayAdapterInterface;
use Square\SquareClient;
use Square\LocationsApi;
use Square\Models\CreateCustomerRequest;
use Square\Models\CreateCustomerCardRequest;
use Square\Models\Address;
use Square\Models\Country;
use Square\Exceptions\ApiException;
use Square\Http\ApiResponse;
use Square\Models\ListLocationsResponse;
use Square\Environment;

class SquareService implements GatewayAdapterInterface
{
    /**
     * @param Request $request
     * @return object
     */
    public function chargeCard(Request $request)
    {
        info("start charge card function");
        $charge_customer          = $request->charge_customer?1:0;
        $amount                   = $request->amount;
        $msg                      = '';
        $customer_profile         = "";
        $customer_payment_profile = "";
        if($request->customer_profile)
        {
            $profileid = $request->customer_profile;
            $card_id = $request->customer_payment_profile;
        } 
        else{
            $cardholdername  = $request->first_name . ' ' . $request->last_name;
            $expiration_date = $request->card_expiry_year . '-' . $request->card_expiry_month;
            //info($expiration_date);
            $customerInfo =
                [
                'email'          => $request->email,// *
                'FirstName'      => $request->first_name,// *
                'LastName'       => $request->last_name,// *
                'Address'        => $request->address,// *
                'City'           => $request->city,// *
                'State'          => $request->is_international_customer ? $request->province : $request->state,// *
                'Zip'            => $request->is_international_customer ? $request->postal_code : $request->zip,// *
                'Country'        => $request->is_international_customer ? $request->country : "USA",// *
                'PhoneNumber'    => $request->phone,// *
                'CardNumber'     => $request->cnumber,// *
                'ExpirationDate' => $expiration_date,
                'CardCode'       => $request->cvv,// *
                '_nounce'        =>$request->squareup_nonce,
                'amount'         => $amount,
            ];
            info("SQUARE CREATE calling PROFILE FUNCTION: ".__LINE__." DATA: ".print_r($customerInfo,1));

            $squareCreateProfileResponse = $this->createProfile($customerInfo);
            info("SQUARE CREATE calling PROFILE FUNCTION COMPLETE: ".__LINE__." RESPONSE: ".print_r($squareCreateProfileResponse,1));
            

            if ($squareCreateProfileResponse->code == 200 ) {
                $card_id         = $squareCreateProfileResponse->card_id;
                $profileid         = $squareCreateProfileResponse->profileid;
            }else{
                $data = [

                    "status" => "error",
                    "error"  => "Creating customer profile- " .'there is some error with creating customer profile',
                    "message"  => "Creating customer profile- " .'there is some error with creating customer profile',
                ];
                return (object) $data;
            } 
        } 

        info("SQUARE CREATE calling CHARGING PROFILE FUNCTION : ".__LINE__);
        $payment_response = $this->chargePayment($card_id, $profileid,$amount);
        info("payment_response Result" . print_r($payment_response, 1));
        if ($payment_response->success) {
            $data['transactionId']          = $payment_response->transaction_id;
            $data['last4']                  = $payment_response->card_last_four;
            $data['customerProfilePayment']    = $payment_response->card_id;
            $data['success']                   =true;
            $data['customerProfile']         = $profileid;
            $data['message']                  = "done";
            $data['status']                   = "success";
          
        }
         else {
            $data = [
                "status" => "error",
                "error"  => "Charging Customer- " .'there is some error with card and charging profile',
            ];

        }
        info("returning data from chargeCard function: " . print_r($data, 1));
        return (object) $data;
    }

    public function createProfile($customerInfo = '')
    {

        $customer = new CreateCustomerRequest;
        $customer->setIdempotencyKey(uniqid());
        $customer->setGivenName($customerInfo['FirstName'].' '. $customerInfo['LastName']);
        $customer->setFamilyName($customerInfo['FirstName'].' '. $customerInfo['LastName']);
        $customer->setCompanyName(config('general.site_name'));
        
        $customer->setEmailAddress($customerInfo['email']);
        $customer->setAddress(new Address);
        $customer->getAddress()->setAddressLine1($customerInfo['Address']);
        // $customer->getAddress()->setAddressLine2('');
        // $customer->getAddress()->setAddressLine3('');
        $customer->getAddress()->setLocality($customerInfo['City']);
        // $customer->getAddress()->setSublocality('');
        $customer->getAddress()->setAdministrativeDistrictLevel1($customerInfo['State']);
        $customer->getAddress()->setPostalCode($customerInfo['Zip']);
        $customer->getAddress()->setCountry(Country::US);
        $customer->setPhoneNumber($customerInfo['PhoneNumber']);
        $customer->setReferenceId(uniqid());
        $customer->setNote(config('general.site_name'));
        $mode = config('gateway.mode');
         if ($mode == 'demo') {
            info("square dmeo mode");
             $client = new SquareClient([
                        'accessToken' => '',
                        // 'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => "sandbox",
                ]);
            $location_id = config('gateway.squareup.location_id');

        } else {

            info("square PRODUCTION mode");
            $client = new SquareClient([
                        'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => Environment::PRODUCTION,
                ]);
            $location_id = config('gateway.squareup.location_id');

        }


        $api_response  = $client->getCustomersApi()->createCustomer($customer);
        info("SQUARE CREATE PROFILE FUNCTION: ".__LINE__." API RESPONSE: ".print_r($api_response,1));

        if (($api_response != null) && ($api_response->isSuccess())) {
            $customerId = $profileid =  $api_response->getResult()->getCustomer()->getId();
            $nounce = $customerInfo['_nounce'];
            $body_cardNonce = $nounce;
            
            //card saving
            $billing_address = new \Square\Models\Address();
            $billing_address->setAddressLine1($customerInfo['Address']);
            $billing_address->setAddressLine2('');
            $billing_address->setLocality($customerInfo['City']);
            $billing_address->setAdministrativeDistrictLevel1($customerInfo['State']);
            $billing_address->setPostalCode($customerInfo['Zip']);
            $billing_address->setCountry('US');

            $card = new \Square\Models\Card();
            $card->setCardholderName($customerInfo['FirstName']." ".$customerInfo['LastName']);
            $card->setBillingAddress($billing_address);
            $card->setCustomerId($profileid);
            $card->setReferenceId(config('general.site_name'));
            $body = new \Square\Models\CreateCardRequest(
                uniqid(),
                $body_cardNonce,
                $card
            );
            // dd($body);
            info("SQUARE CREATE START creatingCart FUNCTION: ".__LINE__." creatingCart body: ".print_r($body,1));
            $api_response = $client->getCardsApi()->createCard($body);
            info("SQUARE CREATE START creatingCart FUNCTION: ".__LINE__." creatingCart RESPONSE: ".print_r($api_response,1));

            if ($api_response->isSuccess()) {
                $card_id = $api_response->getResult()->getCard()->getId();
                $response =  [
                    'code'       => 200,
                    'card_id' => $card_id,
                    'profileid' => $profileid,
                ];
            } else {
                $errors = $api_response->getErrors();
                $response =  [
                    'code'       => 422,
                    'error_text' => "Error creating customer card"
                ];
            }
            //end card saving

        } else {
            
            $errors = $api_response->getErrors();
            info("SQUARE CREATE PROFILE FUNCTION: ".__LINE__." API ERRORS: ".print_r($errors,1));

            $code = 422;
            $message = "Error creating customer profile";
            /*foreach ($errors as $error) {
                info("SQUARE CREATE PROFILE FUNCTION: ".__LINE__." API ERRORS: ".print_r($error,1));
                //$code = $error->code;
                $message = $error['detail'];
            }*/
            $response =  [
                'code'       => $code,
                'error_text' => $message,
            ];
            

            

        }

        info("SQUARE CREATE PROFILE FUNCTION: ".__LINE__." function RESPONSE: ".print_r($response,1));


        return (object) $response;
        

    }

    /**
     * @param Request $request
     * @return object
     */
    public function createCustomerProfile(Request $request)
    {
            $cardholdername  = $request->first_name . ' ' . $request->last_name;
            $expiration_date = $request->card_expiry_year . '-' . $request->card_expiry_month;
            //info($expiration_date);
            $customerInfo =
                [
                'email'          => $request->email,// *
                'FirstName'      => $request->first_name,// *
                'LastName'       => $request->last_name,// *
                'Address'        => $request->address,// *
                'City'           => $request->city,// *
                'State'          => $request->is_international_customer ? $request->province : $request->state,// *
                'Zip'            => $request->is_international_customer ? $request->postal_code : $request->zip,// *
                'Country'        => $request->is_international_customer ? $request->country : "USA",// *
                'PhoneNumber'    => $request->phone,// *
                'CardNumber'     => $request->cnumber,// *
                'ExpirationDate' => $expiration_date,
                'CardCode'       => $request->cvv,// *
                'amount'         => 0,
                '_nounce'        =>$request->squareup_nonce,

            ];
            $logInfo =
                [
                'email'          => $request->email,
                'FirstName'      => $request->first_name,
                'LastName'       => $request->last_name,
                'Address'        => $request->address,
                'City'           => $request->city,
                'State'          => $request->is_international_customer ? $request->province : $request->state,
                'Zip'            => $request->is_international_customer ? $request->postal_code : $request->zip,
                'Country'        => $request->is_international_customer ? $request->country : "USA",
                'PhoneNumber'    => $request->mobile_number,
                'ExpirationDate' => $expiration_date,
                'amount'         => 0,

            ];
            //info(print_r($customerInfo,1));
            info("creating profile ".print_r($logInfo,1));
            // $customer_profile_response = $this->createauthorizeProfile($customerInfo);
            $customer_profile_response = $this->createProfile($customerInfo);

            info("creating profile Result" . print_r($customer_profile_response, 1));
            if ($customer_profile_response->code != 200) {
                $data = [

                    "status" => "error",
                    "error"  => "Creating customer profile- " .'there is some error with creating customer profile',
                    "message"  => "Creating customer profile- " .'there is some error with creating customer profile',
                ];
                info("payment_response ERROR" . print_r((object) $data, 1));
                return (object) $data;
            }else{

                $customer_profile         = $customer_profile_response->profileid;
                $customer_payment_profile         = $customer_profile_response->card_id;
                $last4 = substr($request->cnumber, -4);
                return (object)[
                    'status' => 'success',
                    'customerPaymentProfileId' => $customer_profile,
                    'customerProfilePayment' => $customer_payment_profile,
                    'customerProfile' => $customer_profile,
                    'customerProfilePayment' => $customer_payment_profile,
                    'last4' => $last4,
                ];
            }

    }


    

    /**
     * @param Request $request
     * @return object
     */
    public function chargeProfile(Request $request)
    {
        return $this->chargeCard($request);
    }

    public function chargeCustomerProfile($profileid, $nounce, $amount,$customerInfo)
    {

        info("SQUARE CREATE START chargeCustomerProfile FUNCTION: ".__LINE__." profileid: $profileid");
        info("SQUARE CREATE START chargeCustomerProfile FUNCTION: ".__LINE__." nounce: $nounce");
        info("SQUARE CREATE START chargeCustomerProfile FUNCTION: ".__LINE__." amount: $amount");
        info("SQUARE CREATE START chargeCustomerProfile FUNCTION: ".__LINE__." customerInfo: ".print_r($customerInfo,1));
        
        $mode = config('gateway.mode');
        //dd(config('gateway.squareup.access_token'));
         if ($mode == 'demo') {

             $client = new SquareClient([
                        'accessToken' => '',
                        // 'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => Environment::SANDBOX,
                ]);
            $location_id = config('gateway.squareup.location_id');

        } else {

           $client = new SquareClient([
                        'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => Environment::PRODUCTION,
                ]);
            $location_id = config('gateway.squareup.location_id');

        }


        // Set the transaction's refId
        $refId           = 'ref' . time();

       
        $customerId = $profileid;
        $body_cardNonce = $nounce;
        
        //card saving
        $billing_address = new \Square\Models\Address();
        $billing_address->setAddressLine1($customerInfo['Address']);
        $billing_address->setAddressLine2('');
        $billing_address->setLocality($customerInfo['City']);
        $billing_address->setAdministrativeDistrictLevel1($customerInfo['State']);
        $billing_address->setPostalCode($customerInfo['Zip']);
        $billing_address->setCountry('US');

        $card = new \Square\Models\Card();
        $card->setCardholderName($customerInfo['FirstName']." ".$customerInfo['LastName']);
        $card->setBillingAddress($billing_address);
        $card->setCustomerId($profileid);
        $card->setReferenceId(config('general.site_name'));

        $body = new \Square\Models\CreateCardRequest(
            uniqid(),
            $body_cardNonce,
            $card
        );

        info("SQUARE CREATE START creatingCart FUNCTION: ".__LINE__." creatingCart body: ".print_r($body,1));
        $api_response = $client->getCardsApi()->createCard($body);
        info("SQUARE CREATE START creatingCart FUNCTION: ".__LINE__." creatingCart RESPONSE: ".print_r($api_response,1));

        if ($api_response->isSuccess()) {
            $result = $api_response->getResult();
        } else {
            $errors = $api_response->getErrors();
        }
        //end card saving
        dd($api_response);

        

        

        
        //$statusCode = $apiResponse->getStatusCode();
        //$headers = $apiResponse->getHeaders();

        if (isset($result)) {

            if ($api_response ->isSuccess()) {

                $amount_money = new \Square\Models\Money();
                $amount_money->setAmount($amount*100);
                $amount_money->setCurrency('USD');

                $app_fee_money = new \Square\Models\Money();
                $app_fee_money->setAmount(0);
                $app_fee_money->setCurrency('USD');

                $body = new \Square\Models\CreatePaymentRequest(
                    $api_response,
                    $refId,
                    $amount_money
                );
                $body->setAppFeeMoney($app_fee_money);
                $body->setAutocomplete(true);
                $body->setCustomerId($profileid);
                $body->setLocationId($location_id);
                $body->setReferenceId(uniqid());
                $body->setNote('Charging payment for the reservation');

                $api_response = $client->getPaymentsApi()->createPayment($body);
                dd($api_response );

                if ($api_response->isSuccess()) {
                    $result = $api_response->getResult()->getPayment();
                    $response = $result;
                } else {
                    $errors = $api_response->getErrors();
                }

            } else {

                return [
                    'code'       => 422,
                    //'error_text'=>$response->getErrors()[0]->getErrorText(),
                    'error_text' => "No response returned",
                    'message'    => "No response returned",
                ];
            }
        } else {
            return [
                'code'       => 422,
                //'error_text'=>$response->getErrors()[0]->getErrorText(),
                'error_text' => "No response returned",
                'message'    => "No response returned",
            ];
        }
        return $response;
    }
    public function refundTransaction(Request $request)
    {
        $mode = config('gateway.mode');
        //dd(config('gateway.squareup.access_token'));
         if ($mode == 'demo') {

             $client = new SquareClient([
                        'accessToken' => '',
                        // 'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => Environment::SANDBOX,
                ]);
            $location_id = config('gateway.squareup.location_id');

        } else {

           $client = new SquareClient([
                        'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => Environment::PRODUCTION,
                ]);
            $location_id = config('gateway.squareup.location_id');
        }
        $amount = $request->refund_amount * 100;
        info("Refund Amount " .$request->refund_amount);
        info("Refund Amount After Conversion ". $amount);
        $amount_money = new \Square\Models\Money();
        $amount_money->setAmount($amount);
        $amount_money->setCurrency('USD');
        $body = new \Square\Models\RefundPaymentRequest(
            uniqid(),
            $amount_money,
            $request->transaction_id
        );
        $api_response = $client->getRefundsApi()->refundPayment($body);
        if ($api_response->isSuccess()) {
            $result = $api_response->getResult();
            $response['transaction_id'] = $api_response->getResult()->getRefund()->getId();
            $response['status'] = 'true';
            return (object) $response;
        } else {
            $errors = $api_response->getErrors();
            $response['status'] = 'error';
            $response['error'] = $errors;
            return (object) $response;
        }

    }


    public function authorizedGetTransactionDetails($transactionId)
    {
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode= config('gateway.mode');
        if ($mode == 'demo') {

            $merchantAuthentication->setName(config('gateway.authorize.demo_login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.demo_key'));

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }

        // Set the transaction's refId
        $refId   = 'ref' . time();
        $request = new AnetAPI\GetTransactionDetailsRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransId($transactionId);
        $controller = new AnetController\GetTransactionDetailsController($request);
        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            return [
                'status'         => $response->getTransaction()->getTransactionStatus(),
                'code'           => 200,
                'transaction_id' => $response->getTransaction()->getTransId(),
                'message'        => 'SUCCESS',
            ];
            echo "SUCCESS: Transaction Status:" . $response->getTransaction()->getTransactionStatus() . "\n";
            echo "                Auth Amount:" . $response->getTransaction()->getAuthAmount() . "\n";
            echo "                   Trans ID:" . $response->getTransaction()->getTransId() . "\n";
        } else {

            $errorMessages = $response->getMessages()->getMessage();

            info("reffund errors " . print_r($errorMessages, 1));
            return [
                'status'         => 'none',
                'code'           => $errorMessages[0]->getCode(),
                'transaction_id' => '0',
                'message'        => $errorMessages[0]->getCode(),
            ];
            echo "ERROR :  Invalid response\n";
            echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
        }
        return $response;
    }


    public function authorizedVoidTransaction($transactionid)
    {
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode                   = config('gateway.mode');

        if ($mode == 'demo') {

            $merchantAuthentication->setName(config('gateway.authorize.demo_login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.demo_key'));

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }

        // Set the transaction's refId
        $refId = 'ref' . time();
        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("voidTransaction");
        $transactionRequestType->setRefTransId($transactionid);
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }

        if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    /*info("Authorized success response ".print_r([
                    'message'        => 'SUCCESS',
                    'code'           => $tresponse->getResponseCode(),
                    'transaction_id' => $tresponse->getTransId(),
                    ],1));*/
                    //info("success void code".$tresponse->getResponseCode());
                    return [
                        'message'        => 'SUCCESS',
                        'code'           => $tresponse->getResponseCode(),
                        'transaction_id' => $tresponse->getTransId(),
                    ];
                    /*echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
                echo " Void transaction SUCCESS AUTH CODE: " . $tresponse->getAuthCode() . "\n";
                echo " Void transaction SUCCESS TRANS ID  : " . $tresponse->getTransId() . "\n";
                echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
                echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";*/
                } else {
                    $message        = 'Transaction Failed';
                    $code           = 0;
                    $transaction_id = 0;
                    if ($tresponse->getErrors() != null) {
                        //echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        $code = $tresponse->getErrors()[0]->getErrorCode();
                        //echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                        $message = $tresponse->getErrors()[0]->getErrorText();
                    }
                    return [
                        'message'        => $message,
                        'code'           => $code,
                        'transaction_id' => 0,
                    ];
                }
            } else {
                $message        = 'Transaction Failed';
                $code           = 0;
                $transaction_id = 0;
                $tresponse      = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    //echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    $code = $tresponse->getErrors()[0]->getErrorCode();
                    //echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                    $message = $tresponse->getErrors()[0]->getErrorText();
                } else {
                    //echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    $code = $response->getMessages()->getMessage()[0]->getCode();
                    //echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                    $message = $response->getMessages()->getMessage()[0]->getText();
                }
                return [
                    'message'        => $message,
                    'code'           => $code,
                    'transaction_id' => 0,
                ];
            }
        } else {
            //echo  "No response returned \n";
            return [
                'message'        => 'No response returned ',
                'code'           => 0,
                'transaction_id' => 0,
            ];
        }
        return $response;
    }


    public function authorizedRefundTransaction($refTransId, $amount, $card_number)
    {
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode                   = config('gateway.mode');

        if ($mode == 'demo') {

            $merchantAuthentication->setName(config('gateway.authorize.demo_login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.demo_key'));

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }

        // Set the transaction's refId
        $refId = 'ref' . time();
        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($card_number);
        $creditCard->setExpirationDate("XXXX");
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);
        //create a transaction
        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("refundTransaction");
        $transactionRequest->setAmount($amount);
        $transactionRequest->setPayment($paymentOne);
        $transactionRequest->setRefTransId($refTransId);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequest);
        $controller = new AnetController\CreateTransactionController($request);
        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }
        if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    /*echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
                    echo "Refund SUCCESS: " . $tresponse->getTransId() . "\n";
                    echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
                    echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";*/
                    $response_array = [
                        "code"           => $tresponse->getResponseCode(),
                        "transaction_id" => $tresponse->getTransId(),
                        "message"        => $tresponse->getMessages()[0]->getDescription(),
                    ];
                } else {
                    //echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {
                        /*echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n"; */
                        $response_array = [
                            "code"           => $tresponse->getErrors()[0]->getErrorCode(),
                            "transaction_id" => "0",
                            "message"        => $tresponse->getErrors()[0]->getErrorText(),
                        ];
                    }
                }
            } else {
                // echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    /*echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";    */
                    $response_array = [
                        "code"           => $tresponse->getErrors()[0]->getErrorCode(),
                        "transaction_id" => "0",
                        "message"        => $tresponse->getErrors()[0]->getErrorText(),
                    ];
                } else {
                    /*echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";*/
                    $response_array = [
                        "code"           => $response->getMessages()->getMessage()[0]->getCode(),
                        "transaction_id" => "0",
                        "message"        => $response->getMessages()->getMessage()[0]->getText(),
                    ];
                }
            }
        } else {

            $response_array = [
                "code"           => 0,
                "transaction_id" => "0",
                "message"        => "No response returned",
            ];
        }
        return $response_array;
    }
    public function getTransactionList(Request $request)
    {
        # code...
    }
    
    public function voidTransaction(Request $request)
    {
      # code...
    }
    public function get_transaction_details(Request $request)
    {
      # code...
    }
    public function test_gateway_connection()
    {



        $mode = config('gateway.mode');
        if ($mode == 'demo') {

             $client = new SquareClient([
                        'accessToken' => '',
                        // 'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => Environment::SANDBOX,
                ]);

        } else {


           $client = new SquareClient([
                        'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => Environment::PRODUCTION,
                ]);
        }

        info(config('gateway.squareup.access_token'));



        try {
            $locationsApi = $client->getLocationsApi();
            $apiResponse = $locationsApi->listLocations();

            if ($apiResponse->isSuccess()) {

                return (object) [
                    'status'=>'success',
                    'message'=>'Connected!',
                    'code'=>200,
                ];
                /*$listLocationsResponse = $apiResponse->getResult();
                $locationsList = $listLocationsResponse->getLocations();
                foreach ($locationsList as $location) {
                print_r($location);
                }*/
            } else {
                info("SQUARE TESTING GATEWAY ERRORS: ".print_r($apiResponse->getErrors(),1));
                return (object) [
                    'status'=>'error',
                    'message'=>"Recieved error while calling Square ",
                    'code'=>422,
                ];
            }
        } catch (ApiException $e) {
            


            return (object) [
                'status'=>'error',
                'message'=>"Recieved error while calling Square: " . $e->getMessage(),
                'code'=>422,
            ];
        } 

        
    }


    public function chargeCreditCard($profileid, $nounce, $amount,$customerInfo)
    {
        /*nessary data*/
        info("SQUARE CREATE START chargeCreditCard FUNCTION: ".__LINE__." profileid: $profileid");
        info("SQUARE CREATE START chargeCreditCard FUNCTION: ".__LINE__." nounce: $nounce");
        info("SQUARE CREATE START chargeCreditCard FUNCTION: ".__LINE__." amount: $amount");
        info("SQUARE CREATE START chargeCreditCard FUNCTION: ".__LINE__." customerInfo: ".print_r($customerInfo,1));
        
        $mode = config('gateway.mode');
        //dd(config('gateway.squareup.access_token'));
         if ($mode == 'demo') {

             $client = new SquareClient([
                        'accessToken' => '',
                        // 'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => Environment::SANDBOX,
                ]);
            $location_id = config('gateway.squareup.location_id');

        } else {

           $client = new SquareClient([
                        'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => Environment::PRODUCTION,
                ]);
            $location_id = config('gateway.squareup.location_id');

        }


        // Set the transaction's refId
        $refId           = 'ref' . time();

       
        $customerId = $profileid;
        $body_cardNonce = $nounce;
        
        //card saving
        $billing_address = new \Square\Models\Address();
        $billing_address->setAddressLine1($customerInfo['Address']);
        $billing_address->setAddressLine2('');
        $billing_address->setLocality($customerInfo['City']);
        $billing_address->setAdministrativeDistrictLevel1($customerInfo['State']);
        $billing_address->setPostalCode($customerInfo['Zip']);
        $billing_address->setCountry('US');

        $card = new \Square\Models\Card();
        $card->setCardholderName($customerInfo['FirstName']." ".$customerInfo['LastName']);
        $card->setBillingAddress($billing_address);
        $card->setCustomerId($profileid);
        $card->setReferenceId(config('general.site_name'));
        /*nessary data*/

        $amount_money = new \Square\Models\Money();
        $amount_money->setAmount(200);
        $amount_money->setCurrency('USD');
        $body = new \Square\Models\CreatePaymentRequest(
            $nounce,
            uniqid(),
            $amount_money
        );
        $body->setAutocomplete(true);
        $body->setLocationId($location_id);
        $body->setReferenceId('123456');
        $api_response = $client->getPaymentsApi()->createPayment($body);


        if ($api_response->isSuccess()) {
            $payment_result = $api_response->getResult();
            //dd($payment_result);
            info("SQUARE chargeCreditCard FUNCTION: ".__LINE__." payment_result: ".print_r($payment_result,1));
            $payment_id = $payment_result->getPayment()->getId() ;
            $body = new \Square\Models\CreateCardRequest(
                uniqid(),
                $payment_id,
                $card
            );

            $card_api_response = $client->getCardsApi()->createCard($body);

            $data['success']                   =true;
            $transaction_id                   = $payment_id;
            $card_last_four                   = $payment_result->getPayment()->getCardDetails()->getCard()->getLast4();
            $data['transactionId']           = $transaction_id;
            $data['last4']                     = $card_last_four;
            $data['customerProfile']         = $profileid;
            $data['customerProfilePayment'] = "";
            $data['message']                  = "done";
            $data['status']                   = "success";
            return (object) $data;


            //dd($api_response);
            if ($card_api_response->isSuccess()) {
                $result = $card_api_response->getResult();
            } else {
                $errors = $card_api_response->getErrors();
                
                $data['customerProfile']         = null;
                $data['customerProfilePayment'] = "card on file not found";
                
                return (object) $data;

            }

            //dd($result);
        } else {
            $errors = $api_response->getErrors();
            $errors = $api_response->getErrors();
            $data['success']                   =true;
            
            $data['transactionId']           = null;
            $data['last4']                     = null;
            $data['customerProfile']         = null;
            $data['customerProfilePayment'] = "";
            $data['message']                  = "Error charging card";
            $data['status']                   = "error";
            return (object) $data;
        }
    }

    public function chargePayment($card_id, $profileid,$amount)
    {
             
        $mode = config('gateway.mode');
        //dd(config('gateway.squareup.access_token'));
         if ($mode == 'demo') {

             $client = new SquareClient([
                        'accessToken' => '',
                        // 'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => Environment::SANDBOX,
                ]);
            $location_id = config('gateway.squareup.location_id');

        } else {

           $client = new SquareClient([
                        'accessToken' => config('gateway.squareup.access_token'),
                        'environment' => Environment::PRODUCTION,
                ]);
            $location_id = config('gateway.squareup.location_id');

        }
        $amount_money = new \Square\Models\Money();
        $amount_money->setAmount($amount*100);
        $amount_money->setCurrency('USD');

        $app_fee_money = new \Square\Models\Money();
        $app_fee_money->setAmount(0);
        $app_fee_money->setCurrency('USD');

        $body = new \Square\Models\CreatePaymentRequest(
            $card_id,
            uniqid(),
            $amount_money
        );
        $body->setAppFeeMoney($app_fee_money);
        $body->setAutocomplete(true);
        $body->setCustomerId($profileid);
        $body->setLocationId('');
        $body->setReferenceId($location_id);
        $body->setNote('Charging payment for the reservation');
        $api_response = $client->getPaymentsApi()->createPayment($body);
        if ($api_response->isSuccess()) {
            $transaction_id  = $api_response->getResult()->getPayment()->getId();
            $card_last_four = $api_response->getResult()->getPayment()->getCardDetails()->getCard()->getLast4();
            $response =  [
                'success'       => true,
                'transaction_id' => $transaction_id,
                'card_last_four' => $card_last_four,
                'card_id' => $card_id,
            ];
        } else {
            $errors = $api_response->getErrors();
            $response =  [
                'success'       => false,
            ];

        }
        return (object) $response;


       
    }
    public function captureTransaction(Request $request)
    {
        //
    }
    public function authProfile(Request $request)
    {
      //
    }
}
