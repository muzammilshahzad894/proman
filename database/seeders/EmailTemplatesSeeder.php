<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplatesSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		DB::table('email_templates')->truncate();

		DB::table('email_templates')->insert([
			[
				'id' => 1,
				'type' => 'email',
				'title' => 'Login Details',
				'subject' => 'Login details',
				'static_body' => '<b>Dear [user_name],</b>
				<p>We have created a profile for you in [site_name] Admin Panel. Your account details are listed below:</p>
				<p>Please click <a href="[site_url]">[site_url]</a> to login.</p>
				<p style="margin-bottom: 0px;"><b>Login:</b> [user_email]</p>
				<p style="margin-top: 0px;"><b>Password:</b> [user_password]</p>',
				'dynamic_body' => '',
				'active' => 1,
				'status' => 0,
				'admin_copy' => 0,
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 2,
				'type' => 'sms',
				'title' => 'Follow-up SMS',
				'subject' => 'Follow-up SMS',
				'static_body' => '<b>Dear [user_name],</b>
				<p>I wanted to follow up on the SMS I sent you regarding [sms_purpose]. I wanted to make sure you did not miss the important information conveyed in the SMS.If you have any questions or require further assistance, please feel free to reach out to me. I am here to help.</p>
				<p>Thank you for your attention.</p>
				<p>[user_name].</p>
				',
				'dynamic_body' => '',
				'active' => 1,
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			],
		]);

		// Sample below

		// DB::table('email_templates')->insert([
		// 	[
		// 		'id' => 1,
		// 		'title' => 'Reservation Details',
		// 		'subject' => 'Reservation Details',
		// 		'static_body' => 'Dear [CUSTOMER_NAME],

		// 		Thank you for making a reservation at [SITE_NAME]. Your reservation details are below:

		// 		Pickup Date: [PICKUP_DATE]
		// 		Pickup Time: [PICKUP_TIME]
		// 		Reservation Type: [TYPE]
		// 		Map : [MAP_LINK]
		// 		Rental items(s) :
		// 		[RENTAL TITLE ([RENTAL_AMOUNT])]
		// 		Sales Tax: [SALES_TAX]
		// 		Total Amount: [TOTAL_AMOUNT]',

		// 		'dynamic_body' => '',
		// 		'active' => 1,
		// 		'created_at' => \Carbon\Carbon::now(),
		// 		'updated_at' => \Carbon\Carbon::now(),
		// 	],
		// ]);

	}
}
