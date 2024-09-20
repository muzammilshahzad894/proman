<?php

return [

    'mode' => 'demo',
    'gateway' => 'stripe', // default
    'method' => 'charge', // create_profile
    'BRAINTREE_ENV' => 'sandbox', 

    'stripe' => [
        'model' => App\User::class,
        'secret' => env('STRIPE_SECRET_KEY'),
        'publish' => env('STRIPE_PUBLISH_KEY'),
    ],

    'braintree' => [
        'env'         => env('BRAINTREE_ENV','sandbox'),
        'private_key' => env('BRAINTREE_PRIVATE_KEY'),
        'public_key'  => env('BRAINTREE_PUBLIC_KEY'),
        'mechant_id'  => env('BRAINTREE_MERCHANT_ID')
    ],

    'authorize' => [
      'login' => env('AUTHORIZE_PAYMENT_API_LOGIN_ID'),
      'key'   => env('AUTHORIZE_PAYMENT_TRANSACTION_KEY')
  ],

];
