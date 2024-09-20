<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Payment;
use App\Services\Interfaces\PaymentMethodAdapterInterface;

class PaymentService implements PaymentMethodAdapterInterface
{

    protected mixed $service;
    protected bool $createProfile;
    protected EmailService $email_service;

    public function __construct($gateway = null , $createProfile = null)
    {
        //info("GATEWAY: ".config('gateway.gateway' ));
        //info("GATEWAY MODE: ".config('gateway.mode' ));
        if ( null != $gateway ) {

            $this->service = $gateway;

        } else {

            if ( config('gateway.gateway' ) == 'stripe' ) {

                $this->service = new StripeService;

            }

            if (config('gateway.gateway' ) == 'braintree' ){

                $this->service = new BraintreeService;

            }

            if (config('gateway.gateway' ) == 'authorize') {

                $this->service = new AuthorizeService;

            }

            if (config('gateway.gateway') == 'squareup') {
                $this->service = new SquareService;

            }

            if (config('gateway.gateway' ) == 'heartland') {

                $this->service = new HeartLandService;

            }

        }

        if ( null != $createProfile ) {

            $this->createProfile = true ;

        } elseif (config('gateway.method' ) == 'create_profile') {

            $this->createProfile = true ;

        } else {

            $this->createProfile = false ;
        }
        $this->email_service = new EmailService();

    }

    public function processPayment(Request $request ): object
    {

        info("In processPayment");
        if(config('gateway.mode') == 'demo' && $request->cnumber)
        {
            if(!in_array($request->cnumber,config('testing-card')))
            {
                $error_text = "Your Gateway is on demo mode and you are using live card on demo.";
                $this->email_service->failedPaymentEmails($error_text);
                $data = [
                    "status"  => "error",
                    "error"   => $error_text,
                    "message"   => $error_text,
                    "error_text"   => $error_text,
                ];
                return (object) $data;
            }
        }
        if ( $this->createProfile == null ) {
            if($request->product_deposit == true)
            {
                info("Called: AuthCard");
                $response= $this->service->AuthCard($request);
                info("End auth Profile".print_r($response,1));
            }else{
                info("Called: chargeCard");
                $response= $this->service->chargeCard($request);
                info("End Charging Profile".print_r($response,1));
            }
            if ($response->status=='success') {
                $this->savePayment($request,$response);
            }
            return $response;

        } else {

            info("createCustomerProfile");
            return $this->service->createCustomerProfile($request);

        }

    }

    public function createCustomerProfile(Request $request )
    {

        info("createCustomerProfile:PaymentService");
        $response=$this->service->createCustomerProfile($request);
        info("createCustomerProfile:PaymentService:response ".print_r($response,1));
        if ($response->status=='success') {
            $request->merge(
                [
                    'amount'=> 0,/*this will make this transaction as saved card. on payment stamp it will show CARD SAVED*/

                ]
            );
            $this->savePayment($request,$response);
        }
        return $response;

    }

