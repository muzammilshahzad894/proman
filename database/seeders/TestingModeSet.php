<?php

use Illuminate\Database\Seeder;

class TestingModeSet extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->where('key','mail')->update([
			[
				'key' => 'mail',
				'view_name' => 'settings.mail',
				'title' => 'Mail Settings',
				'config' => '{"host":"smtp.mailtrap.io","port":"2525","username":"ce459e1b69c197","password":"80c0cd950e4516","from":{"address":"reservations@rezosystems.com","name":"Rezosystems Rentals"},"driver":"smtp","domain":"reservations.rezosystems.com","secret":"key-25386939438ee660b25f321e6b964414"}',
			]
		]);
		DB::table('settings')->where('key','gateway')->update([
			[
				'key' => 'gateway',

				'view_name' => 'settings.gateway',
				
				'title' => 'Gateway Settings',

				'config' => '{"gateway":"authorize","mode":"demo","method":"charge","stripe":{"secret":null,"publish":null},"braintree":{"private_key":null,"public_key":null,"mechant_id":null},"authorize":{"login":null,"key":null},"squareup":{"application_id":null,"access_token":null,"location_id":null,"demo_application_id":null,"demo_access_token":null,"demo_location_id":null}}',

				'validation_rules'=>'{"config.is_live":"required|in:0,1","config.login_id":"required","config.transaction_key":"required"}'
			],
		]);

		\Cache::forget('mail');
		\Cache::forget('gateway');


    }
}
