<?php

use App\Http\Controllers\EmailController;
use App\InventoryBlockDate;
use App\InventoryMaintenance;
use App\Models\Addon;
use App\Category;
use App\Product;
use App\ProductDayRate;
use App\Reservation;
use App\Reservee;
use App\TimeslotByDay;
use App\TourCategory;
use App\StaticPage;
use App\User;
use App\ProductTimeSlotRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Season;

const INSURANCE_ADDON_ID = 16;
const CHAFFEE_TAX = 2; //its percentage 2%
const COLORADO_TAX_AMOUNT = 2.05; //its fixed value $2.00 // changed to 2.05 on 1 july 2022.
// const NATIONAL_FOREST_FEE = "{{config('site.national_forest_fee')}}";
// const RED_CLIFF_FEE = "{{config('site.red_cliff_fee')}}"; //its 
const BUDDY_PARTNER_ID = "p-g1ysxl1ki6cjbrw";
const BUDDY_ORDER_URL = "https://partners.buddyinsurance.com/v1/order/batch";

//const BUDDY_PARTNER_ID="p-zr8l1kffdtxim";
//const BUDDY_ORDER_URL="https://staging.partners.buddyinsurance.com/v1/order/batch";


function assetsUrl()
{

	// $url = asset('/');

	// return $url;

}
function check_repair_status($inventory_id, $date)
{
	$date = date("Y-m-d", strtotime((new Carbon($date))->toDateString()));

	//info("inventory_id $inventory_id");
	//info("date $date");
	$reserved_for_maintenance = InventoryMaintenance::query()

		->Where('inventory_id', $inventory_id)
		->Where(function ($q) use ($date) {
			$q
				->whereDate('block_date_start', '<=', $date)
				->whereDate('block_date_end', '>=', $date);
		});
	if ($reserved_for_maintenance->count() > 0) {
		return $reserved_for_maintenance;
	} else {
		return true;
	}
}

function need_maintenance($inventory_id, $date)
{
	$date = (new Carbon($date))->toDateString();
	//info("inventory_id $inventory_id");
	//info("date $date");
	$reserved_for_maintenance = InventoryMaintenance::query()

		->Where('inventory_id', $inventory_id)
		->Where(function ($q) use ($date) {
			$q
				->whereDate('block_date_start', '<=', $date)
				->whereDate('block_date_end', '>=', $date);
		});
	if ($reserved_for_maintenance->count() > 0) {
		$inventory_maintenance = $reserved_for_maintenance->get();
		$data['tooltip_text'] = "";
		foreach ($inventory_maintenance as $inventory_maintenance_single) {
			$data['tooltip_text'] .= "<br>";
			$data['tooltip_text'] .= "Maintenance: " . @$inventory_maintenance_single->maintenance_item->title . "<br>";
			$data['tooltip_text'] .= "From: " . to_date($inventory_maintenance_single->block_date_start) . "<br>";
			$data['tooltip_text'] .= "To: " . to_date($inventory_maintenance_single->block_date_end) . "<br>";
			$data['tooltip_text'] .= "<br>";
		}
		return (object) $data;
	} else {
		return false;
	}
}

function in_blocked_status($inventory_id, $date)
{
	$date = (new Carbon($date))->toDateString();
	// $date = date("Y-m-d", strtotime($date));
	//info("inventory_id $inventory_id");
	$blocked_inventory = InventoryBlockDate::query()

		->Where('inventory_id', $inventory_id)
		->Where('block_date', $date);
	//info("in_blocked_status $date $inventory_id COUNT: ".$blocked_inventory->count());
	if ($blocked_inventory->count() > 0) {
		return true; /* block hai*/
	} else {
		return false;
	}
}

function check_repair_status_all()
{

	$reserved_for_maintenance = InventoryMaintenance::pluck('id')->toArray();

	return $reserved_for_maintenance;
}
function to_currency($value, $symbol = true)
{
	$value = str_replace(',', '', $value);
	$value = (float) $value;
	if ($symbol) {
		return "$" . number_format($value, 2);
	} else {
		return number_format($value, 2, '.', '');
	}
}

function addons_price($addons, $addon_qty, $amount, $days_count)
{
	if ($amount == null) {
		$amount = 0;
	}
	//return $this->reservation_addons;
	$total_addon_charges = 0;
	if (is_array($addons) && count($addons) > 0) {
		foreach ($addons as $qty => $addon_id) {

			$addon = Addon::find($addon_id);



			if ($addon) {



				//echo "<p>$addon->title</p>";
				//echo "<p>Amount: $amount</p>";
				if ($amount > 0 && $addon->percent_of_rental_rate) {
					$addon_price = $amount * $addon->percent_of_rental_rate_value / 100;
					//echo "<p>Percentage rate yes</p>";

				} else {
					$addon_price = $addon->price;
				}

				//echo "<p>orignal price: $addon_price</p>";

				//yeh wali funcationality oper wali functionality ko overwrite karay gi.
				if (config('site.buddy_api') && $addon->id == config('site.buddy_addon_id')  && @$addon_qty[$qty] > 0) {
					$addon_price = get_buddy_quote($days_count);
					$addon_charges = $addon_price;
				} elseif (config('site.insurance_pricing') && $addon->id == config('site.insurance_addon_id')  && @$addon_qty[$qty] > 0) {
					if ($days_count > 2) {
						$extra_days = $days_count - 2;
						$addon_charges = ($addon_price * @$addon_qty[$qty]);
						$addon_charges = $addon_charges + ($addon->additional_days_price * @$addon_qty[$qty] * $extra_days);
					} else {
						$addon_charges = ($addon_price * @$addon_qty[$qty]);
					}
				} else {
					if (strtolower($addon->type) == 'per day') {

						if ($days_count > 1) {
							$addon_charges = ($addon_price * @$addon_qty[$qty] * $days_count);
							//echo "<p>All days rate: $addon_charges</p>";
						} else {
							$addon_charges = ($addon_price * @$addon_qty[$qty] * 1); // if days are less then 1 suppose days count is 1.
						}
					} else {
						$addon_charges = ($addon_price * @$addon_qty[$qty]);
					}
				}






				$total_addon_charges = $addon_charges + $total_addon_charges;
			}
		}
	}
	return $total_addon_charges;
}
function non_taxable_addon_prices($addons, $addon_qty, $amount, $days_count)
{
	if ($amount == null) {
		$amount = 0;
	}
	//return $this->reservation_addons;
	$total_addon_charges = 0;
	if (is_array($addons) && count($addons) > 0) {
		foreach ($addons as $qty => $addon_id) {
			$addon = Addon::find($addon_id);
			if ($addon && $addon->non_taxable_addon) {
				if ($amount > 0 && $addon->percent_of_rental_rate) {
					$addon_price = $amount * $addon->percent_of_rental_rate_value / 100;
				} else {
					$addon_price = $addon->price;
				}

				//yeh wali funcationality oper wali functionality ko overwrite karay gi.
				if (config('site.buddy_api') && $addon->id == config('site.buddy_addon_id')  && @$addon_qty[$qty] > 0) {
					$addon_price = get_buddy_quote($days_count);
					$addon_charges = $addon_price;
				} elseif (config('site.insurance_pricing') && $addon->id == config('site.insurance_addon_id')  && @$addon_qty[$qty] > 0) {
					if ($days_count > 2) {
						$extra_days = $days_count - 2;
						$addon_charges = ($addon_price * @$addon_qty[$qty]);
						$addon_charges = $addon_charges + ($addon->additional_days_price * @$addon_qty[$qty] * $extra_days);
					} else {
						$addon_charges = ($addon_price * @$addon_qty[$qty]);
					}
				} else {
					if (strtolower($addon->type) == 'per day') {
						if ($days_count > 1) {
							$addon_charges = ($addon_price * @$addon_qty[$qty] * $days_count);
							//echo "<p>All days rate: $addon_charges</p>";
						} else {
							$addon_charges = ($addon_price * @$addon_qty[$qty] * 1); // if days are less then 1 suppose days count is 1.
						}
					} else {
						$addon_charges = ($addon_price * @$addon_qty[$qty]);
					}
				}
				$total_addon_charges = $addon_charges + $total_addon_charges;
			}
		}
	}
	return $total_addon_charges;
}
function addons_reservations($addons, $request)
{
	//return $this->reservation_addons;

	if (is_array($addons) && count($addons) > 0) {
		foreach ($addons as $qty => $addon_id) {

			$addon = Addon::find($addon_id);
			if ($addon) {
				$reservations = Reservation::select('reservations.*')
					->where('is_cancelled', 0)
					->where('deleted_at', null)
					->join('reservees', 'reservations.id', 'reservees.reservation_id')
					->leftjoin('reservee_addons', 'reservee_addons.reservation_id', '=', 'reservations.id')
					->where('reservee_addons.addon_id', $addon->id)
					->where(function ($q) use ($request) {
						$q->whereBetween('reservees.start_date', [$request->start_date, $request->end_date]);
					})->count();
				if ($reservations <= $addon->max_limit) {
					return false;
				}
			}
		}
	}
	return true;
}
function buddy_amount($addons, $addon_qty, $amount, $days_count)
{
	//yeh funciton sirf buddy addon ki total price calculate karta hai
	$buddy_charges = 0;
	if (is_array($addons) && count($addons) > 0) {
		foreach ($addons as $qty => $addon_id) {
			$addon = Addon::find($addon_id);
			if ($addon) {
				if (config('site.buddy_api') && $addon->id == config('site.buddy_addon_id')  && $addon_qty[$qty] > 0) {
					$buddy_charges = get_buddy_quote($days_count);
				}
			}
		}
	}
	return $buddy_charges;
}

