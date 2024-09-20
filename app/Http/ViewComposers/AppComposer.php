<?php

namespace App\Http\ViewComposers;

use Carbon\Carbon;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class AppComposer
{
	/**
	 * Retrieve and cache static pages
	 */
	protected function staticPages()
	{
		$pages_to_skip = [];
		array_push($pages_to_skip, 'Home Page bottom section');
		if (!config('site.special_offer_page')) {
			array_push($pages_to_skip, 'Special Offer');
		}

		if (config('site.use_two_waivers')) {
			array_push($pages_to_skip, 'Waiver');
		} else {
			array_push($pages_to_skip, 'Driver Waiver');
			array_push($pages_to_skip, 'Rider Waiver');
		}

		if (!config('site.cancellation_policy_term')) {
			array_push($pages_to_skip, 'Cancellation Policy');
		}
		if (!config('site.enable_tech_station')) {
			array_push($pages_to_skip, 'Ski / Snowboard Cautiously');
			array_push($pages_to_skip, 'Ski / Snowboard Moderately');
			array_push($pages_to_skip, 'Ski / Snowboard Aggressively');
		}

		if ((@Auth::user()->email != 'marc@rezosystems.com' && @Auth::user()->email != 'programmer@rezosystems.com')) {
			array_push($pages_to_skip, 'Terms of Use');
		}
		if (config('site.enable_product_base_waivers')) {
			array_push($pages_to_skip, 'Waiver');
		}


		return Cache::remember('pages_static', Carbon::now()->addMinutes(15), function () use ($pages_to_skip) {
			$query = \App\StaticPage::select(['id', 'title']);
			return $query = $query->whereNotIn('title', $pages_to_skip)->get();
		});
	}

	/**
	 * Retrieve and cache settings pages
	 */
	protected function settingsPages()
	{
		return Cache::remember('pages_settings', Carbon::now()->addMinutes(15), function () {
			return \App\Setting::select(['id', 'key', 'title'])->get();
		});
	}

	protected function default_dates_string()
	{
		if (config('site.reports_default_dates') == 'current_month') {
			$default_from_date = date('m/d/Y', strtotime('first day of this month'));
			$default_to_date = date('m/d/Y', strtotime('last day of this month'));
		} else if (config('site.reports_default_dates') == 'previous_month') {
			$default_from_date = date('m/d/Y', strtotime('first day of previous month'));
			$default_to_date = date('m/d/Y', strtotime('last day of previous month'));
		} else if (config('site.reports_default_dates') == 'last_fifteen_days') {
			/*last 15 days*/
			$default_from_date = date("m/d/Y", strtotime("-15 day"));;
			$default_to_date = date('m/d/Y');
		} else if (config('site.reports_default_dates') == 'last_thirty_days') {
			/*last 30 days*/
			$default_from_date = date("m/d/Y", strtotime("-30 day"));;
			$default_to_date = date('m/d/Y');
		} else if (config('site.reports_default_dates') == 'current_week') {
			/*last 30 days*/
			$default_from_date = date("m/d/Y", strtotime("-7 day"));;
			$default_to_date = date('m/d/Y');
		} else {
			/*last month- By default*/
			$default_from_date = date('m/d/Y', strtotime('first day of previous month'));
			$default_to_date = date('m/d/Y', strtotime('last day of previous month'));
		}

		return "?start_date=$default_from_date&end_date=$default_to_date";
	}

	protected function tomorrow_dates_string()
	{

		/*last month- By default*/
		$default_from_date = date('m/d/Y', strtotime('+1 Day'));
		$default_to_date = date('m/d/Y', strtotime('+1 Day'));


		return "?start_date=$default_from_date&end_date=$default_to_date";
	}

	public function todays_date_string()
	{
		$default_from_date = date('m/d/Y');
		$default_to_date = date('m/d/Y');
		return "?start_date=$default_from_date&end_date=$default_to_date";
	}

	public function people_title()
	{
		return config('site.people_title') != "" ? config('site.people_title') : "Passengers";
	}



	/**
	 * Retrieve and cache notifications
	 */




	/**
	 * Share variables with views
	 *
	 * @param View $view
	 */
	public function compose(View $view)
	{
		$view->with([
			'default_dates_string'              => $this->default_dates_string(),
			'todays_date_string'              => $this->todays_date_string(),
			'tomorrow_dates_string'              => $this->tomorrow_dates_string(),
			'pages_static'              => $this->staticPages(),
			'pages_settings'            => $this->settingsPages(),
			'people_title'     => $this->people_title(),
		]);
	}
}
