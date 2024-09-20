<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('settings')->truncate();
        DB::table('settings')->insert([
			[
				'key' => 'general',
				'view_name' => 'settings.general',
				'title' => 'General Settings',
				'config' => '{"site_name":"Admin Panel Boiler Plate","default_email":"reservations@rezosystems.com","site_url":"http:\/\/127.0.0.1:8000\/login","additional_emails":[],"in_advance_rental_days":1,"reservation_fee":10,"discount":0,"phone":"(111) 111-1111","admin_phone":"(222) 222-2222","timezone":"America\/Denver","google_map_link":null}'
			]
		]);

		DB::table('settings')->insert([
			[
				'key' => 'site',
				'view_name' => 'settings.site',
				'title' => 'Site Settings',
				'config' => '{
							   "enable_sales_tax":"0",
							   "sales_tax_price":"0.00",
							}',
				
			]
		]);

		DB::table('settings')->insert([
			[
				'key' => 'gateway',

				'view_name' => 'settings.gateway',
				
				'title' => 'Gateway Settings',

				'config' => '{"is_live":0,"login_id":"","transaction_key":"","gateway":"authorize","mode":"demo","method":"charge","stripe":{"secret":"sk_test_tryLcpP0VgTYiDXdAtoLtqwR","publish":"pk_test_1TvlTEaWoV37XW2y1fbkKvN8"},"braintree":{"private_key":"020e6fdbe31fd558ed4fdafac060b144","public_key":"tsfrdypqt9fn9wyw","mechant_id":"mc9hg3ggzg4p4zg2"},"authorize":{"login":"4Cvy54Ds","key":"53qa9X3DA6z88E9T"}}',

				'validation_rules'=>'{"config.is_live":"required|in:0,1","config.login_id":"required","config.transaction_key":"required"}'
			],
		]);

		DB::table('settings')->insert([
			[
				'key' => 'mail',
				'view_name' => 'settings.mail',
				'title' => 'Mail Settings',
				'config' => '{"host":"mail.brownrice.com","port":"587","username":"reservations@rezosystems.com","password":"FatBike","from":{"address":"reservations@rezosystems.com","name":"Rezosystems Rentals"},"driver":"mailgun","domain":"reservations.rezosystems.com","secret":"key-65927a3a623615e00bd13923116e483b"}',
			]
		]);

		DB::table('settings')->insert([
			[
				'id' => 7,
				'key' => 'programmer_settings',
				'view_name' => 'settings.programmer',
				'title' => 'Programmer Settings',
				'config' => '{"is_sentry_enabled":"0","sentry_key":"1234567890"}',
			]
		]);

    }
}
