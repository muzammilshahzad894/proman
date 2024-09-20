<?php

namespace App\Services;

use App\Services\Interfaces\GatewayAdapterInterface;
use Braintree\Customer;
use Braintree_Transaction;
use Illuminate\Http\Request;

class BraintreeService implements GatewayAdapterInterface
{

    public function chargeCard(Request $request)
    {
        info("request ".print_r($request->all(),1));
        $expirationDate = $request->card_expiry_month . '/' . $request->card_expiry_year;
        $result         = \Braintree_Customer::create(array(
            'firstName'  => $request->first_name,
            'lastName'   => $request->last_name,
            'company'    => config('general.site_name'),
            'email'      => $request->email,
            'phone'      => $request->mobile_number,
            'fax'        => '123.456.7899',
            'website'    => config('general.site_url'),
            'creditCard' => array(
                'number'         => $request->cnumber,
                'cardholderName' => $request->first_name . " " . $request->last_name,
                'expirationDate' => $expirationDate,
                'cvv'            => $request->cvv,
            ),

        ));
        info("result ".print_r($result,1));

        if ($result->success) {

        } else {
            $response = [
                'status'   => 'error',
                'message' => $result->message,
                'error' => $result->message,
            ];

            return (object) $response;

        }

        $customer_profile_id     = $result->customer->id;
        $customer_card_last_four = $result->customer->creditCards[0]->last4;

        $result_transaction = Braintree_Transaction::sale([
            'amount'     => number_format((float)$request->amount,2,'.',''),
            'customerId' => $customer_profile_id,
            'options'    => [
                'submitForSettlement' => true,
            ],
        ]);

        

        if ($result_transaction->success === false) {
            $response = [
                'status'   => 'error',
                'response' => $result_transaction->message,
                'error' => $result_transaction->message,
                'message' => $result_transaction->message,

            ];

            return (object) $response;
        }

        $transactionId = $result_transaction->transaction->id;
        $last4         = $result_transaction->transaction->creditCard['last4'];

        $response = [
            'status'          => 'success',
            'transactionId'   => $transactionId,
            'customerProfile' => $customer_profile_id,
            'customerProfilePayment' => 0,
            'last4'           => $last4,
        ];

        return (object) $response;

    }
    public function refundTransaction(Request $request)
    {
        $result =Braintree_Transaction::refund($request->transaction_id, number_format((float)$request->refund_amount,2,'.',''));
            info("Refund result ".print_r($result,1));
            if ($result->success) {
                $custom_response['status']='success';
                $custom_response['type']='refund';
                $custom_response['transaction_id']=$result->transaction->id;
            }else{
                $custom_response['status']='error';
                $custom_response['error']=$result->message;
            }

        
        
        return (object) $custom_response;
    }

    public function voidTransaction(Request $request)
    {
        $result =Braintree_Transaction::void($request->transaction_id);
            info("void result ".print_r($result,1));
            if ($result->success) {
                $custom_response['status']='success';
                $custom_response['type']='void';
                $custom_response['transaction_id']=$result->transaction->id;
            }else{
                $custom_response['status']='error';
                $custom_response['error']=$result->message;
            }
        return (object) $custom_response;
    }

    public function get_transaction_details(Request $request)
    {
        $result_transaction =Braintree_Transaction::find($request->transaction_id);
        info("Transaction detaisl ".print_r($result_transaction,1));

        $custom_response['status']='success';

        if ($result_transaction->status=='settled' || $result_transaction->status=='settling') {
            $custom_response['transaction_status']='settled';
        }else{
            $custom_response['transaction_status']='not_settled';
            
        }
        return (object) $custom_response;
    }

    public function chargeProfile(Request $request)
    {
        $customer_profile_id         = $request->customer_profile;
        

        $result_transaction = Braintree_Transaction::sale([
            'amount'     => number_format((float)$request->amount,2,'.',''),
            'customerId' => $customer_profile_id,
            'options'    => [
                'submitForSettlement' => true,
            ],
        ]);

        $transactionId = $result_transaction->transaction->id;
        $last4         = $result_transaction->transaction->creditCard['last4'];

        if ($result_transaction->success === false) {
            $response = [
                'status'   => 'error',
                'response' => $result_transaction->message,

            ];

            return (object) $response;
        }

        $response = [
            'status'          => 'success',
            'transactionId'   => $transactionId,
            'customerProfile' => $customer_profile_id,
            'customerProfilePayment' => 0,
            'last4'           => $last4,
        ];

        return (object) $response;
    }

    public function getTransactionList(Request $request)
    {
        # code...
    }

    public function createCustomerProfile(Request $request)
    {

        $result          = null;
        $last4           = null;
        $customerProfile = null;

        $result = Customer::create([
            'firstName'  => $request->first_name,
            'lastName'   => $request->last_name,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'creditCard' => array(
                'number'         => $request->cnumber,
                'cardholderName' => $request->first_name . " " . $request->last_name,
                'expirationDate' => $expirationDate,
                'cvv'            => $cvv,
            ),
        ]);

        $customerProfile = $result->customer->id;
        $last4           = $result->customer->paymentMethods[0]->last4;

        if ($result->success === false) {
            $response = [
                'status'   => 'error',
                'response' => $result->message,

            ];

            return (object) $response;
        }

        $response = [
            'status'          => 'success',
            'customerProfile' => $customerProfile,
            'last4'           => $last4,
        ];

        return (object) $response;

    }

    public function getProfileByNounce(Request $request)
    {

        $result          = null;
        $last4           = null;
        $customerProfile = null;

        $result = Customer::create([
            'firstName'          => $request->first_name,
            'lastName'           => $request->last_name,
            'phone'              => $request->phone,
            'email'              => $request->email,
            'paymentMethodNonce' => $request->nonce,
        ]);

        $customerProfile = $result->customer->id;

        return $customerProfile;

    }

    public function test_gateway_connection()
      {
          return (object) [
                'status'=>'error',
                'message'=>'Not implemented yet.',
                'code'=>422,
            ];
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