function getUser($user_id = '')
{

	if ($user_id == '') {

		return Auth::user();
	} else {

		return User::find($user_id);
	}
}

function UserName()
{
	return ucfirst(getUser()->first_name) . ' ' . ucfirst(getUser()->last_name);
}

function getUserMeta($user_id = '')
{

	if ($user_id == '') {

		$user = UserMeta::where('user_id', getUser()->id)->first();
	} else {

		$user = UserMeta::where('user_id', $user_id)->first();
	}
	return $user;
}

function getUserAvatar($user_id = '')
{

	if ($user_id == '') {

		$user = getUserMeta();
	} else {
		$user = getUserMeta($user_id);
	}

	if (null != $user->avatar) {
		return $user->avatar;
	} else {
		return 'avatar.jpg';
	}
}

function includefile($file)
{
	return include base_path() . ('/resources/views/' . $file . '.blade.php');
}

function sendEmail($emailData)
{

	$email = EmailController::sendEmail($emailData);

	if ($emailData['action'] == 'view') {

		return $email;
	} else if ($email == false) {
		return false;
	} else {
		return true;
	}
}

/*
 *
 * Returns the formatted date
 * @param Carbon $dateTime object
 * @return: date string
 *
 */

function formatted_date($date)
{
	if (!empty($date)) {
		return Carbon::parse($date)->format('jS M, Y');
	} else {
		return "";
	}
}

function getBodyContent(DOMNode $element)
{
	$doc = $element->ownerDocument;
	$wrapper = $doc->createElement('div');
	foreach ($element->childNodes as $child) {
		$wrapper->appendChild($child);
	}
	$element->appendChild($wrapper);
	$html = $doc->saveHTML($wrapper);
	return substr($html, strlen("<div>"), -strlen("</div>"));
}

if (!function_exists('storeImage')) {

	/**
	 * Store Image to profiles folder
	 *
	 * @param $image
	 * @param null $name
	 * @param string $folder
	 * @return mixed
	 */
	function storeImage1($image, $folder = 'profiles', $given_name = null)
	{
		$name = is_null($given_name) ? str_random(5) : $given_name;
		$full_name = $name . '-' . rand(1, 6000) . '.' . $image->extension();

		return \Storage::disk('public')->putFileAs($folder, $image, $full_name);
	}

	function storeImage($image, $folder = 'profiles', $given_name = null)
	{
		$filename = "product_" . time() . "." . $image->getClientOriginalExtension();
		$thumb_filename = "thumb_" . $filename;
		try {
			$img = Image::make($image);

			$canvas = Image::canvas(800, 600, '#fffff');
			$img->resize(780, null, function ($constraint) {
				$constraint->aspectRatio();
			})->save('uploads/' . $filename);
			/*list($width, $height) = getimagesize("uploads/$filename");
				                    if ($width>$height) {
				                        $height=null;
				                    }else{
				                        $width=null;
			*/
			$canvas->insert('uploads/' . $filename, 'center')->resize(800, null, function ($constraint) {
				$constraint->aspectRatio();
			});
			$canvas->save('uploads/thumbs/' . $filename, 70);
		} catch (\Exception $e) {
			return $e->getMessage();
		}
		return $filename;
	}

	function store_to_uploads($image)
	{

		$name = time() . '.' . $image->getClientOriginalExtension();
		$destinationPath = public_path('/uploads');
		$image->move($destinationPath, $name);
		return $name;
	}
}

if (!function_exists('base64ToImage')) {

	/**
	 * Store Image to profiles folder
	 *
	 * @param $image
	 * @param null $name
	 * @param string $folder
	 * @return mixed
	 */
	function base64ToImage($image64, $folder = 'waivers', $given_name = null)
	{
		info('Storage waiver image on disk');
		try {
			$image64 = str_replace('data:image/png;base64,', '', $image64);
			$image64 = str_replace(' ', '+', $image64);
			$imageName = $folder . '/' . ($given_name ? $given_name . '.' . 'png' : str_random(10) . '.' . 'png');

			\Storage::disk('public')->put($imageName, base64_decode($image64));
			info('SUCCESS: Storage waiver image on disk');
		} catch (\Exception $e) {
			info('ERROR: Storage waiver image on disk ' . $e->getMessage());
		}

		return $imageName;
	}
}

if (!function_exists('round_normal')) {

	/**
	 * @return mixed
	 */
	function round_normal($value)
	{
		return number_format((float) $value, 2, '.', '');
	}
}

if (!function_exists('thousand_separator')) {

	/**
	 * @return mixed
	 */
	function thousand_separator($value)
	{
		return number_format((float) $value, 2, '.', ',');
	}
}

if (!function_exists('calc_tax_amount')) {

	/**
	 * @return int
	 */
	function calc_tax_amount($price, $percentage)
	{
		$amount = $price * $percentage / 100;

		return round_normal($amount);
	}
}

function calculate_site_tax($amount)
{
	$tax_amount = 0;

	if (config('site.enable_sales_tax')) {
		$tax_percentage = config('site.sales_tax_price');
		$tax_amount = calc_tax_amount($amount, $tax_percentage);
	}

	return $tax_amount;
}

if (!function_exists('timeToHour')) {

	/**
	 * @param $time
	 * @return int
	 */
	function timeToHour($time)
	{
		return (int) date("H", strtotime($time));
	}
}

if (!function_exists('getAllCountries')) {

	/**
	 * @return array
	 */
	function getAllCountries()
	{
		return array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
	}
}

