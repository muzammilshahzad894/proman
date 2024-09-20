<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

Interface PaymentMethodAdapterInterface
{
  public function savePayment(Request $request,$response_object);
  public function savePaymentForServiceFee(Request $request,$response_object);
  public function processPayment(Request $request);
  public function make_profile(Request $request); //will require billing card details and amount
  public function chargeProfile(Request $request);// will require amount,customer_profile,customer_payment_profile
  public function getTransactionList(Request $request);
  public function refundTransaction(Request $request);
  public function captureTransaction(Request $request);
  public function authProfile(Request $request);
  public function test_gateway_connection();
  
  
}
