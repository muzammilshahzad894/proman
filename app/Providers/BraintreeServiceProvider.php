<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

class BraintreeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        $mode = config('gateway.mode');
        if ($mode == 'demo') {
          \Braintree\Configuration::environment( "sandbox" );
          \Braintree\Configuration::merchantId("xk4vrq7f32gr5r5v");
          \Braintree\Configuration::publicKey("s3xnv324xxmycb86");
          \Braintree\Configuration::privateKey("e3e4198b1ecdd54d970a29f1cf400c94");    # code...
        }else{
          \Braintree\Configuration::environment( "production" );
          \Braintree\Configuration::merchantId(config('gateway.braintree.mechant_id'));
          \Braintree\Configuration::publicKey(config('gateway.braintree.public_key'));
          \Braintree\Configuration::privateKey(config('gateway.braintree.private_key'));  
        }
          
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