if (!function_exists('getAmericanStates')) {

	/**
	 * @return array
	 */
	function getAmericanStates()
	{
		return array(
			'AL' => 'Alabama',
			'AK' => 'Alaska',
			'AZ' => 'Arizona',
			'AR' => 'Arkansas',
			'CA' => 'California',
			'CO' => 'Colorado',
			'CT' => 'Connecticut',
			'DE' => 'Delaware',
			'DC' => 'District Of Columbia',
			'FL' => 'Florida',
			'GA' => 'Georgia',
			'HI' => 'Hawaii',
			'ID' => 'Idaho',
			'IL' => 'Illinois',
			'IN' => 'Indiana',
			'IA' => 'Iowa',
			'KS' => 'Kansas',
			'KY' => 'Kentucky',
			'LA' => 'Louisiana',
			'ME' => 'Maine',
			'MD' => 'Maryland',
			'MA' => 'Massachusetts',
			'MI' => 'Michigan',
			'MN' => 'Minnesota',
			'MS' => 'Mississippi',
			'MO' => 'Missouri',
			'MT' => 'Montana',
			'NE' => 'Nebraska',
			'NV' => 'Nevada',
			'NH' => 'New Hampshire',
			'NJ' => 'New Jersey',
			'NM' => 'New Mexico',
			'NY' => 'New York',
			'NC' => 'North Carolina',
			'ND' => 'North Dakota',
			'OH' => 'Ohio',
			'OK' => 'Oklahoma',
			'OR' => 'Oregon',
			'PA' => 'Pennsylvania',
			'RI' => 'Rhode Island',
			'SC' => 'South Carolina',
			'SD' => 'South Dakota',
			'TN' => 'Tennessee',
			'TX' => 'Texas',
			'UT' => 'Utah',
			'VT' => 'Vermont',
			'VA' => 'Virginia',
			'WA' => 'Washington',
			'WV' => 'West Virginia',
			'WI' => 'Wisconsin',
			'WY' => 'Wyoming',
		);
	}

	function isGuide()
	{
		if (auth()->check()) {

			return @\Auth::user()->role == 'guide' or @\Auth::user()->role == 'Guide' or strtolower(@\Auth::user()->type) == 'guide';
		} else {

			return false;
		}
	}

	function save_reservation_emails($reservation_id, $is_tour, $name, $to, $subject, $content)
	{
		$reservation_email = new App\ReservationEmails;
		$reservation_email->to = $to;
		$reservation_email->name = $name;
		$reservation_email->reservation_id = $reservation_id;
		$reservation_email->is_tour_reservation = $is_tour;
		$reservation_email->content = $content;
		$reservation_email->subject = $subject;
		$reservation_email->save();
	}
	function to_date($date_in_any_format, $with_time = false)
	{
		if ($date_in_any_format == "" || $date_in_any_format == "0000-00-00" || $date_in_any_format == "1969-12-31" || $date_in_any_format == "1970-01-01") {
			return "";
		}
		if (config('site.australia_timezone')) {
			if ($date_in_any_format) {
				if ($with_time) {
					return date('d/m/Y h:i a', strtotime($date_in_any_format));
				} else {
					return date('d/m/Y', strtotime($date_in_any_format));
				}
			} else {
				return "";
			}
		} else {
			if ($date_in_any_format) {
				if ($with_time) {
					return date('m/d/Y h:i a', strtotime($date_in_any_format));
				} else {
					return date('m/d/Y', strtotime($date_in_any_format));
				}
			} else {
				return "";
			}
		}
	}
}
if (!function_exists('getAustrailianStates')) {
	function getAustrailianStates()
	{
		return array(
			"NSW" => "New South Wales",
			"VIC" => "Victoria",
			"QLD" => "Queensland",
			"TAS" => "Tasmania",
			"SA" => "South Australia",
			"WA" => "Western Australia",
			"NT" => "Northern Territory",
			"ACT" => "Australian Capital Territory"
		);
	}
}
if (!function_exists('get_available_inventories')) {

	function get_available_inventories(array $products, Carbon $start_date, Carbon $end_date, $booking_hour_id = null)
	{
		$start_date = $start_date->toDateString();

		$is_24_hour = false;
		if ($book_hr = \App\TimeSlotBookHour::query()->find($booking_hour_id)) {
			// If timeslot is 24 hour then we will get null value for end date from request
			// so we need to check that and add one day in start date and make end date
			$is_24_hour = $book_hr->timeSlot ? $book_hr->timeSlot->is_24_hour : false;
			if ($is_24_hour) {
				$end_date = Carbon::parse($start_date);
			}
		}

		// Now we are making a date string
		$end_date = $end_date->toDateString();

		if ($is_24_hour || ($start_date == $end_date)) {
			$reserved_inventories = \App\Reservee::query()
				->when($book_hr, function ($q) use ($book_hr) {
					$pickup_time = Carbon::parse($book_hr->pickup_time)->toTimeString();
					$return_time = Carbon::parse($book_hr->return_time ? $book_hr->return_time : $book_hr->pickup_time)->toTimeString();

					$q->whereRaw('((pickup_time >= ? AND pickup_time <= ?) OR (return_time <= ? AND return_time >= ?))', [$pickup_time, $return_time, $return_time, $pickup_time]);
				})
				->whereDate('start_date', '<=', $start_date)
				->whereDate('end_date', '>=', $end_date)
				->distinct()
				->pluck('inventory_id');
		} else {
			$reserved_inventories = \App\Reservee::query()
				->when($book_hr, function ($q) use ($book_hr) {
					$pickup_time = Carbon::parse($book_hr->pickup_time)->toTimeString();
					$return_time = Carbon::parse($book_hr->return_time ? $book_hr->return_time : $book_hr->pickup_time)->toTimeString();

					$q->whereRaw('((pickup_time >= ? AND pickup_time <= ?) OR (return_time <= ? AND return_time >= ?))', [$pickup_time, $return_time, $return_time, $pickup_time]);
				})
				->whereRaw('((start_date >= ? AND start_date <= ?) OR (end_date <= ? AND end_date >= ?))', [$start_date, $end_date, $start_date, $end_date])
				->distinct()
				->pluck('inventory_id');
		}

		$available_inventories = \App\Inventory::query()
			->whereIn('product_id', $products)
			->whereNotIn('id', $reserved_inventories)
			->get();

		return $available_inventories;
	}
}

function get_timeslot_formatted($timeslot_id)
{
	$timeslots = DB::table('tour_timeslots')->whereId($timeslot_id)->first();
	if ($timeslots) {
		return "From $timeslots->from_hour:$timeslots->from_minutes " . strtolower($timeslots->from_daypart) . "  To $timeslots->to_hour:$timeslots->to_minutes " . strtolower($timeslots->to_daypart);
	} else {
		return "Invalid timeslot";
	}
}

function get_timeslot_formatted_for_shuttles($timeslot_id)
{
	$timeslots = DB::table('tour_timeslots')->whereId($timeslot_id)->first();
	if ($timeslots) {
		return "$timeslots->from_hour:$timeslots->from_minutes " . strtolower($timeslots->from_daypart);
	} else {
		return "Invalid timeslot";
	}
}
function get_reservation_addon($reservation_id, $addon_id)
{
	return DB::table('tour_reservation_addons')->where('reservation_id', $reservation_id)->where('addon_id', $addon_id)->first();
}

function get_inventory_title($inventory_id)
{
	$inventory = DB::table('inventories')->whereId($inventory_id)->first();
	if ($inventory) {
		return "$inventory->unit_name - $inventory->unit_no";
	} else {
		return "NA";
	}
}

function attribute_value_id_count($reserved_inventories, $product_id, $attribute_value_id, $location_id = null)
{

	$results = \App\Inventory::query()
		->where('product_id', $product_id)
		->whereNotIn('id', $reserved_inventories)
		->where('attribute_value_id', $attribute_value_id)
		->where('is_active', 1)
		->where('is_sold', 0)
		->when($location_id, function ($query, $location_id) {
			return $query->where('location_id', $location_id);
		})
		->count();

	return $results;
}
function save_reservation_sms($message, $subject, $to, $name, $reservation_id)
{
	\App\ReservationEmail::query()->create([
		'reservation_id' => $reservation_id,
		'name' => $name,
		'to' => $to,
		'type' => 'sms',
		'is_tour_reservation' => '1',
		'subject' => $subject,
	]);
}

function new_rental_reservations()
{
	return DB::table('reservations')->select('reservations.*')
		->join('reservees', 'reservations.id', 'reservees.reservation_id')
		->where('reservees.is_tour_reservation', '0')
		->whereDate('reservees.start_date', '>=', Carbon::today()->toDateString())
		->where('reservations.approved', '=', 0)
		->distinct()
		->get()->count();
}
function disabled_dates_array()
{
	return explode(',', config('site.disabled_dates'));
}

function expiration_years()
{
	$current_year = date('Y');
	$max_years = $current_year + 20;
	$expiration_years = [];
	for ($i = $current_year; $i <= $max_years; $i++) {
		$expiration_years[] = $i;
	}
	return $expiration_years;
}

if (!function_exists('getUser')) {

	/**
	 * @param string $email
	 * @return mixed
	 */
	function getUser($email = '')
	{
		if ($email == '') {

			return \Auth::user();
		} else {

			return \User::where('email', $email)->first();
		}
	}
}

function shuttle_time_status($shuttleTimeId, $addonId)
{
	$record = \DB::table('addon_shuttle_times')
		->where('addon_id', $addonId)
		->where('shuttle_time_id', $shuttleTimeId)
		->first();
	if ($record) {
		return $record->active;
	} else {
		return 0;
	}
}

function shuttle_time_riders($shuttleTimeId, $addonId)
{
	$record = \DB::table('addon_shuttle_times')
		->where('addon_id', $addonId)
		->where('shuttle_time_id', $shuttleTimeId)
		->first();
	if ($record) {
		return $record->max_riders;
	} else {
		return 0;
	}
}
function OnlineDiscount($rate = 0)
{
	$discount = 0;

	if (config('site.online_booking_discount_switch') == 1) {
		$discount = config('site.online_booking_discount');
	}

	$discount = ($rate * $discount / 100);

	return $discount;
}

function OnlineDiscountRate($rate = 0)
{
	$discount = 0;

	if (config('site.online_booking_discount_switch') == 1) {
		$discount = config('site.online_booking_discount');
	}

	$discount = $rate - ($rate * $discount / 100);

	return $discount;
}