    public function savePayment(Request $request,$response )
    {
        $customer_profile = $last4 =$transaction_id=$customer_payment_profile = null;
        if (isset($response)) {
            $customer_profile = @$response->customerProfile;
            $last4 = @$response->last4;
            $transaction_id = @$response->transactionId;
            $customer_payment_profile = @$response->customerProfilePayment;
        }
        if($request->gift_id)
        {
            // Don't Need it to create transcation for Gift Order
            // $paymentData = array(
            //   'first_name' => $request->first_name,
            //   'last_name' => $request->last_name,
            //   'email' => $request->email,
            //   'phone' => $request->phone,
            //   'zip' => $request->zip,
            //   'city' => $request->city,
            //   'state' => $request->state,
            //   'address' => $request->address,
            //   'amount' => $request->amount,
            //   'payment_method' => 'Stripe',
            //   'customer_card' => $request->credit_card,
            //   'transaction_id' => $response->transactionId,
            // );
            // info("savePayment TO DATABASE :PaymentService ". print_r($paymentData,1));
            // $payment = GiftCertificatePayment::query()->create($paymentData);
            // info("savePayment TO DATABASE : SAVED");
        }
        else
        {
            if($request->product_deposit == true)
            {
                $paymentData = array(
                    'reservation_id' => $request->reservation_id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->mobile_number,
                    'zip' => $request->zip_code,
                    'city' => $request->city,
                    'state' => $request->state,
                    'address' => $request->address,
                    'is_tour_reservation' => $request->is_tour_reservation,
                    'is_gratuity' => $request->is_gratuity,
                    'amount' => $request->products_deposit_amount,
                    'transaction_id' => $transaction_id,
                    'customer_id' => $customer_profile,
                    'customer_pay_profile_id' => $customer_payment_profile,
                    'is_auth' => 1,
                    'customer_card' => $last4,
                    'payment_method' => $request->payment_method.'_auth',
                );
            }else{
                $paymentData = array(
                    'reservation_id' => $request->reservation_id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->mobile_number,
                    'zip' => $request->zip_code,
                    'city' => $request->city,
                    'state' => $request->state,
                    'address' => $request->address,
                    'is_tour_reservation' => $request->is_tour_reservation,
                    'is_gratuity' => $request->is_gratuity,
                    'amount' => $request->amount,
                    'transaction_id' => $transaction_id,
                    'customer_id' => $customer_profile,
                    'customer_pay_profile_id' => $customer_payment_profile,
                    'customer_card' => $last4,
                    'payment_method' => $request->payment_method,
                );
            }
            info("savePayment TO DATABASE :PaymentService ". print_r($paymentData,1));
            $payment = Payment::query()->create($paymentData);
            info("savePayment TO DATABASE : SAVED");
        }

    }

    public function make_profile(Request $request )
    {

        $response=$this->service->createCustomerProfile($request);

        return $response;

    }

    public function chargeProfile(Request $request )
    {
        $response= $this->service->chargeProfile($request);
        info("Charge profile response ".print_r($response,1));
        if ($response->status=='success') {
            $this->savePayment($request,$response);
        }
        return $response;


    }
    public function authProfile(Request $request )
    {
        $response= $this->service->authProfile($request);
        info("Auth profile response ".print_r($response,1));
        if ($response->status=='success') {
            $this->savePayment($request,$response);
        }
        return $response;


    }

    public function getTransactionList(Request $request )
    {

        return $this->service->getTransactionList($request);


    }
    public function refundTransaction(Request $request )
    {

        return $this->service->refundTransaction($request);


    }

    public function voidTransaction(Request $request )
    {
        return $this->service->voidTransaction($request);


    }
    public function captureTransaction(Request $request )
    {
        return $this->service->captureTransaction($request);


    }


    public function get_transaction_details(Request $request )
    {

        return $this->service->get_transaction_details($request);


    }

    public function test_gateway_connection()
    {
        return $this->service->test_gateway_connection();
    }

    public function savePaymentForServiceFee(Request $request,$response )
    {
        $customer_profile = $last4 =$transaction_id=$customer_payment_profile = null;
        if (isset($response)) {
            $customer_profile = @$response->customerProfile;
            $last4 = @$response->last4;
            $transaction_id = @$response->transactionId;
            $customer_payment_profile = @$response->customerProfilePayment;
        }
        $paymentData = array(
            'reservation_id' => $request->reservation_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->mobile_number,
            'zip' => $request->zip_code,
            'city' => $request->city,
            'state' => $request->state,
            'address' => $request->address,
            'is_tour_reservation' => $request->is_tour_reservation,
            'is_service_fee' => 1,
            'amount' => $request->rezo_service_fee,
            'transaction_id' => $transaction_id,
            'customer_id' => $customer_profile,
            'customer_pay_profile_id' => $customer_payment_profile,
            'customer_card' => $last4,
            'payment_method' => $request->payment_method,
        );
        info("savePayment TO DATABASE :PaymentService ". print_r($paymentData,1));
        $payment = Payment::query()->create($paymentData);
        info("savePayment TO DATABASE : SAVED");
    }




}
