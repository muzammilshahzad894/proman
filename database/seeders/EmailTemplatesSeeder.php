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
		
		DB::table('email_templates')->insert([
            [
				'id' => 3,
				'type' => 'email',
                'title' => 'Pending Reservation',
                'subject' => 'Pending Reservation',
				'static_body' => 'We have received your reservation request for [property]. Details below:

Guest Name: [firstname] [lastname]
Arrival: [date_start] Departure: [date_end] Number of nights: [nights]
Number of guests: Adults [adults] Children [children]
Pets [pets]



Total: [total_amount]',
                'dynamic_body' => '<p>Pending Reservation</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
            [
				'id' => 4,
				'type' => 'email',
                'title' => 'Booked Reservation',
                'subject' => 'Booked Reservation',
				'static_body' => 'Hi [firstname],

We are pleased to CONFIRM your reservation at [property].  Details of your reservation are below:

Guest Name:  [firstname] [lastname]
Arrival:  [date_start] Departure: [date_end] Number of nights:  [nights]
Number of guests:  Adults [adults] Children [children]
Pets [pets]
Lodging: [lodging]
Sales Tax: [sales_tax]
Lodgers Tax: [lodgers_tax]
Total: [total_amount]',
                'dynamic_body' => '<p>Booked Reservation</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 5,
				'type' => 'email',
                'title' => 'Booked Reservation (Full Payment)',
                'subject' => 'Booked Reservation (Full Payment)',
				'static_body' => 'Hi [firstname],

We are pleased to CONFIRM your reservation at [property].  Details of your reservation are below:

Guest Name:  [firstname] [lastname]
Arrival:  [date_start] Departure: [date_end] Number of nights:  [nights]
Number of guests:  Adults [adults] Children [children]
Pets [pets]

Lodging: [lodging]
Sales Tax: [sales_tax]
Lodgers Tax: [lodgers_tax]
Total: [total_amount]',
                'dynamic_body' => '<p>Booked Reservation (Full Payment)</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 6,
				'type' => 'email',
                'title' => 'Receipt of Deposit',
                'subject' => 'Receipt of Deposit',
				'static_body' => 'Hi [firstname],

We have received your deposit in the amount of $[amount_paid] for [property]. Details of your reservation follow:

Guest Name: [firstname] [lastname]
Arrival: [date_start] Departure: [date_end] Number of nights: [nights]
Number of guests: Adults [adults] Children [children]
Pets [pets]

[sum_detail]
Total: [total_amount]

AMOUNT PAID: $[amount_paid]
BALANCE DUE: $[amount_due]    
DATE DUE: [due_date]',
                'dynamic_body' => '<p>Receipt of Deposit</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 7,
				'type' => 'email',
                'title' => 'Final Receipt and Directions',
                'subject' => 'Final Receipt and Directions',
				'static_body' => 'Hi [firstname],

Final payment in the amount of $[amount_due] for your stay at [property] has been received.  

Guest Name:  [firstname] [lastname]
Arrival:  [date_start] Departure: [date_end] Number of nights: [nights]
Number of guests: Adults [adults] Children [children]
Pets  [pets]


Total: [total_amount] PAID IN FULL

We have complete instructions, directions and property information, please download the [file] PDF document',
                'dynamic_body' => '<p>Final Receipt and Directions</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 8,
				'type' => 'email',
                'title' => 'Owner Confirmation',
                'static_body' => 'Property: [property]
Arrival: [date_start] Departure: [date_end] Number of nights: [nights]

Guest Name: [firstname] [lastname]
Number of guests: Adults [adults] Children [children]

Pets [pets]



Total: [total_amount]',
                'dynamic_body' => '<p>Owner Confirmation</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 9,
				'type' => 'email',
                'title' => 'Housekeeper job assigned',
                'subject' => 'Housekeeper job assigned',
                'static_body' => 'Dear [housekeeper],
You have been assigned a job.
Please arrange cleaning after 10:00AM on the day of departure.

Property: [property]
Guest Name: [firstname] [lastname]
Arrival: [date_start] Departure: [date_end] Number of nights: [nights]
Number of guests: Adults [adults] Children [children]
Pets [pets]

Thanks.',
                'dynamic_body' => '<p>Housekeeper job assigned</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 10,
				'type' => 'email',
                'title' => 'Manager Alert',
                'subject' => 'Manager Alert',
                'static_body' => 'Dear [maintenance],
Your property has guests scheduled.

Property: [property]
Guest Name: [firstname] [lastname]
Arrival: [date_start] Departure: [date_end] Number of nights: [nights]
Number of guests: Adults [adults] Children [children]
Pets [pets]

Thanks.',
                'dynamic_body' => '<p>Manager Alert</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 11,
				'type' => 'email',
                'title' => 'Booked Reservation- Accept Terms & Conditions ',
                'subject' => 'Booked Reservation- Accept Terms & Conditions ',
                'static_body' => 'Hi [firstname],

We are pleased to CONFIRM your reservation at [property].  Details of your reservation are below:

Guest Name:  [firstname] [lastname]
Arrival:  [date_start] Departure: [date_end] Number of nights:  [nights]
Number of guests:  Adults [adults] Children [children]
Pets [pets]

[sum_detail]
Total: [total_amount]',
                'dynamic_body' => '<p>Booked Reservation- Accept Terms & Conditions </p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 12,
				'type' => 'email',
                'title' => 'Booked Reservation - 50% Deposit Receipt',
                'subject' => 'Booked Reservation - 50% Deposit Receipt',
                'static_body' => 'Hi [firstname],

We are pleased to CONFIRM your reservation at [property].  Details of your reservation are below:

Guest Name:  [firstname] [lastname]
Arrival:  [date_start] Departure: [date_end] Number of nights:  [nights]
Number of guests:  Adults [adults] Children [children]
Pets [pets]

[sum_detail]
Total: [total_amount]

We have received your 50% deposit of $[amount_paid] today. We will be charging you the balance of $[amount_due] on [due_date].',
                'dynamic_body' => '<p>Booked Reservation - 50% Deposit Receipt</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 13,
				'type' => 'email',
                'title' => 'Owner "Self Booked" Confirmation',
                'subject' => 'Owner "Self Booked" Confirmation',
                'static_body' => 'Hi [Owner_firstname],

We are pleased to CONFIRM your reservation at [property].  Details of your reservation are below:

Guest Name:  [firstname] [lastname]
Arrival:  [date_start] Departure: [date_end] Number of nights:  [nights]
Number of guests:  Adults [adults] Children [children]
Pets [pets]',
                'dynamic_body' => '<p>Owner "Self Booked" Confirmation</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 14,
				'type' => 'email',
                'title' => 'Reservation balance payment is due',
                'subject' => 'Reservation balance payment is due',
                'static_body' => 'Dear [firstname],

Just a reminder, we will bill your credit card for the final Payment of $[amount_due] in 3 days. If you want to pay with a different credit card, please contact our offices at (719) 658-2533.
Thank you for your business.
',
                'dynamic_body' => '<p>Reservation balance payment is due</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 15,
				'type' => 'email',
                'title' => 'Payment Process Existing Reservation',
                'subject' => 'Payment Process Existing Reservation',
                'static_body' => 'Hi [firstname],

We have charged [amount_paying] on your reservation at [property].  Details of your reservation are below:
Arrival: [date_start]
Departure: [date_end]
Total: [total_amount]
',
                'dynamic_body' => '<p>Payment Process Existing Reservation</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
		]);
		
		DB::table('email_templates')->insert([
			[
				'id' => 16,
				'type' => 'email',
                'title' => 'Cancelled Reservation',
                'subject' => 'Cancelled Reservation',
                'static_body' => 'Hi [firstname],

We are pleased to inform you that your reservation at [property] is cancelled.  Details of your reservation are below:

Guest Name:  [firstname] [lastname]
Arrival:  [date_start] Departure: [date_end] Number of nights:  [nights]
Number of guests:  Adults [adults] Children [children]
Pets [pets]
Lodging: [lodging]
Sales Tax: [sales_tax]
Lodgers Tax: [lodgers_tax]
Total: [total_amount]',
                'dynamic_body' => '<p>Cancelled Reservation</p>',
                'active' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
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
