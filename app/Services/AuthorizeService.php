<?php

namespace App\Services;

use App\Services\Interfaces\GatewayAdapterInterface;
use DateTime;
use Illuminate\Http\Request;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeService implements GatewayAdapterInterface
{

    public function chargeCard(Request $request)
    {
        $charge_customer          = 0;
        $amount                   = to_currency($request->amount,0);
        $msg                      = '';
        $customer_profile         = "";
        $customer_payment_profile = "";

        $cardholdername  = $request->first_name . ' ' . $request->last_name;
        $expiration_date = $request->card_expiry_year . '-' . $request->card_expiry_month;
        info($expiration_date);
        $customerInfo = 
            [
            'email'          => $request->email,
            'FirstName'      => $request->first_name,
            'LastName'       => $request->last_name,
            'Address'        => $request->address,
            'City'           => $request->city,
            'State'          => $request->is_international ? $request->province : $request->state,
            'Zip'            => $request->is_international ? $request->postal_code : $request->zip_code,
            'Counrty'        => $request->is_international ? $request->country : "USA",
            'PhoneNumber'    => $request->mobile_number,
            'CardNumber'     => $request->cnumber,
            'ExpirationDate' => $expiration_date,
            'CardCode'       => $request->cvv,
            'amount'         => $amount,

        ];
        
            if ($charge_customer == 0) {
                info("Charging card  & THEN CREATING CUSTOMER FROM TRANSACTION  : " . print_r($customerInfo, 1));
                $payment_response = $this->chargeCreditCard($customerInfo);
                info("payment_response Result" . print_r($payment_response, 1));
                if ($payment_response['code'] != 1) {
                    $data = [
                        "status"  => "error",
                        "error"   => "Error Charging Customer " . @$payment_response['error_text'],
                        "error_text"   => "Error Charging Customer " . @$payment_response['error_text'],
                        "message" => "Error Charging Customer " . @$payment_response['error_text'],
                    ];
                    return (object) $data;
                } else {
                    if (config('site.without_CIM')) {
                        info("Charging card  without CIM  : " . print_r($customerInfo, 1));
                        //charge karny k baad profile ni bnaani
                    }else{

                        //transaction id se profile create karni hai
                        $customerInfo['transaction_id']=$payment_response['transaction_id'];
                    
                        $customer_profile_response = $this->createCustomerProfileFromTransaction($customerInfo);

                        info("CREATING CUSTOMER FROM TRANSACTION Result: " . print_r($customer_profile_response, 1));
                
                        if ($customer_profile_response['code'] != 200) {
                        
                            
                            $data = [
                                "success" => false,
                                "status" => "error",
                                "message"  => "ERROR CREATING CUSTOMER: " . @$customer_profile_response['error_text'],
                                "error"  => "ERROR CREATING CUSTOMER: " . @$customer_profile_response['error_text'],
                                "error_text"  => "ERROR CREATING CUSTOMER: " . @$customer_profile_response['error_text'],
                                
                            ];
                            info("ERROR in creating customer profile from transaction: " . print_r((object) $data, 1));
                            if(@$customer_profile_response['code'] =='E00099'){
                                //this code mean INVALID transaction, so in case of invalid transaction we must return error. 
                                return (object) $data;
                            }
                            //if error code is any other then E00099 then it mean transaction is created successfully but authorized.net could not create customer. so we will allow customer to make reservation and return success. 
                            $customer_profile         = "Error Creating Profile From Transaction";
                            $customer_payment_profile = "Error Creating Profile From Transaction";
                        }else{
                            $customer_profile         = $customer_profile_response['customer_profile'];
                            $customer_payment_profile = $customer_profile_response['customer_payment_profile'];
                        }
                    }

                    

                    $data['transactionId']          = $payment_response['transaction_id'];
                    $data['last4']                  = $payment_response['accountNumber'];
                    $data['customerProfile']        = $customer_profile;
                    $data['customerProfilePayment'] = $customer_payment_profile;
                    $data['status']                 = "success";

                }
                info("returning data " . print_r($data, 1));
        
                return (object) $data;

                /*info("creating profile");
                $customer_profile_response = $this->createauthorizeProfile($customerInfo);
                info("creating profile Result" . print_r($customer_profile_response, 1));
                if ($customer_profile_response['code'] != 200) {
                    $data = [
                        "status"  => "error",
                        "error"   => "Error Creating Customer " . @$customer_profile_response['error_text'],
                        "message" => "Error Creating Customer " . @$customer_profile_response['error_text'],
                    ];
                    info("payment_response ERROR" . print_r((object) $data, 1));
                    return (object) $data;
                }
                $customer_profile         = $customer_profile_response['customer_profile'];
                $customer_payment_profile = $customer_profile_response['customer_payment_profile'];*/
            } else {
                //existing profile se charge karna hai
                $customer_profile         = $request->customer_profile;
                $customer_payment_profile = $request->customer_payment_profile;
                info("charging profile");
                $payment_response = $this->chargeCustomerProfile($customer_profile, $customer_payment_profile, $amount);

                info("payment_response Result" . print_r($payment_response, 1));
                if ($payment_response['code'] != 1) {
                    $data = [
                        "status"  => "error",
                        "error"   => "Error Charging Customer " . @$payment_response['error_text'],
                        "message" => "Error Charging Customer " . @$payment_response['error_text'],
                        "error_text" => "Error Charging Customer " . @$payment_response['error_text'],
                        
                    ];
                    return (object) $data;
                } else {

                    $data['transactionId']          = $payment_response['transaction_id'];
                    $data['last4']                  = $payment_response['accountNumber'];
                    $data['customerProfile']        = $customer_profile;
                    $data['customerProfilePayment'] = $customer_payment_profile;
                    $data['status']                 = "success";

                }

                info("returning data " . print_r($data, 1));
                return (object) $data;
            }
            
        
        
        
        
        
        
    }
    public function chargeProfile(Request $request)
    {
        $charge_customer          = 0;
        $amount                   = to_currency($request->amount,0);
        $msg                      = '';
        $customer_profile         = "";
        $customer_payment_profile = "";

        $customer_profile         = $request->customer_profile;
        $customer_payment_profile = $request->customer_payment_profile;

        info("charging profile");
        $payment_response = $this->chargeCustomerProfile($customer_profile, $customer_payment_profile, $amount);
        info("payment_response Result" . print_r($payment_response, 1));
        if ($payment_response['code'] != 1) {
            $data = [
                "status" => "error",
                "error"  => "Error Charging Customer: " . $payment_response['error_text'],
                "message"  => "Error Charging Customer: " . $payment_response['error_text'],
            ];
            return (object) $data;
        } else {
            $data['transactionId']          = $payment_response['transaction_id'];
            $data['last4']                  = $payment_response['accountNumber'];
            $data['customerProfile']        = $customer_profile;
            $data['customerProfilePayment'] = $customer_payment_profile;
            $data['status']                 = "success";

        }
        info("returning data " . print_r($data, 1));
        return (object) $data;
    }
    public function authProfile(Request $request)
    {
        $amount                   = to_currency($request->products_deposit_amount,0);
        $msg                      = '';
        $customer_profile         = "";
        $customer_payment_profile = "";

        $customer_profile         = $request->customer_profile;
        $customer_payment_profile = $request->customer_payment_profile;
        info("auth profile");
        $payment_response = $this->authorizeCustomerProfile($customer_profile, $customer_payment_profile, $amount);
        info("payment_response Result" . print_r($payment_response, 1));
        if ($payment_response['code'] != 1) {
            $data = [
                "status" => "error",
                "error"  => "Error Charging Customer: " . $payment_response['error_text'],
                "message"  => "Error Charging Customer: " . $payment_response['error_text'],
            ];
            return (object) $data;
        } else {
            $data['transactionId']          = $payment_response['transaction_id'];
            $data['last4']                  = $payment_response['accountNumber'];
            $data['customerProfile']        = $customer_profile;
            $data['customerProfilePayment'] = $customer_payment_profile;
            $data['status']                 = "success";

        }
        info("returning data " . print_r($data, 1));
        return (object) $data;
    }

    public function createCustomerProfile(Request $request)
    {
        info("start function");
        $charge_customer          = 0;
        $amount                   = 0;
        $msg                      = '';
        $customer_profile         = "";
        $customer_payment_profile = "";

        $cardholdername  = $request->first_name . ' ' . $request->last_name;
        $expiration_date = $request->card_expiry_year . '-' . $request->card_expiry_month;
        info($expiration_date);
        $customerInfo =
            [
            'email'          => $request->email,
            'FirstName'      => $request->first_name,
            'LastName'       => $request->last_name,
            'Address'        => $request->address,
            'City'           => $request->city,
            'State'          => $request->is_international ? $request->province : $request->state,
            'Zip'            => $request->is_international ? $request->postal_code : $request->zip_code,
            'Counrty'        => $request->is_international ? $request->country : "USA",
            'PhoneNumber'    => $request->mobile_number,
            'CardNumber'     => $request->cnumber,
            'ExpirationDate' => $expiration_date,
            'CardCode'       => $request->cvv,

        ];
        info(print_r($customerInfo,1));
        info("creating profile");
        $customer_profile_response = $this->createauthorizeProfile($customerInfo);
        info("creating profile Result" . print_r($customer_profile_response, 1));
        if ($customer_profile_response['code'] != 200) {
            $data = [
                "status" => "error",
                "error"  => "Creating Customer- " . $customer_profile_response['error_text'],
                "message"  => "Creating Customer- " . $customer_profile_response['error_text'],
            ];
            info("payment_response ERROR" . print_r((object) $data, 1));
            return (object) $data;
        }
        $customer_profile               = $customer_profile_response['customer_profile'];
        $customer_payment_profile       = $customer_profile_response['customer_payment_profile'];
        $data['customerProfile']        = $customer_profile;
        $data['customerProfilePayment'] = $customer_payment_profile;
        $data['last4']                  = "XXXX" . substr($request->cnumber, -4);
        $data['status']                 = "success";

        info("returning data " . print_r($data, 1));
        return (object) $data;
    }

    public function createauthorizeProfile($customerInfo = '')
    {
        //define("AUTHORIZENET_LOG_FILE", "phplog");

        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();

        $mode = config('gateway.mode');
        if ($mode == 'demo') {

            
            

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create a Customer Profile Request
        //  1. (Optionally) create a Payment Profile
        //  2. (Optionally) create a Shipping Profile
        //  3. Create a Customer Profile (or specify an existing profile)
        //  4. Submit a CreateCustomerProfile Request
        //  5. Validate Profile ID returned

        // Set credit card information for payment profile
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($customerInfo['CardNumber']);
        $creditCard->setExpirationDate($customerInfo['ExpirationDate']);
        $creditCard->setCardCode($customerInfo['CardCode']);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        
            // Create the Bill To info for new payment type
            $billTo = new AnetAPI\CustomerAddressType();
            $billTo->setFirstName($customerInfo['FirstName']);
            $billTo->setLastName($customerInfo['LastName']);

            $billTo->setAddress($customerInfo['Address']);
            $billTo->setCity($customerInfo['City']);
            $billTo->setState($customerInfo['State']);
            $billTo->setZip($customerInfo['Zip']);
            $billTo->setCountry($customerInfo['Counrty']);
            //$billTo->setCountry("USA");
            $billTo->setPhoneNumber($customerInfo['PhoneNumber']);
            // $billTo->setfaxNumber("999-999-9999");
        
        

        // Create a customer shipping address
        /*  $customerShippingAddress = new AnetAPI\CustomerAddressType();
        $customerShippingAddress->setFirstName("James");
        $customerShippingAddress->setLastName("White");
        $customerShippingAddress->setCompany("Addresses R Us");
        $customerShippingAddress->setAddress(rand() . " North Spring Street");
        $customerShippingAddress->setCity("Toms River");
        $customerShippingAddress->setState("NJ");
        $customerShippingAddress->setZip("08753");
        $customerShippingAddress->setCountry("USA");
        $customerShippingAddress->setPhoneNumber("888-888-8888");
        $customerShippingAddress->setFaxNumber("999-999-9999");*/

        // Create an array of any shipping addresses
        // $shippingProfiles[] = $customerShippingAddress;

        // Create a new CustomerPaymentProfile object
        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType('individual');
        
        $paymentProfile->setBillTo($billTo);
        
        $paymentProfile->setPayment($paymentCreditCard);
        $paymentProfile->setDefaultpaymentProfile(true);
        $paymentProfiles[] = $paymentProfile;

        // Create a new CustomerProfileType and add the payment profile object
        $customerProfile = new AnetAPI\CustomerProfileType();
        $customerProfile->setDescription(config('general.site_name') . " Reservation");
        $customerProfile->setMerchantCustomerId("M_" . time());
        $customerProfile->setEmail($customerInfo['email']);
        $customerProfile->setpaymentProfiles($paymentProfiles);
        // $customerProfile->setShipToList($shippingProfiles);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setValidationMode('liveMode');

        $request->setProfile($customerProfile);

        // Create the controller and get the response
        $controller = new AnetController\CreateCustomerProfileController($request);

        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {

            $paymentProfiles = $response->getCustomerPaymentProfileIdList();

            //echo "Succesfully created customer profile : " . $response->getCustomerProfileId() . "\n";
            //echo "SUCCESS: PAYMENT PROFILE ID : " . $paymentProfiles[0] . "\n";
            return [
                'code'                     => 200,
                'customer_profile'         => $response->getCustomerProfileId(),
                'customer_payment_profile' => $paymentProfiles[0],
            ];

            $CustomerProfile                       = new AnetCustomerProfile;
            $CustomerProfile->reservation_id       = $customerInfo['reservationId'];
            $CustomerProfile->first_name           = $customerInfo['FirstName'];
            $CustomerProfile->last_name            = $customerInfo['LastName'];
            $CustomerProfile->email                = $customerInfo['email'];
            $CustomerProfile->amount               = $customerInfo['amount'];
            $CustomerProfile->getcustomerprofileid = $response->getCustomerProfileId();
            $CustomerProfile->paymentprofiles      = $paymentProfiles[0];

            $CustomerProfile->save();
            Session::forget('success');
            Session::flash('success', 'Credit card info saved.');

            return true;

        } else {
            // echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            // echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";

            $error = "Credit Card Error :" . "  " . $errorMessages[0]->getText() . "\n";
            return [
                'code'       => $errorMessages[0]->getCode(),
                'error_text' => $error,
                'message' => $error,
            ];
            //Log::info($error);

            $this->authErrors = $error;
            return false;

        }
        return $response;

    }

    public function chargeCustomerProfile($profileid, $paymentprofileid, $amount)
    {
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();

        $mode = config('gateway.mode');
        if ($mode == 'demo') {

            
            

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }
        // Set the transaction's refId
        $refId           = 'ref' . time();
        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($profileid);
        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($paymentprofileid);
        $profileToCharge->setPaymentProfile($paymentProfile);
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setProfile($profileToCharge);
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
            if ($response->getMessages()->getResultCode() == 'Ok') {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {

                    /*echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
                    echo  "Charge Customer Profile APPROVED  :" . "\n";
                    echo " Charge Customer Profile AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                    echo " Charge Customer Profile TRANS ID  : " . $tresponse->getTransId() . "\n";
                    echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
                    echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";*/
                    //info('customer response'.print_r($tresponse,1));
                    $declined_message="";
                    if ($tresponse->getResponseCode()!=1) {
                        $declined_message="Transaction has been declined";
                    }

                    return [
                        'code'           => $tresponse->getResponseCode(),
                        'transaction_id' => $tresponse->getTransId(),
                        'accountNumber'  => $tresponse->getAccountNumber(),
                        'error_text'  => $declined_message, /*will only show when status code is 4 and trransaction failed by fraud detection*/
                        //'card'=>$tresponse->accountType,
                        'message'        => $tresponse->getMessages()[0]->getDescription(),
                    ];
                } else {
                    //echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {
                        /*echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n"; */
                        return [
                            'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                            'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                            'message'    => "Transaction Failed",
                        ];

                    }
                }
            } else {
                //echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    /* echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";    */
                    return [
                        'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                        'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                        'message'    => "Transaction Failed",
                    ];
                } else {
                    /*echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                     */
                    return [
                        'code'       => $response->getMessages()->getMessage()[0]->getCode(),
                        'error_text' => $response->getMessages()->getMessage()[0]->getText(),
                        'message'    => "Transaction Failed",
                    ];
                }
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
    public function authorizeCustomerProfile($profileid, $paymentprofileid, $amount)
    {
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();

        $mode = config('gateway.mode');
        if ($mode == 'demo') {

            
            

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }
        // Set the transaction's refId
        $refId           = 'ref' . time();
        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($profileid);
        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($paymentprofileid);
        $profileToCharge->setPaymentProfile($paymentProfile);
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authOnlyTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setProfile($profileToCharge);
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
            if ($response->getMessages()->getResultCode() == 'Ok') {
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $declined_message="";
                    if ($tresponse->getResponseCode()!=1) {
                        $declined_message="Transaction has been declined";
                    }
                    return [
                        'code'           => $tresponse->getResponseCode(),
                        'transaction_id' => $tresponse->getTransId(),
                        'accountNumber'  => $tresponse->getAccountNumber(),
                        'error_text'  => $declined_message, /*will only show when status code is 4 and trransaction failed by fraud detection*/
                        'message'        => $tresponse->getMessages()[0]->getDescription(),
                    ];
                } else {
                    //echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {  
                        return [
                            'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                            'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                            'message'    => "Transaction Failed",
                        ];
                    }
                }
            } else {
                //echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    return [
                        'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                        'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                        'message'    => "Transaction Failed",
                    ];
                } else {
                    return [
                        'code'       => $response->getMessages()->getMessage()[0]->getCode(),
                        'error_text' => $response->getMessages()->getMessage()[0]->getText(),
                        'message'    => "Transaction Failed",
                    ];
                }
            }
        } else {
            return [
                'code'       => 422,
                'error_text' => "No response returned",
                'message'    => "No response returned",
            ];
        }
        return $response;
    }
    public function authorizedGetTransactionList($request)
    {
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode                   = config('gateway.mode');
        if ($mode == 'demo') {

            
            

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }

        // Set the request's refId
        $refId = 'ref' . time();
        //Setting a valid batch Id for the Merchant
        $batchId = $request->batchId;
        $request = new AnetAPI\GetTransactionListRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setBatchId($batchId);
        $controller = new AnetController\GetTransactionListController($request);
        //Retrieving transaction list for the given Batch Id
        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            ///echo "SUCCESS: Get Transaction List for BatchID : " . $batchId . "\n\n";
            if ($response->getTransactions() == null) {
                $custom_response['status'] = 'error';
                $custom_response['error']  = "Response : SUCCESS: Get Transaction List for BatchID : " . $batchId;
                return (object) $custom_response;
            }
            $transactions_data = [];
            //Displaying the details of each transaction in the list
            foreach ($response->getTransactions() as $transaction) {
                /*echo "      ->Transaction Id    : " . $transaction->getTransId() . "\n";
                echo "      Submitted on (Local)    : " . date_format($transaction->getSubmitTimeLocal(), 'Y-m-d H:i:s') . "\n";
                echo "      Status          : " . $transaction->getTransactionStatus() . "\n";
                echo "      Settle amount       : " . number_format($transaction->getSettleAmount(), 2, '.', '') . "\n";*/
                $item['id']          = $transaction->getTransId();
                $item['date']        = date_format($transaction->getSubmitTimeLocal(), 'Y-m-d H:i:s');
                $item['status']      = $transaction->getTransactionStatus();
                $item['amount']      = number_format($transaction->getSettleAmount(), 2, '.', '');
                $transactions_data[] = $item;
            }
            $custom_response['status']       = 'success';
            $custom_response['transactions'] = $transactions_data;
        } else {
            //echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            //echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
            $custom_response['status'] = 'error';
            $custom_response['error']  = "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText();
            $custom_response['error_text']  = "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText();
        }
        return (object) $custom_response;
    }

    public function getBatchIds($request)
    {
        $firstSettlementDate = new DateTime('2018-09-03 00:00:00');
        //$firstSettlementDate=$firstSettlementDate->format('Y-m-d H:i:s');

        $lastSettlementDate = new DateTime('2018-09-03 00:00:00');
        //$lastSettlementDate=$lastSettlementDate->format('Y-m-d H:i:s');

        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode                   = config('gateway.mode');
        if ($mode == 'demo') {

            
            

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }

        // Set the transaction's refId
        $refId   = 'ref' . time();
        $request = new AnetAPI\GetSettledBatchListRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setIncludeStatistics(false);

        // Both the first and last dates must be in the same time zone
        // The time between first and last dates, inclusively, cannot exceed 31 days.
        $request->setFirstSettlementDate($firstSettlementDate);
        $request->setLastSettlementDate($lastSettlementDate);
        $controller = new AnetController\GetSettledBatchListController($request);
        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $batchIds = [];
            foreach ($response->getBatchList() as $batch) {
                $batchIds[] = $batch->getBatchId();
                /*echo "\n\n";
                echo "Batch ID: " . $batch->getBatchId() . "\n";
                echo "Batch settled on (UTC): " . $batch->getSettlementTimeUTC()->format('r') . "\n";
                echo "Batch settled on (Local): " . $batch->getSettlementTimeLocal()->format('D, d M Y H:i:s') . "\n";
                echo "Batch settlement state: " . $batch->getSettlementState() . "\n";
                echo "Batch market type: " . $batch->getMarketType() . "\n";
                echo "Batch product: " . $batch->getProduct() . "\n";*/
                foreach ($batch->getStatistics() as $statistics) {

                    /*echo "Account type: ".$statistics->getAccountType()."\n";
                echo "Total charge amount: ".$statistics->getChargeAmount()."\n";
                echo "Charge count: ".$statistics->getChargeCount()."\n";
                echo "Refund amount: ".$statistics->getRefundAmount()."\n";
                echo "Refund count: ".$statistics->getRefundCount()."\n";
                echo "Void count: ".$statistics->getVoidCount()."\n";
                echo "Decline count: ".$statistics->getDeclineCount()."\n";
                echo "Error amount: ".$statistics->getErrorCount()."\n";*/
                }
            }
            $custom_response['status']   = 'success';
            $custom_response['batchIds'] = $batchIds;
        } else {
            //echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            //echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
            $custom_response['status'] = 'error';
            $custom_response['error']  = $errorMessages;
        }
        return (object) $custom_response;
    }

    public function getTransactionList(Request $request)
    {
        $response = $this->getBatchIds($request);
        if ($response->status == 'success') {
            if (!empty($response->batchIds)) {
                $custom_response['status']   = 'success';
                $custom_response['batchIds'] = $response->batchIds;
                foreach ($response->batchIds as $batchId) {
                    $request->merge(['batchId' => $batchId]);
                    $transactoin_list_response       = $this->authorizedGetTransactionList($request);
                    $custom_response['transactions'] = $transactoin_list_response->transactions;
                }

            } else {
                $custom_response['status'] = 'error';
                $custom_response['error']  = 'no transactions found in between these dates';
            }

        } else {
            $custom_response['status'] = 'error';
            $custom_response['error']  = $response->error;
        }

        return (object) $custom_response;
    }
    public function authorizedRefundTransaction($refTransId, $amount, $card_number)
    {
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode                   = config('gateway.mode');
        if ($mode == 'demo') {

            
            

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
    public function authorizedVoidTransaction($transactionid)
    {
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode                   = config('gateway.mode');
        if ($mode == 'demo') {

            
            

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
    public function authorizedGetTransactionDetails($transactionId)
    {
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode                   = config('gateway.mode');
        if ($mode == 'demo') {

            
            

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

    public function get_transaction_details(Request $request)
    {
        $result_details = $this->authorizedGetTransactionDetails($request->transaction_id);
        if ($result_details['code'] != 200) {
            //return response([$result_details['message']],422);
            $custom_response['status'] = 'error';
            $custom_response['transaction_status'] = 'error';
            $custom_response['error']  = $result_details['message'];
        } else {

            $custom_response['status'] = 'success';
            $custom_response['error']  = "";

            if ($result_details['status'] != 'settledSuccessfully') {
                $custom_response['transaction_status'] = 'not_settled';
            } else {
                $custom_response['transaction_status'] = 'settled';
            }

        }
        return (object) $custom_response;
    }

    public function refundTransaction(Request $request)
    {
        $response = $this->authorizedRefundTransaction($request->transaction_id, $request->refund_amount, $request->card);
                if ($response['code'] != 1) {
                    $custom_response['status'] = 'error';
                    $custom_response['error']  = $response['message'];
                } else {
                    $custom_response['status']         = 'success';
                    $custom_response['type']           = 'refund';
                    $custom_response['transaction_id'] = $response['transaction_id'];

                }
        return (object) $custom_response;
    }

    public function voidTransaction(Request $request)
    {
        $result_void = $this->authorizedVoidTransaction($request->transaction_id);
        if ($result_void['code'] != 1) {
            $custom_response['status'] = 'error';
            $custom_response['error']  = $result_void['message'];
        } else {
            $custom_response['status']         = 'success';
            $custom_response['type']           = 'void';
            $custom_response['transaction_id'] = $result_void['transaction_id'];

        }
        return (object) $custom_response;
    }

    public function chargeCreditCard($customerInfo)
    {
        //yeh funciton card se charge karta hai, profile se nahi

        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode                   = config('gateway.mode');
        if ($mode == 'demo') {

            
            

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }
        
        
        // Set the transaction's refId
        $refId = 'ref' . time();
        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($customerInfo['CardNumber']);
        $creditCard->setExpirationDate($customerInfo['ExpirationDate']);
        $creditCard->setCardCode($customerInfo['CardCode']);
        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);
        // Create order information
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber("10101");
        $order->setDescription("Rental Fee");
        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($customerInfo['FirstName']);
        $customerAddress->setLastName($customerInfo['LastName']);
        $customerAddress->setCompany("");
        $customerAddress->setAddress($customerInfo['Address']);
        $customerAddress->setCity($customerInfo['City']);
        $customerAddress->setState($customerInfo['State']);
        $customerAddress->setZip($customerInfo['Zip']);
        $customerAddress->setCountry($customerInfo['Counrty']);
        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId("99999456654");
        $customerData->setEmail($customerInfo['email']);
        // Add values for transaction settings
        $duplicateWindowSetting = new AnetAPI\SettingType();
        $duplicateWindowSetting->setSettingName("duplicateWindow");
        $duplicateWindowSetting->setSettingValue("60");
        // Add some merchant defined fields. These fields won't be stored with the transaction,
        // but will be echoed back in the response.
        $merchantDefinedField1 = new AnetAPI\UserFieldType();
        $merchantDefinedField1->setName("customerLoyaltyNum");
        $merchantDefinedField1->setValue("1128836273");
        $merchantDefinedField2 = new AnetAPI\UserFieldType();
        $merchantDefinedField2->setName("favoriteColor");
        $merchantDefinedField2->setValue("blue");
        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($customerInfo['amount']);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
        $transactionRequestType->addToUserFields($merchantDefinedField1);
        $transactionRequestType->addToUserFields($merchantDefinedField2);
        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }
        
        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();
            
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    /*echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
                    echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
                    echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
                    echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
                    echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";*/

                     return [
                        'code'           => $tresponse->getResponseCode(),
                        'transaction_id' => $tresponse->getTransId(),
                        'accountNumber'  => $tresponse->getAccountNumber(),
                        //'card'=>$tresponse->accountType,
                        'message'        => $tresponse->getMessages()[0]->getDescription(),
                    ];


                } else {
                    //echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {
                        //echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        //echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";

                        return [
                            'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                            'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                            'message'    => "Transaction Failed",
                        ];
                    }
                }
                // Or, print errors if the API request wasn't successful
            } else {
                //echo "Transaction Failed \n";
                //$tresponse = $response->getTransactionResponse();
            
                /*if ($tresponse != null && $tresponse->getErrors() != null) {
                    echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                } else {
                    echo " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    echo " Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                }*/

                //echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    /* echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";    */
                    return [
                        'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                        'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                        'message'    => "Transaction Failed",
                    ];
                } else {
                    /*echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                     */
                    return [
                        'code'       => $response->getMessages()->getMessage()[0]->getCode(),
                        'error_text' => $response->getMessages()->getMessage()[0]->getText(),
                        'message'    => "Transaction Failed",
                    ];
                }
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

    public function test_gateway_connection()
    {
        return $this->getMerchantDetails();
    }

    public function getMerchantDetails()
    {
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode                   = config('gateway.mode');
        if ($mode == 'demo') {

            
            

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }
        
        // Set the transaction's refId
        $refId = 'ref' . time();

        $request = new AnetAPI\GetMerchantDetailsRequest();
        $request->setMerchantAuthentication($merchantAuthentication);

        $controller = new AnetController\GetMerchantDetailsController($request);

        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
        {
            $message="Merchant Name:" . $response->getMerchantName() . " Gateway Id:" . $response->getGatewayId(). " ";

            

          foreach ($response->getProcessors() as $processor) {
            $message.= "Name: " . $processor->getName() . " "; 
          }

          foreach ($response->getCurrencies() as $currency) {
            $message.= "Currency : " . $currency . " "; 
          }

          $response_array= [
                        'code'       => 200,
                        'status'       => "success",
                        'message'    => $message,
                        'error_text' => null,
                    ];
         }
        else
        {
            
            $errorMessages = $response->getMessages()->getMessage();
            $message= "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . " ";

            $response_array= [
                        'code'       => $errorMessages[0]->getCode(),
                        'status'       => "error",
                        'message'    => $message,
                        'error_text' => $message,
                    ];
        }

        return (object) $response_array;
      }

    function createAnAcceptPaymentTransaction($customerInfo)
    {
        //yeh hosted form bna k nonce k through payment karwaye ga
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode                   = config('gateway.mode');
        if ($mode == 'demo') {

            
            

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }
        
        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create the payment object for a payment nonce
        $opaqueData = new AnetAPI\OpaqueDataType();
        $opaqueData->setDataDescriptor($customerInfo['dataDescriptor']);
        $opaqueData->setDataValue($customerInfo['dataValue']);


        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setOpaqueData($opaqueData);

        // Create order information
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber("10101");
        $order->setDescription(config('general.site_name') . " Reservation");
        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($customerInfo['FirstName']);
        $customerAddress->setLastName($customerInfo['LastName']);
        $customerAddress->setCompany("");
        $customerAddress->setAddress($customerInfo['Address']);
        $customerAddress->setCity($customerInfo['City']);
        $customerAddress->setState($customerInfo['State']);
        $customerAddress->setZip($customerInfo['Zip']);
        $customerAddress->setCountry($customerInfo['Country']);

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId("99999456654");
        $customerData->setEmail($customerInfo['email']);



        // Add values for transaction settings
        $duplicateWindowSetting = new AnetAPI\SettingType();
        $duplicateWindowSetting->setSettingName("duplicateWindow");
        $duplicateWindowSetting->setSettingValue("60");

        // Add some merchant defined fields. These fields won't be stored with the transaction,
        // but will be echoed back in the response.
        $merchantDefinedField1 = new AnetAPI\UserFieldType();
        $merchantDefinedField1->setName("customerLoyaltyNum");
        $merchantDefinedField1->setValue("1128836273");

        $merchantDefinedField2 = new AnetAPI\UserFieldType();
        $merchantDefinedField2->setName("favoriteColor");
        $merchantDefinedField2->setValue("blue");

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction"); 
        $transactionRequestType->setAmount($customerInfo['amount']);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
        $transactionRequestType->addToUserFields($merchantDefinedField1);
        $transactionRequestType->addToUserFields($merchantDefinedField2);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        
        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }
        

        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();
            
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    /*echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
                    echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
                    echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
                    echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
                    echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";*/
                    $declined_message="";
                    if ($tresponse->getResponseCode()!=1) {
                        $declined_message="Transaction has been declined";
                    }
                    return [
                        'code'           => $tresponse->getResponseCode(),
                        'transaction_id' => $tresponse->getTransId(),
                        'accountNumber'  => $tresponse->getAccountNumber(),
                        //'card'=>$tresponse->accountType,
                        'error_text'  => $declined_message, /*will only show when status code is 4 and trransaction failed by fraud detection*/
                        'message'        => $tresponse->getMessages()[0]->getDescription(),
                    ];
                } else {
                    //echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {
                        /*echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";*/
                        return [
                            'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                            'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                            'message'    => "Transaction Failed",
                        ];
                    }
                }
                // Or, print errors if the API request wasn't successful
            } else {
                echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
            
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    /*echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";*/
                    return [
                        'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                        'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                        'message'    => "Transaction Failed",
                    ];
                } else {
                    /*echo " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    echo " Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";*/
                    return [
                        'code'       => $response->getMessages()->getMessage()[0]->getCode(),
                        'error_text' => $response->getMessages()->getMessage()[0]->getText(),
                        'message'    => "Transaction Failed",
                    ];
                }
            }      
        } else {
            //echo  "No response returned \n";
            return [
                'code'       => 422,
                //'error_text'=>$response->getErrors()[0]->getErrorText(),
                'error_text' => "No response returned",
                'message'    => "No response returned",
            ];
        }

        //return $response;
    }  
    function createCustomerProfileFromTransaction($customerInfo)
    {
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $transId=$customerInfo['transaction_id'];
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode                   = config('gateway.mode');
        if ($mode == 'demo') {

            
            

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }
        
        // Set the transaction's refId
        $refId = 'ref' . time();

        $customerProfile = new AnetAPI\CustomerProfileBaseType();
        $customerProfile->setMerchantCustomerId("M_" . time());
        $customerProfile->setEmail($customerInfo['email']);
        $customerProfile->setDescription(config('general.site_name') . " Reservation");
          
        $request = new AnetAPI\CreateCustomerProfileFromTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransId($transId);

        // You can either specify the customer information in form of customerProfileBaseType object
        $request->setCustomer($customerProfile);
        //  OR   
        // You can just provide the customer Profile ID
            //$request->setCustomerProfileId("123343");

        $controller = new AnetController\CreateCustomerProfileFromTransactionController($request);

        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
            //echo "SUCCESS: PROFILE ID : " . $response->getCustomerProfileId() . "\n";
            $paymentProfiles = $response->getCustomerPaymentProfileIdList();

            return [
                'code'                     => 200,
                'customer_profile'         => $response->getCustomerProfileId(),
                'customer_payment_profile' => $paymentProfiles[0],
            ];
        } else {
            //echo "ERROR :  Invalid response\n";
            //$errorMessages = $response->getMessages()->getMessage();
            //echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";

            $errorMessages = $response->getMessages()->getMessage();
            $error = "Credit Card Error :" . "  " . $errorMessages[0]->getText() . "\n";
            return [
                'code'       => $errorMessages[0]->getCode(),
                'error_text' => $error,
            ];
        }
        return $response;
    }
    public function authorizeCreditCard($customerInfo,$products_deposit_amount)
    {
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode = config('gateway.mode');
        if ($mode == 'demo') {

            
            

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }
        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($customerInfo['CardNumber']);
        $creditCard->setExpirationDate($customerInfo['ExpirationDate']);
        $creditCard->setCardCode($customerInfo['CardCode']);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create order information
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber("10101");
        $order->setDescription(config('general.site_name') . " Reservation");

        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($customerInfo['FirstName']);
        $customerAddress->setLastName($customerInfo['LastName']);
        $customerAddress->setAddress($customerInfo['Address']);
        $customerAddress->setCity($customerInfo['City']);
        $customerAddress->setEmail($customerInfo['email']);
        $customerAddress->setState($customerInfo['State']);
        $customerAddress->setZip($customerInfo['Zip']);
        $customerAddress->setCountry($customerInfo['Counrty']);
        $customerAddress->setPhoneNumber($customerInfo['PhoneNumber']);

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId("M_" . time());
        $customerData->setEmail($customerInfo['email']);

        // Add values for transaction settings
        $duplicateWindowSetting = new AnetAPI\SettingType();
        $duplicateWindowSetting->setSettingName("duplicateWindow");
        $duplicateWindowSetting->setSettingValue("60");

        // Add some merchant defined fields. These fields won't be stored with the transaction,
        // but will be echoed back in the response.
        $merchantDefinedField1 = new AnetAPI\UserFieldType();
        $merchantDefinedField1->setName("customerLoyaltyNum");
        $merchantDefinedField1->setValue("1128836273");

        $merchantDefinedField2 = new AnetAPI\UserFieldType();
        $merchantDefinedField2->setName("favoriteColor");
        $merchantDefinedField2->setValue("blue");

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authOnlyTransaction");         
        $transactionRequestType->setAmount($products_deposit_amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
        $transactionRequestType->addToUserFields($merchantDefinedField1);
        $transactionRequestType->addToUserFields($merchantDefinedField2);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }
        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    return [
                        'code'           => $tresponse->getResponseCode(),
                        'transaction_id' => $tresponse->getTransId(),
                        'accountNumber'  => $tresponse->getAccountNumber(),
                        //'card'=>$tresponse->accountType,
                        'message'        => $tresponse->getMessages()[0]->getDescription(),
                    ];
                } else {
                    echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {
                        // echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        // echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                        return [
                            'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                            'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                            'message'    => "Transaction Failed",
                        ];
                    }
                }
                // Or, print errors if the API request wasn't successful
            } 
            else {
                echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    // echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    // echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                    return [
                        'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                        'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                        'message'    => "Transaction Failed",
                    ];
                } else {
                    // echo " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    // echo " Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                    return [
                        'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                        'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                        'message'    => "Transaction Failed",
                    ];
                }
            }      
        } else {
            echo  "No response returned \n";
        }

        return $response;
    }
    public function capturePreviouslyAuthorizedAmount($transcationid,$amount)
    {
        /* Create a merchantAuthenticationType object with authentication details
        retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $mode = config('gateway.mode');
        if ($mode == 'demo') {

            
            

        } else {

            $merchantAuthentication->setName(config('gateway.authorize.login'));
            $merchantAuthentication->setTransactionKey(config('gateway.authorize.key'));
        }
        // Set the 
        // Set the transaction's refId
        $refId = 'ref' . time();

        // Now capture the previously authorized  amount
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("priorAuthCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setRefTransId($transcationid);

        
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransactionRequest( $transactionRequestType);

        $controller = new AnetController\CreateTransactionController($request);
        if ($mode == 'demo') {

            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        }
        if ($response != null)
        {
            info("Auth Capture Function Response " . print_r($response, 1));
        if($response->getMessages()->getResultCode() == "Ok")
        {
            $tresponse = $response->getTransactionResponse();
            if ($tresponse != null && $tresponse->getMessages() != null)   
            {   
                return [
                    'status'           => true,
                    'code'           => $tresponse->getResponseCode(),
                    'transaction_id' => $tresponse->getTransId(),
                    'accountNumber'  => $tresponse->getAccountNumber(),
                    //'card'=>$tresponse->accountType,
                    'message'        => $tresponse->getMessages()[0]->getDescription(),
                ];
            }
            else
            {
            if($tresponse->getErrors() != null)
            {
                return [
                    'status'           => false,
                    'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                    'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                    'message'    => "Transaction Failed",
                ];
            }
            }
        }
        else {
            echo "Transaction Failed \n";
            $tresponse = $response->getTransactionResponse();
            if ($tresponse != null && $tresponse->getErrors() != null) {
                // echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                // echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                return [
                    'status'           => false,
                    'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                    'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                    'message'    => "Transaction Failed",
                ];
            } else {
                // echo " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                // echo " Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                return [
                    'status'           => false,
                    'code'       => $tresponse->getErrors()[0]->getErrorCode(),
                    'error_text' => $tresponse->getErrors()[0]->getErrorText(),
                    'message'    => "Transaction Failed",
                ];
            }
        }       
        }
        else
        {
        echo  "No response returned \n";
        }

        return $response;
    }
    public function AuthCard(Request $request)
    {
        $charge_customer          = $request->customer_profile?1:0;
        $amount                   = to_currency($request->amount,0);
        $msg                      = '';
        $customer_profile         = "";
        $customer_payment_profile = "";
        $cardholdername  = $request->first_name . ' ' . $request->last_name;
        $expiration_date = $request->card_expiry_year . '-' . $request->card_expiry_month;
        info($expiration_date);
        $customerInfo = 
            [
            'email'          => $request->email,
            'FirstName'      => $request->first_name,
            'LastName'       => $request->last_name,
            'Address'        => $request->address,
            'City'           => $request->city,
            'State'          => $request->is_international ? $request->province : $request->state,
            'Zip'            => $request->is_international ? $request->postal_code : $request->zip_code,
            'Counrty'        => $request->is_international ? $request->country : "USA",
            'PhoneNumber'    => $request->mobile_number,
            'CardNumber'     => $request->cnumber,
            'ExpirationDate' => $expiration_date,
            'CardCode'       => $request->cvv,
            'amount'         => $amount,
        ];
        if ($charge_customer == 0) {
            info("CREATING AUTH ONLY TRANSACTION FOR AMOUNT  : $request->products_deposit_amount" );
            $authorized_card_response = $payment_response = $this->authorizeCreditCard($customerInfo,$request->products_deposit_amount);
            info("CREATING AUTH ONLY TRANSACTION RESPONSE  : ".print_r($payment_response,1) );
            if ($payment_response['code'] != 1) {
                $data = [
                    "status"  => "error",
                    "error"   => "Error Charging Customer " . @$payment_response['error_text'],
                    "error_text"   => "Error Charging Customer " . @$payment_response['error_text'],
                    "message" => "Error Charging Customer " . @$payment_response['error_text'],
                ];
                return (object) $data;
            } else {
                $data['transactionId']          = $payment_response['transaction_id'];
                $data['last4']                  = $payment_response['accountNumber'];
                $data['customerProfile']        = "";
                $data['customerProfilePayment'] = "";
                $data['status']                 = "success";
            }
            info("returning data " . print_r($data, 1));
            return (object) $data;
        } else {
            //existing profile se charge karna hai
            $customer_profile         = $request->customer_profile;
            $customer_payment_profile = $request->customer_payment_profile;
            info("charging profile");
            $payment_response = $this->authorizeCustomerProfile($customer_profile, $customer_payment_profile, $amount);

            info("payment_response Result" . print_r($payment_response, 1));
            if ($payment_response['code'] != 1) {
                $data = [
                    "status"  => "error",
                    "error"   => "Error Charging Customer " . @$payment_response['error_text'],
                    "message" => "Error Charging Customer " . @$payment_response['error_text'],
                    "error_text" => "Error Charging Customer " . @$payment_response['error_text'],
                    
                ];
                return (object) $data;
            } else {

                $data['transactionId']          = $payment_response['transaction_id'];
                $data['last4']                  = $payment_response['accountNumber'];
                $data['customerProfile']        = $customer_profile;
                $data['customerProfilePayment'] = $customer_payment_profile;
                $data['status']                 = "success";

            }

            info("returning data " . print_r($data, 1));
            return (object) $data;
        }
    }
    
    public function captureTransaction(Request $request)
    {
        $amount                   = to_currency($request->amount,0);
        $result_void = $this->capturePreviouslyAuthorizedAmount($request->transaction_id,$amount);
        if ($result_void['code'] != 1) {
            $custom_response['status'] = 'error';
            $custom_response['error']  = $result_void['message'];
        } else {
            $custom_response['status']         = 'success';
            $custom_response['type']           = 'capture';
            $custom_response['transaction_id'] = $result_void['transaction_id'];

        }
        return (object) $custom_response;
    }
    public function chargeServiceFee(Request $request)
    {
        //
    }
}

 
