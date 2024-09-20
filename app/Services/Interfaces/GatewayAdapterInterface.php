<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

Interface GatewayAdapterInterface
{
  
  public function chargeCard(Request $request);
  
  public function createCustomerProfile(Request $request);
  
  public function chargeProfile(Request $request);
  public function getTransactionList(Request $request);
  public function refundTransaction(Request $request);
  public function voidTransaction(Request $request);
  public function get_transaction_details(Request $request);
  public function captureTransaction(Request $request);
  public function chargeServiceFee(Request $request);
  public function authProfile(Request $request);
  public function test_gateway_connection();
  
  
}