function discount_show($id)
{
	$coupon = App\Models\Coupon::find($id);
	return $coupon->human_discount;
}

function random_color()
{
	return random_color_part() . random_color_part() . random_color_part();
}

function random_color_part()
{
	return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function heights()
{
	//yeh sedona ote k liye use ho rhi hain
	$arr = [];
	for ($i = 115; $i <= 196; $i += 1) {

		$arr[$i] = cm2feet($i);
	}

	//return $arr;
	return array_unique($arr);
}
function cm2feet($cm)
{
	$inches = $cm / 2.54;
	$feet = intval($inches / 12);
	$inches = $inches % 12;
	// ceil($inches = $inches%12);
	return sprintf("%d' %d''", $feet, $inches);

	if ($inches > 0) {
		return sprintf("%d ' %d ''", $feet, $inches);
	} else {
		return sprintf("%d '", $feet);
	}
}

function days_rate($product_id, $days, $is_hourly = 0)
{
	info("searcing in days rate. Product Id $product_id Day: $days");
	info("frontend folder= " . env('FRONTEND_VIEW_FOLDER'));

	if ($days == "NaN") {
		$days = 1; //its a rare scenario when during reservation start date entered and returned date is not entered yet.
	}

	if (config('site.rate_system') == 'by_days') {
		if ($days == 1) {
			$group_name = 'Full Day';
		} elseif ($days >= 2 && $days <= 4) {
			$group_name = '2 - 4 Days';
		} else {
			$group_name = '5+ Days';
		}

		$product_day_rate = ProductDayRate::query()
			->where('product_id', $product_id)
			->where('group_name', $group_name)
			->first();
		if (!$product_day_rate) {
			$product_day_rate = ProductDayRate::query()->first();
		}

		return $product_day_rate->rate;
	} elseif (config('site.rate_system') == 'by_days_for_all') {

		info("IN HELPER DAYS RATE FUNCTION LINE # 732: is_hourly: " . $is_hourly);
		if ($is_hourly) {
			if (env('FRONTEND_VIEW_FOLDER') == 'sodusbayoutfitters-frontend' || env('FRONTEND_VIEW_FOLDER') == 'blacktiesummer-frontend' || env('FRONTEND_VIEW_FOLDER') == 'powder-houndak-frontend') {
				info("checking for timeslot by day table where hourly slots are more than 1.");
				$timeslot_by_day_id = TimeslotByDay::where("duration", 'hours')->where('linked_normal_timeslot_id', $is_hourly)->first()->id;
			} else {
				$timeslot_by_day_id = TimeslotByDay::where("duration", 'hours')->first()->id;
			}
		} else {
			$timeslot_by_day_id = TimeslotByDay::whereRaw("($days between days_from and days_to)")->where("duration", '<>', 'hours')->first()->id;
		}

		$product_day_rate = ProductDayRate::query()
			->where('product_id', $product_id)
			->where('timeslot_by_day_id', $timeslot_by_day_id)
			->first();

		if (!$product_day_rate) {
			$product_day_rate = ProductDayRate::query()->first();
		}

		return $product_day_rate->rate;
	}
}

function first_day_plus_other_days($product_id, $days, $is_hourly = 0)
{
	$total_calculated_days = $days;
	if (config('site.fixed_rate_sub_question') == 'one_day_multidays') {

		//1ST DAY RATE + MULTIDAYS RATE (UP TO 6 DAYS) + FIXED 7TH DAY RATE

		if ($total_calculated_days == 1) {
			$amount = round_normal(days_rate($product_id, 1, $is_hourly));
		} elseif ($total_calculated_days == 2) {
			$first_day_rate = round_normal(days_rate($product_id, 1, $is_hourly));
			$seond_day_rate = round_normal(days_rate($product_id, 2, $is_hourly));

			$amount = $first_day_rate + $seond_day_rate;
		} elseif ($total_calculated_days >= 3 && $total_calculated_days <= 6) {
			$first_day_rate = round_normal(days_rate($product_id, 1, $is_hourly));
			$remaining_days = $total_calculated_days - 1;
			$remaining_days_rate = round_normal(days_rate($product_id, $remaining_days, $is_hourly));

			$amount = $first_day_rate + ($remaining_days_rate * $remaining_days);
		} elseif ($total_calculated_days == 7) {
			$seven_days_rate = round_normal(days_rate($product_id, 7, $is_hourly));
			$amount = ($seven_days_rate * $total_calculated_days);
		} elseif ($total_calculated_days > 7) {

			$seven_days_rate = round_normal(days_rate($product_id, 7, $is_hourly));
			//get per day rate from serven_day_rate
			//$per_day_from_seven_day=$seven_days_rate/7;
			$amount = ($seven_days_rate * $total_calculated_days);
		} else {
			$amount = round_normal(days_rate($product_id, $total_calculated_days, $is_hourly));
		}
	} elseif (config('site.fixed_rate_sub_question') == 'one_day_multidays_with_weekly') {

		//same like above, 1ST DAY RATE + MULTIDAYS RATE (UP TO 6 DAYS) + FIXED 7TH DAY RATE


		if ($total_calculated_days == 1) {
			$amount = round_normal(days_rate($product_id, 1, $is_hourly));
		} elseif ($total_calculated_days == 2) {
			$first_day_rate = round_normal(days_rate($product_id, 1, $is_hourly));
			$seond_day_rate = round_normal(days_rate($product_id, 2, $is_hourly));

			$amount = $first_day_rate + $seond_day_rate;
		} elseif ($total_calculated_days >= 3 && $total_calculated_days <= 6) {
			$first_day_rate = round_normal(days_rate($product_id, 1, $is_hourly));
			$remaining_days = $total_calculated_days - 1;
			$remaining_days_rate = round_normal(days_rate($product_id, $remaining_days, $is_hourly));

			$amount = $first_day_rate + ($remaining_days_rate * $remaining_days);
		} elseif ($total_calculated_days == 7) {
			$seven_days_rate = round_normal(days_rate($product_id, 7, $is_hourly));

			$amount = $seven_days_rate;
		} elseif ($total_calculated_days > 7) {

			$seven_days_rate = round_normal(days_rate($product_id, 7, $is_hourly));
			//get per day rate from serven_day_rate
			$per_day_from_seven_day = $seven_days_rate / 7;
			$amount = ($per_day_from_seven_day * $total_calculated_days);
		} else {
			$amount = round_normal(days_rate($product_id, $total_calculated_days, $is_hourly));
		}
	} elseif (config('site.fixed_rate_sub_question') == 'one_day_two_day_multidays') {

		if ($total_calculated_days == 1) {
			$amount = round_normal(days_rate($product_id, 1, $is_hourly));
		} elseif ($total_calculated_days == 2) {
			$first_day_rate = round_normal(days_rate($product_id, 1, $is_hourly));
			$seond_day_rate = round_normal(days_rate($product_id, 2, $is_hourly));

			$amount = $first_day_rate + $seond_day_rate;
		} elseif ($total_calculated_days == 3) {
			$first_day_rate = round_normal(days_rate($product_id, 1, $is_hourly));
			$seond_day_rate = round_normal(days_rate($product_id, 2, $is_hourly));
			$third_day_rate = round_normal(days_rate($product_id, 3, $is_hourly));

			$amount = $first_day_rate + $seond_day_rate + $third_day_rate;
		} elseif ($total_calculated_days > 3 &&  $total_calculated_days <= 6) {
			$first_day_rate = round_normal(days_rate($product_id, 1, $is_hourly)); // 39
			$seond_day_rate = round_normal(days_rate($product_id, 2, $is_hourly)); // 30
			$additional_days = $total_calculated_days - 2; //3-2=1
			$additional_days_rate = round_normal(days_rate($product_id, 3, $is_hourly)); // 39

			$amount = $first_day_rate + $seond_day_rate + ($additional_days_rate * $additional_days);
		} elseif ($total_calculated_days >= 7) {
			$seven_plus_days_rate = round_normal(days_rate($product_id, $total_calculated_days, $is_hourly));
			info("seven_plus_days_rate: $seven_plus_days_rate");
			info("total_calculated_days: $total_calculated_days");
			$amount = ($seven_plus_days_rate * $total_calculated_days);
		} else {
			$amount = round_normal(days_rate($product_id, $total_calculated_days, $is_hourly));
		}
	} elseif (config('site.fixed_rate_sub_question') == 'one_day_two_day_multidays_exclude_pervious') {

		info("searcing in rate: one_day_two_day_multidays_exclude_pervious");
		if ($total_calculated_days == 1) {
			$amount = round_normal(days_rate($product_id, 1, $is_hourly));
		} elseif ($total_calculated_days == 2) {
			$first_day_rate = round_normal(days_rate($product_id, 1, $is_hourly));
			$seond_day_rate = round_normal(days_rate($product_id, 2, $is_hourly));

			$amount = $first_day_rate + $seond_day_rate;
		} elseif ($total_calculated_days >= 3) {
			info("one_day_two_day_multidays_exclude_pervious: > = 3 condition");
			$amount = round_normal(days_rate($product_id, $total_calculated_days, $is_hourly)) * $total_calculated_days;
		} else {
			info("one_day_two_day_multidays_exclude_pervious: else condition");
			$amount = round_normal(days_rate($product_id, $total_calculated_days, $is_hourly));
		}
	}
	return $amount;
}

function calculate_days($start_date, $end_date)
{
	if (config('site.rate_system') == 'separat_for_week_n_weekend') {
		$workingDays = 0;
		$startTimestamp = strtotime($start_date);
		$endTimestamp = strtotime($end_date->updated_at);
		for ($i = $startTimestamp; $i <= $endTimestamp; $i = $i + (60 * 60 * 24)) {
			if (date("N", $i) <= 4) $workingDays = $workingDays + 1;
		}
		return $workingDays;
	}
	//Calculate Weekend Day
	else {
		$start_date = date('Y-m-d', strtotime($start_date));
		$end_date = date('Y-m-d', strtotime($end_date));
		if ($start_date == $end_date) {
			return 1;
		}

		$earlier = new DateTime($start_date);
		$later = new DateTime($end_date);
		return $diff = $later->diff($earlier)->format("%a") + 1;
	}
}
//Calculate Weekend Day
function calculate_weekend_days($start_date, $end_date)
{
	$weekendDays = 0;
	$startTimestamp = strtotime($start_date->created_at);
	$endTimestamp = strtotime($end_date->updated_at);
	for ($i = $startTimestamp; $i <= $endTimestamp; $i = $i + (60 * 60 * 24)) {
		if (date("N", $i) > 4) $weekendDays = $weekendDays + 1;
	}
	return $weekendDays;
}

function day_rates_groups()
{
	return ['Full Day', '2 - 4 Days', '5+ Days'];
}

function heights_list()
{
	$heights_list = array(
		"< 4' 3'' (130 cm)",
		"4' 4'' (132 cm)",
		"4' 5'' (135 cm)",
		"4' 6'' (137 cm)",
		"4' 7'' (140 cm)",
		"4' 8'' (142 cm)",
		"4' 9'' (145 cm)",
		"4' 10'' (147 cm)",
		"4' 11'' (150 cm)",
		"5' 0'' (152 cm)",
		"5' 1'' (155 cm)",
		"5' 2'' (157 cm)",
		"5' 3'' (160 cm)",
		"5' 4'' (163 cm)",
		"5' 5'' (165 cm)",
		"5' 6'' (168 cm)",
		"5' 7'' (170 cm)",
		"5' 8'' (173 cm)",
		"5' 9'' (175 cm)",
		"Select",
		"5' 10'' (178 cm)",
		"5' 11'' (180 cm)",
		"6' 0'' (183 cm)",
		"6' 1'' (185 cm)",
		"6' 2'' (188 cm)",
		"6' 3'' (191 cm)",
		"6' 4'' (193 cm)",
		"6' 5''+ (196 cm+)",

	);

	return $heights_list;
}

function weights_list()
{
	$weights_list = array(
		"< 70 lbs (32 kg)",

		"80 lbs (36 kg)",

		"90 lbs (41 kg)",

		"100 lbs (45 kg)",

		"110 lbs (50 kg)",

		"120 lbs (54 kg)",

		"130 lbs (59 kg)",

		"140 lbs (64 kg)",

		"150 lbs (68 kg)",

		"160 lbs (73 kg)",

		"170 lbs (77 kg)",

		"Select",

		"180 lbs (82 kg)",

		"190 lbs (86 kg)",

		"200 lbs (91 kg)",

		"210 lbs (95 kg)",

		"220 lbs (100 kg)",

		"230 lbs (104 kg)",

		"240 lbs (109 kg)",

		"250 lbs (113 kg)",

		"260 lbs (118 kg)",

		"270 lbs (122 kg)",

		"280 lbs (127 kg)",

		"290 lbs (132 kg)",

		"300 lbs (136 kg)",
	);
	return $weights_list;
}

function dates_btw_period($from, $to)
{
	if ($from == "" && $to == "") {
		return [];
	} else {
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$period = new DatePeriod(
			new DateTime($from),
			new DateInterval('P1D'),
			new DateTime($to)
		);

		$date = [];
		foreach ($period as $key => $value) {
			$date[] = $value->format('Y-m-d');
		}
		$date[] = $to;
		return $date;
	}
}

function free_checkout($coupon_id)
{
	$coupon = DB::table('coupons')->where('id', $coupon_id)->where('free_checkout_coupon', 1)->first();
	if ($coupon) {
		return true;
	} else {
		return false;
	}
}

function free_checkout_by_book_me($book_me_host_id)
{
	$book_me_host = DB::table('book_me_hosts')->where('id', $book_me_host_id)->where('skip_checkout', 1)->first();
	if ($book_me_host) {
		return true;
	} else {
		return false;
	}
}

function append_url($type)
{
	$request = request();
	$currentQueries = $request->query();

	//Declare new queries you want to append to string:
	$newQueries = ['status' => $type];

	//Merge together current and new query strings:
	$allQueries = array_merge($currentQueries, $newQueries);

	//Generate the URL with all the queries:
	$request->fullUrlWithQuery($allQueries);
}

function hours()
{
	return ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
}

function minutes()
{
	return ['00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55'];
}

function script_mode()
{
	$mode = "Rentals Only";
	if (config('site.script_mode') == 1) {
		$mode = "Rentals Only";
	}
	if (config('site.script_mode') == 2) {
		$mode = "Tours Only";
	}

	if (config('site.script_mode') == 3) {
		$mode = "Both";
	}

	return $mode;
}

function session_remaining_time()
{
	$session_start_time = session('tour_reservation.session_start_time');
	$time_for_tour_reservation_session = config('site.time_for_tour_reservation_session') != '' ? config('site.time_for_tour_reservation_session') : 10;
	$session_end_time_should_be = $session_start_time + (60 * $time_for_tour_reservation_session);
	$time_now = time();
	$remaining = $session_end_time_should_be - $time_now;

	if ($session_end_time_should_be > $time_now) {
		return [
			'session_expired' => false,
			'server_time_now' => date("h:i:s", $time_now),
			'session_start_time' => date("h:i:s", $session_start_time),
			'session_end_time_should_be' => date("h:i:s", $session_end_time_should_be),
			'time_for_tour_reservation_session' => $time_for_tour_reservation_session,
			'message' => "Time remaining to complete this reservation: " . gmdate("h:i:s", $remaining) . " (hours:minutes:seconds)",
		];
	} else {
		return [
			'session_expired' => true,
			'message' => date('i:s', $remaining),
		];
	}
}

function session_remaining_time_formatted()
{
	$session_start_time = session('tour_reservation.session_start_time');
	$session_end_time_should_be = $session_start_time + 300;

	//$remaining=$session_end_time_should_be-time();
	return date("Y-m-d H:i:s", $session_end_time_should_be);
}

function getMonthNames()
{
	$months = [];

	for ($i = 1; $i <= 12; $i++) {
		$months[$i] = get_month_sname_by_number($i);
	}

	return $months;
}
function land_tax_cal($amount)
{
	$land_tax_amount = 0;

	if (config('site.enable_land_tax') && ((config('site.land_tax_type') == 'rentals' || config('site.land_tax_type') == 'both'))) {
		$tax_percentage = config('site.land_tax_price');
		$land_tax_amount = $amount * $tax_percentage / 100;
	}
	return round_normal($land_tax_amount);
}

function get_month_name_by_number($month_number)
{
	//yeh full name return karta hai
	$dateObj = DateTime::createFromFormat('!m', $month_number);
	return $month = $dateObj->format('F'); // March
}
function get_month_sname_by_number($month_number)
{
	//yeh short name return karta hai
	$dateObj = DateTime::createFromFormat('!m', $month_number);
	return $month = $dateObj->format('M'); // March
}

function tour_category($tour_category_id)
{
	$tour_category = TourCategory::find($tour_category_id);
	return strtolower($tour_category->title);
}

function get_date_range_with_selected_days($start_date, $end_date, $days = [])
{
	$start = $start_date;
	$end = $end_date;

	$period = floor((strtotime($end) - strtotime($start)) / (24 * 60 * 60));

	$dates = [];

	for ($i = 0; $i <= $period; $i++) {
		//if(in_array(date('l',strtotime("$start +$i day")),$days)) yeh day name se search karta hai like ['Monday','Tuesday']
		if (in_array(date('l', strtotime("$start +$i day")), $days)) {
			$dates[] = date('Y-m-d', strtotime("$start +$i day")); //yeh day number se search karta hai like [1,2]
		}
	}

	return $dates;
}

function days_array()
{
	return [
		'Monday',
		'Tuesday',
		'Wednesday',
		'Thursday',
		'Friday',
		'Saturday',
		'Sunday',
	];
}

function get_day_sname_by_number($day_number)
{
	$days_array = days_array();
	if (array_key_exists($day_number, $days_array)) {
		return $days_array[$day_number];
	} else {
		return "Invalid Number";
	}
}

function waiver_status_by_number($reservee_id, $person_number, $is_driver = 0)
{
	return DB::table('reservee_waivers')->where('reservee_id', $reservee_id)->where('person_number', $person_number)->where('is_driver', $is_driver)->first();
}

function underscore_to_space($str)
{
	return ucwords(str_replace("_", " ", $str));
}


function term_of_use_page_content()
{
	$term_of_use_page = StaticPage::where('title', 'Terms of Use')->first();
	if ($term_of_use_page) {
		return $term_of_use_page->content;
	}
}

function terms_accept_date()
{
	if (!config('site.ask_for_term_of_use_accepted')) {
		//it means old customer, so acceptance date should be 1/1/2020
		return "1/1/2020";
	} else {
		return config('site.term_of_use_accepted_datetime');
	}
}

function calculate_chaffee_tax($amount)
{
	return to_currency($amount * CHAFFEE_TAX / 100, 0);
}
function calculate_colorado_tax_amount($days)
{
	return to_currency($days * COLORADO_TAX_AMOUNT, 0);
}
function calculate_national_forest_fee($amount)
{
	return to_currency($amount * config('site.national_forest_fee') / 100, 0);
}
function calculate_red_cliff_fee($amount)
{
	return to_currency($amount * config('site.red_cliff_fee') / 100, 0);
}
function get_buddy_quote($days_count)
{
	//return 11.92;
	$amount = 0;


	try {
		$url = "https://partners.buddyinsurance.com/v1/quote?partnerID=" . config('buddy.partner_id') . "&who=me&state=AZ&numberOfDays=$days_count";

		info("Buddy quote URL: $url");

		$quotes_json = file_get_contents($url);

		info("Buddy quote RESPONSE: $quotes_json");

		$quotes = json_decode($quotes_json);

		return $quotes->pricing;
	} catch (Exception $e) {
		return 0;
	}
}

function to_percentage($value, $symbol = true)
{
	$value = str_replace(',', '', $value);
	$value = (float) $value;
	if ($symbol) {
		return round($value, 2) . "%";
	} else {
		return round($value, 2);
	}
}

function run_migrations()
{



	info('EXCEPTION HANDLING DB: AUTO MIGRATING');
	//$output = new \Symfony\Component\Console\Output\BufferedOutput; 
	Artisan::call('migrate', [], null);

	info('EXCEPTION HANDLING DB: AUTO MIGRATING DONE');
}

function products_has_waiver_on_checkout($ids)
{
	foreach (DB::table('products')->whereIn('id', $ids)->get() as $product) {
		if (@$product->waiver_on_checkout) {
			return true;
		}
	}
	return false;
	// $has_waiver_on_checkout = true;
	// $products = DB::table('products')->whereIn('id',$ids)->get();
	// foreach ($products as $product) {
	// 	if (!@$product->waiver_on_checkout) {
	// 		$has_waiver_on_checkout = false;
	// 		break;
	// 	}
	// }
	// return $has_waiver_on_checkout;
}

function site_disabled_dates_arr()
{
	if (config('site.disabled_dates') && !empty(config('site.disabled_dates'))) {
		$site_disabled_dates = explode(",", config('site.disabled_dates'));
	} else {
		$site_disabled_dates = [];
	}

	return $site_disabled_dates;
}

function check_in_disabled_dates($start_date, $end_date)
{
	//******** RETURN false MEANS ANY DISABLED DATE LIES B/W SELECTED RANGE*****////
	//******** RETURN true MEANS RANGE DOES NOT CONTAIN ANY DISABLED DATE*****////
	$result = true;

	if (config("site.allow_if_disabled_date_is_in_booking_range")) {
		//normall agar disabled date selected date range k darmiyaan me ho to system booking allow ni karta. Lekin is setting se yeh check skip krein gay. Like Glacier wants to block sunday, but wants allow users to pickup on Saturday and Pickup on Monday.
		return true;
	}


	$from_admin = \Request::route()->getName() == "bikes.booking.step1" ? false : true;

	if ($from_admin && !config("site.disable_dates_on_admin_res")) {
		//mean agar admin reservation ho or settting me disable dates on admin off ho to ham ne disabled dates me check ni karna.
		//dd("jere");
		return $result;
	}

	if (config('disbale_date_dont_check_in_range')) {
	}

	$res_days = dates_btw_period($start_date, $end_date); //get every date of reservation.

	$site_disabled_dates_arr = site_disabled_dates_arr();



	if (is_array($site_disabled_dates_arr) && count($site_disabled_dates_arr) > 0) {
		foreach ($res_days as $res_day) {
			if (in_array(to_date($res_day), $site_disabled_dates_arr)) {
				$result = false;
				break;
			}
		}
	}

	return $result;
}
function check_in_disabled_dates_for_tours($start_date, $end_date)
{
	$result = true;
	$res_days = dates_btw_period($start_date, $end_date); //get every date of reservation.

	$site_disabled_dates_arr = site_disabled_dates_arr();
	if (is_array($site_disabled_dates_arr) && count($site_disabled_dates_arr) > 0) {
		foreach ($res_days as $res_day) {
			if (in_array(to_date($res_day), $site_disabled_dates_arr)) {
				$result = false;
				break;
			}
		}
	}

	return $result;
}

function frontend_booking_price($amount_options_array)
{
	if (config('site.online_booking_fee_switch')) {
		$booking_fee = config('site.online_booking_fee');
		$amount = $amount_options_array['amount'];
		$amount = $amount * $booking_fee / 100;
		return $amount;
	} else {
		return 0;
	}
}
function get_countries()
{

	$countries = array(
		'Afghanistan',
		'Albania',
		'Algeria',
		'American Samoa',
		'Andorra',
		'Angola',
		'Anguilla',
		'Antarctica',
		'Antigua and Barbuda',
		'Argentina',
		'Armenia',
		'Aruba',
		'Australia',
		'Austria',
		'Azerbaijan',
		'Bahamas',
		'Bahrain',
		'Bangladesh',
		'Barbados',
		'Belarus',
		'Belgium',
		'Belize',
		'Benin',
		'Bermuda',
		'Bhutan',
		'Bolivia',
		'Bosnia and Herzegowina',
		'Botswana',
		'Bouvet Island',
		'Brazil',
		'British Indian Ocean Territory',
		'Brunei Darussalam',
		'Bulgaria',
		'Burkina Faso',
		'Burundi',
		'Cambodia',
		'Cameroon',
		'Canada',
		'Cape Verde',
		'Cayman Islands',
		'Central African Republic',
		'Chad',
		'Chile',
		'China',
		'Christmas Island',
		'Cocos (Keeling) Islands',
		'Colombia',
		'Comoros',
		'Congo',
		'Congo, the Democratic Republic of the',
		'Cook Islands',
		'Costa Rica',
		'Cote d\'Ivoire',
		'Croatia (Hrvatska)',
		'Cuba',
		'Cyprus',
		'Czech Republic',
		'Denmark',
		'Djibouti',
		'Dominica',
		'Dominican Republic',
		'East Timor',
		'Ecuador',
		'Egypt',
		'El Salvador',
		'Equatorial Guinea',
		'Eritrea',
		'Estonia',
		'Ethiopia',
		'Falkland Islands (Malvinas)',
		'Faroe Islands',
		'Fiji',
		'Finland',
		'France',
		'France Metropolitan',
		'French Guiana',
		'French Polynesia',
		'French Southern Territories',
		'Gabon',
		'Gambia',
		'Georgia',
		'Germany',
		'Ghana',
		'Gibraltar',
		'Greece',
		'Greenland',
		'Grenada',
		'Guadeloupe',
		'Guam',
		'Guatemala',
		'Guinea',
		'Guinea-Bissau',
		'Guyana',
		'Haiti',
		'Heard and Mc Donald Islands',
		'Holy See (Vatican City State)',
		'Honduras',
		'Hong Kong',
		'Hungary',
		'Iceland',
		'India',
		'Indonesia',
		'Iran (Islamic Republic of)',
		'Iraq',
		'Ireland',
		'Israel',
		'Italy',
		'Jamaica',
		'Japan',
		'Jordan',
		'Kazakhstan',
		'Kenya',
		'Kiribati',
		'Korea, Democratic People\'s Republic of',
		'Korea, Republic of',
		'Kuwait',
		'Kyrgyzstan',
		'Lao, People\'s Democratic Republic',
		'Latvia',
		'Lebanon',
		'Lesotho',
		'Liberia',
		'Libyan Arab Jamahiriya',
		'Liechtenstein',
		'Lithuania',
		'Luxembourg',
		'Macau',
		'Macedonia, The Former Yugoslav Republic of',
		'Madagascar',
		'Malawi',
		'Malaysia',
		'Maldives',
		'Mali',
		'Malta',
		'Marshall Islands',
		'Martinique',
		'Mauritania',
		'Mauritius',
		'Mayotte',
		'Mexico',
		'Micronesia, Federated States of',
		'Moldova, Republic of',
		'Monaco',
		'Mongolia',
		'Montserrat',
		'Morocco',
		'Mozambique',
		'Myanmar',
		'Namibia',
		'Nauru',
		'Nepal',
		'Netherlands',
		'Netherlands Antilles',
		'New Caledonia',
		'New Zealand',
		'Nicaragua',
		'Niger',
		'Nigeria',
		'Niue',
		'Norfolk Island',
		'Northern Mariana Islands',
		'Norway',
		'Oman',
		'Pakistan',
		'Palau',
		'Panama',
		'Papua New Guinea',
		'Paraguay',
		'Peru',
		'Philippines',
		'Pitcairn',
		'Poland',
		'Portugal',
		'Puerto Rico',
		'Qatar',
		'Reunion',
		'Romania',
		'Russian Federation',
		'Rwanda',
		'Saint Kitts and Nevis',
		'Saint Lucia',
		'Saint Vincent and the Grenadines',
		'Samoa',
		'San Marino',
		'Sao Tome and Principe',
		'Saudi Arabia',
		'Senegal',
		'Seychelles',
		'Sierra Leone',
		'Singapore',
		'Slovakia (Slovak Republic)',
		'Slovenia',
		'Solomon Islands',
		'Somalia',
		'South Africa',
		'South Georgia and the South Sandwich Islands',
		'Spain',
		'Sri Lanka',
		'St. Helena',
		'St. Pierre and Miquelon',
		'Sudan',
		'Suriname',
		'Svalbard and Jan Mayen Islands',
		'Swaziland',
		'Sweden',
		'Switzerland',
		'Syrian Arab Republic',
		'Taiwan, Province of China',
		'Tajikistan',
		'Tanzania, United Republic of',
		'Thailand',
		'Togo',
		'Tokelau',
		'Tonga',
		'Trinidad and Tobago',
		'Tunisia',
		'Turkey',
		'Turkmenistan',
		'Turks and Caicos Islands',
		'Tuvalu',
		'Uganda',
		'Ukraine',
		'United Arab Emirates',
		'United Kingdom',
		'United States',
		'United States Minor Outlying Islands',
		'Uruguay',
		'Uzbekistan',
		'Vanuatu',
		'Venezuela',
		'Vietnam',
		'Virgin Islands (British)',
		'Virgin Islands (U.S.)',
		'Wallis and Futuna Islands',
		'Western Sahara',
		'Yemen',
		'Yugoslavia',
		'Zambia',
		'Zimbabwe',
	);
	return $countries;
}

function markErrors($errors, $htmlName, $returnIfErrors = 'has-error')
{
	if (is_array($htmlName)) {
		foreach ($htmlName as $n) {
			if ($errors->has($n)) {
				return $returnIfErrors;
			}
		}

		return '';
	}

	return $errors->has($htmlName) ? $returnIfErrors : '';
}
function getAllAdminAndStaff()
{
	$users = \App\User::whereIn('type', ['admin', 'staff'])->get();
	return $users;
}
function randString($length = 5)
{
	return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
function getUrlsTransferInventory()
{
	$locations = [];

	if (env('STORE_CLIENT_NAME') == 'demo') {
		array_push($locations, env('DEMO_STORE_URL', 'http://ski.test'));
	}

	return $locations;
}
function count_age($birthDate)
{
	$birthDate = Carbon::parse($birthDate)->format('m/d/Y');
	$birthDate = explode("/", $birthDate);
	//get age from date or birthdate
	$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
		? ((date("Y") - $birthDate[2]) - 1)
		: (date("Y") - $birthDate[2]));
	return $age;
}
function checkReserveePickup($reservation_id)
{
	$reservation = Reservation::find($reservation_id);
	$reservee = Reservee::where('reservation_id', $reservation->id)->where('has_picked_up', 1)->get();
	if ($reservee->count() == 0) {
		return 0;
	}
	if ($reservee->count() > 0 && $reservee->count() < $reservee->count()) {
		return 1;
	}
	// if (count($reservation->reservees) == $reservee->count())
	// {
	// 	$reservation->has_picked_up = 1;
	// 	$reservation->save();
	// 	return true;
	// }
	// else{
	// 	return false;
	// }
}



function calculateWeekendRate($start_date, $end_date, $product_slot_rate)
{
	$day_of_week = date("N", strtotime($start_date));
	$week_days_count = 0;
	$weekend_days_count = 0;

	if (is_null($end_date)) {
		//only check from day if its weekend/
		if ($day_of_week >= 5) {
			$weekend_days_count++;
		} else {
			$week_days_count++;
		}
	} else {
		$dates_btw_period = dates_btw_period($start_date, $end_date, []);
		foreach ($dates_btw_period as $date_btw_period) {
			$day_of_week = date("N", strtotime($date_btw_period));
			if ($day_of_week >= 5) {
				$weekend_days_count++;
			} else {
				$week_days_count++;
			}
		}
	}


	$prodcut_amount = round_normal($product_slot_rate->rate * $week_days_count);
	$prodcut_weekend_amount = round_normal($product_slot_rate->weekend_rate * $weekend_days_count);

	return $prodcut_amount = $prodcut_amount + $prodcut_weekend_amount;
}

function includes_weekend_new($fromDate, $toDate)
{
	//Get current day of week. For example Friday = 5

	$day_of_week = date("N", strtotime($fromDate));

	if (is_null($toDate)) {
		//only check from day if its weekend/
		if ($day_of_week >= 5) {
			return "single date is weekend";
		} else {
			return "single date is NOT weekend";
		}
	}


	$days = $day_of_week + (strtotime($toDate) - strtotime($fromDate)) / (60 * 60 * 24);
	//strtotime(...) - convert a string date to unixtimestamp in seconds.
	//The difference between strtotime($toDate) - strtotime($fromDate) is the number of seconds between this 2 dates. 
	//We divide by (60*60*24) to know the number of days between these 2 dates (60 - seconds, 60 - minutes, 24 - hours)
	//After that add the number of days between these 2 dates to a day of the week. So if the first date is Friday and days between these 2 dates are: 3 the sum will be 5+3 = 8 and it's bigger than 6 so we know it's a weekend between.



	if ($days >= 5) {
		//we have a weekend. Because day of week of first date + how many days between this 2 dates are greater or equal to 6 (6=Saturday)
		return "We have weekend;";
	} else {
		return "We DONT have weekend;";
		//we don't have a weekend
	}
}

function get_product_slot($product_time_slot_rate_id)
{
	return $product_slot_rate = ProductTimeSlotRate::find($product_time_slot_rate_id);
}
function getReserveeAfterDiscount($id, $reservee_amount)
{
	$reservation = Reservation::find($id);
	$admin_total = 0;
	$online_total = 0;
	if (isset($reservation->admin_discount) && $reservation->admin_discount > 0) {
		$admin_total = $reservee_amount * $reservation->admin_discount / 100;
	}
	$online_total = (isset($reservation->added_from) && $reservation->added_from == 'frontend' && !$reservation->from_kiosk) ? OnlineDiscount($reservee_amount) : 0;
	$total_amount = $reservee_amount - $admin_total - $online_total;
	return to_currency($total_amount);
}
function addons_total($id)
{
	$reservee = Reservee::find($id);
	$total = 0;
	foreach ($reservee->reservee_addons as $addon) {
		if ($addon->addon) {
			if (config('site.buddy_api') && $addon->addon_id == config('site.buddy_addon_id')  && $addon->addon_qty > 0) {
				$addon_price = $addon->buddy_addon_price;
				$total = $total + $addon_price;
			} else {
				if (strtolower($addon->addon->type) == 'per day' && !$addon->addon->percent_of_rental_rate) {
					// !percent_of_rental_rate wali condition is liye lagayi hai. k ham percentage wali price ko each day se multiply ni karna. ham reservee me product amount already days se multiple kar k save karty hain. for example agar product ki 1 day price 95 hai to reservee me 285 save hoga for 3 days. And 1% 285 pe calculate hoga yani total 3 days ki price pe. SO NO NEED TO MULTIPLY :) 


					if ($reservee->reservation->total_days > 1) {
						$total = $total + ($addon->calculate_reservee_addon_price() * @$addon->addon_qty * $reservee->reservation->total_days);
					} else {
						$total = $total + ($addon->calculate_reservee_addon_price() * @$addon->addon_qty * 1); // if days are less then 1 suppose days count is 1.
					}
				} else {
					$total = $total + ($addon->calculate_reservee_addon_price() * @$addon->addon_qty);
				}
			}
		}
	}
	return $total;
}

function requestHasBuddy($request)
{
	$has_buddy = false;
	if ($request->addons) {
		foreach ($request->addons as $request_addon_index => $request_addon) {

			if (config('site.buddy_api') && $request_addon == config('site.buddy_addon_id')  && $request->addon_qty[$request_addon_index] > 0) {
				$has_buddy = true;
			}
		}
	}

	return $has_buddy;
}
function onlyYear($date)
{

	return date('Y', strtotime($date));
}
function permanentAddons()
{
	if (config('site.permanent_addon')) {
		$addon = Addon::find(config('site.permanent_addon_id'));

		if ($addon) {
			return $addon->title;
		} else {
			$addons_detail = null;
		}
		// $addons_detail =  "<div>$addon->title<input type='checkbox' name='reservee_addon[]' checked></div>";
	} else {
		$addons_detail = null;
	}
	return $addons_detail;
}
function checkDeliveryDetails($start_date, $end_date)
{
	if (config('site.enable_delivery_setting')) {
		if (config('site.delivery_night_before')) {
			$reservations = Reservation::select('reservations.*')
				->where('is_cancelled', 0)
				->where('delivery_status', 1)
				->where('delivery_preferred_time', 1)
				->join('reservees', 'reservations.id', 'reservees.reservation_id')
				->where(function ($q) use ($start_date, $end_date) {
					$q->whereBetween('reservees.start_date', [$start_date, $end_date]);
				})->distinct()->pluck('delivery_time')->all();
			$data['delivery_night_before'] = array_diff(config('site.delivery_night_before'), $reservations);
		} else {
			$data['delivery_night_before'] = [];
		}
		if (config('site.delivery_day_of')) {
			$day_reservations = Reservation::select('reservations.*')
				->where('is_cancelled', 0)
				->where('delivery_status', 1)
				->where('delivery_preferred_time', 0)
				->join('reservees', 'reservations.id', 'reservees.reservation_id')
				->where(function ($q) use ($start_date, $end_date) {
					$q->whereBetween('reservees.start_date', [$start_date, $end_date]);
				})->distinct()->pluck('delivery_time')->all();
			$data['delivery_day_of'] = array_diff(config('site.delivery_day_of'), $day_reservations);
		} else {
			$data['delivery_day_of'] = [];
		}

		return $data;
	}
}

function getTimeslotIdFromSession()
{
	$time_slot_id = null;

	if ($time_slot = session('time_slots')) {
		$time_slot_booking_hr_id = $time_slot->time_slot_booking_hr_id;
		$time_slot_booking_hr = DB::table('time_slot_booking_hrs')->where('id', $time_slot_booking_hr_id)->first();
		if ($time_slot_booking_hr) {
			$time_slot_id = $time_slot_booking_hr->time_slot_id;
		}
	}

	return $time_slot_id;
}
function count_reservations($email)
{
	$reservations = Reservation::where('is_cancelled', 0)
		->where('email', $email)->count();

	return $reservations;
}
// function checkDayOfTimeslot($hour)
// {
// 	return "Done";
// 	if($hour->pickup_time >= "10:00:00")
// 	{
// 		if($bookHour->pickup_time < '10:00:00') {
// 			unset($book_hours[$i]);
// 		}
// 	}

// 	elseif($day == 'Sat')
// 	{
// 		if($bookHour->pickup_time < '09:00:00') {
// 			unset($book_hours[$i]);
// 		}
// 	}

// 	elseif($day == 'Sun')
// 	{
// 		if($bookHour->pickup_time < '12:00:00') {
// 			unset($book_hours[$i]);
// 		}
// 	}
// }
if (! function_exists('isMobileDevice')) {
	function isMobileDevice()
	{
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
}
function connectedLightSpeedCategory($id)
{
	$categories = Category::where('lightspeed_id', $id)->get();
	$html = "";
	foreach ($categories as $key => $category) {
		if ($key == 0) {
			$html .= $category->name;
		} else {
			$html .=  ',' . $category->name;
		}
	}
	return $html;
}

function connectedLightSpeedProduct($id)
{
	$products = Product::where('lightspeed_id', $id)->get();
	$html = "";
	foreach ($products as $key => $product) {
		if ($key == 0) {
			$html .= $product->title;
		} else {
			$html .=  ',' . $product->title;
		}
	}
	return $html;
}

if (! function_exists('allowedImageTypes')) {
	function allowedImageTypes()
	{
		return '.jpg, .jpeg, .webp, .png, .ico';
	}
}

function nl2zero(Request $request, $column)
{
	$value  = $request->get($column);
	return ($value !== null) ? $value : 0;
}

function states()
{
	return array(
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'District Of Columbia',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
	);
}

function number_format_without_comma($value = '')
{
	if (!empty($value)) {
		$value = number_format($value, 2);
		$value = str_replace(',', '', $value);
		return $value;
	}
}

function getCurrentSeason($from_date = null, $to_date = null)
{
	$from_date = $from_date ? strtotime($from_date) : strtotime(date('y-m-d'));
	$to_date = $to_date ? strtotime($to_date) : strtotime(date('y-m-d'));

	$seasons = Season::all();
	foreach ($seasons as $season) {
		$seasonStartDate = date('y') . '-' . $season->from_month . '-' . $season->from_day;
		$seasonStartDate = strtotime($seasonStartDate);

		$seasonEndDate = date('y') . '-' . $season->to_month . '-' . $season->to_day;
		$seasonEndDate = strtotime($seasonEndDate);

		if ($season->from_month > $season->to_month) {
			$seasonEndDate = date('y') + 1 . '-' . $season->to_month . '-' . $season->to_day;
			$seasonEndDate = strtotime($seasonEndDate);
		}
		if ($from_date >= $seasonStartDate && $to_date <= $seasonEndDate) {
			return  $season;
		}
	}

	return null;
}

function numberOfDays( $first_date, $second_date )
{
    $datePickup = new \Carbon\Carbon($first_date);
    $dateReturn = new \Carbon\Carbon($second_date);
   
    return $datePickup->diffInDays($dateReturn) ;
}

function seasonRate($property, $season_id , $number_of_days )
{     
    $season =  $property->seasons()->find( $season_id );
    
    if($season!=null)
    {
        try {
            $rate = 0;
            if($number_of_days <=7) {
              $rate = $season->pivot->daily_rate;
            }
            if($number_of_days >= 8) {
               $rate = $season->pivot->weekly_rate / 7;
                if ( $season->pivot->weekly_rate == '' || $season->pivot->weekly_rate == 0) {
                   $rate =   $season->pivot->daily_rate;
                }
            }

             if($number_of_days >= 30) {
               $rate = $season->pivot->monthly_rate / 30;
                if ($season->pivot->monthly_rate == '' || $season->pivot->monthly_rate == 0) {
                   $rate = $season->pivot->daily_rate;
                }
            }

        } catch (Exception $e) {
           $response = [
                'status' => 'error',
                'error' => 'This property is not availalbe in this season.',
            ];
            return response()->json($response,200);
        }
    } else {
       $rate = '0';
    }
    return  $rate; 
}