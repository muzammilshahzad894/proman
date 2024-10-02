<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

include __DIR__ . '/aritsan_routes.php';

// Clear application cache:
Route::get('/clear-cache', function () {
	Artisan::call('cache:clear');
	dd("cache has been cleared");
});

// Clear application cache & config:
Route::get('/clear-cache-config', function () {
	Artisan::call('cache:clear'); //php artisan cache:clear
	Artisan::call('config:cache');
	Artisan::call('view:clear');
	dd("cache-config has been cleared");
});

Route::get('get-template-name', function () {
	dd(env("FRONTEND_VIEW_FOLDER"));
});

Route::get('app-env', function () {
	dd(env("APP_ENV"));
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {

	Route::get('logs', 'LogViewerController@index');
});

Route::get('auth/login', function () {
	return redirect('login');
});

Route::get('reset_fonts_dir', function () {
	$path = storage_path() . '/fonts';
	File::deleteDirectory($path);
	File::isDirectory($path) or File::makeDirectory($path, 0775, true, true);
});

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');


// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/request', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// If profile pic not found will show default pic
Route::get('/storage/products/{filename}', function ($filename) {
	return response(file_get_contents('./img/default.jpg'))->header('Content-Type', 'image/png');
});

// route for handling ajax file deletion calls
Route::delete('static_page/{page_id}/file/{file_id}', [
    'as' => 'static_page.destroy_file',
    'uses' => 'StaticPageController@destroy_file',
]);
Route::resource('static_page', 'StaticPageController', [
    'only' => ['edit', 'update'],
]);

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
|
| Here you can register web routes for admin role
|	, 'middleware' => ['auth', 'role:admin']
 */

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:admin|Guide|staff']], function () {

	Route::get('dashboard', 'DashboardController@index')->name('dashboard');;

	Route::resource('users', 'AdminController');
    Route::get('test_gateway_connection', 'SettingController@test_gateway_connection');
	Route::resource('email_templates', 'EmailTemplateController');
	//-------------------- End ------------------------------------//

	// settigns routes group
	Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {

		Route::post('/update_term_of_use_accepted', 'SettingController@update_term_of_use_accepted')->name('update_term_of_use_accepted');

		Route::get('general', 'SettingController@showGeneral')->name('general');
		Route::post('general', 'SettingController@updateGeneral')->name('general');

		Route::get('programmer', 'SettingController@showProgrammerSettings')->name('programmer_settings');
		Route::get('/7/edit', 'SettingController@showProgrammerSettings')->name('programmer_settings');
		Route::post('programmer', 'SettingController@updateProgrammerSettings')->name('programmer_settings');
		Route::get('mail', 'SettingController@showMail')->name('mail');
		Route::post('mail', 'SettingController@updateMail')->name('mail');
		Route::post('mail_driver', 'SettingController@updateMailDriver')->name('mail_driver');

		Route::post('send-email', 'SettingController@sendEmail')->name('send-email');

		Route::get('gateway', 'SettingController@showGateway')->name('gateway');
		Route::get('admin-gateway', 'SettingController@showGatewayAdmin')->name('gateway-admin');
		Route::post('gateway', 'SettingController@updateGateway')->name('gateway');
		Route::post('gateway_mode_toggle', 'SettingController@gateway_mode_toggle')->name('gateway_mode_toggle');

		Route::get('rezo_gateway', 'SettingController@showRezoGateway')->name('rezo_gateway');
		Route::post('rezo_gateway', 'SettingController@updateRezoGateway')->name('rezo_gateway');
		Route::post('rezo_gateway_mode_toggle', 'SettingController@rezo_gateway_mode_toggle')->name('rezo_gateway_mode_toggle');

		Route::delete('{setting_id}/email/{email?}', [
			'as' => 'destroy_email',
			'uses' => 'SettingController@destroy_email',
		]);

		Route::get('site_settings', 'SettingController@siteSetting')->name('site');
		Route::post('site_settings', 'SettingController@storeSiteSetting')->name('store.site');
		Route::post('site_toggle_sidebar_pin', 'SettingController@site_toggle_sidebar_pin')->name('store.site_toggle_sidebar_pin');

		Route::get('twilio', 'SettingController@twilioSetting')->name('twilio');
		Route::post('twilio', 'SettingController@storeTwilioSetting')->name('store.twilio');
		Route::post('twilio_setting', 'SettingController@updateTwilioSetting')->name('update.twilio');
		Route::post('confirm_password', 'SettingController@confirm_password')->name('confirm_password');
		//SMS Setting
		Route::get('sms', 'SettingController@smsSetting')->name('sms');
		Route::post('sms', 'SettingController@storeSmsSetting')->name('store.sms');
		Route::post('sms_setting', 'SettingController@updateSmsSetting')->name('update.sms');

		Route::get('buddy', 'SettingController@buddySetting')->name('buddy');
		Route::post('buddy', 'SettingController@storebuddySetting')->name('store.buddy');
	});
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function() {
	Route::get('amenities', 'AmenityController@index')->name('amenities.index');
	Route::get('amenities/create', 'AmenityController@create')->name('amenities.create');
	Route::post('amenities', 'AmenityController@store')->name('amenities.store');
	Route::get('amenities/{id}/edit', 'AmenityController@edit')->name('amenities.edit');
	Route::post('amenities/{id}/edit', 'AmenityController@update')->name('amenities.update');
	Route::delete('amenities/delete/{id}', 'AmenityController@destroy')->name('amenities.destroy');
	
	Route::get('types', 'TypeController@index')->name('types.index');
	Route::get('type/create', 'TypeController@create')->name('type.create');
	Route::post('type', 'TypeController@store')->name('type.store');
	Route::get('type/{id}/edit', 'TypeController@edit')->name('type.edit');
	Route::post('type/{id}/edit', 'TypeController@update')->name('type.update');
	Route::delete('type/delete/{id}', 'TypeController@destroy')->name('type.destroy');
	
	Route::get('bedrooms', 'BedroomController@index')->name('bedrooms.index');
	Route::get('bedroom/create', 'BedroomController@create')->name('bedroom.create');
	Route::post('bedroom', 'BedroomController@store')->name('bedroom.store');
	Route::get('bedroom/{id}/edit', 'BedroomController@edit')->name('bedroom.edit');
	Route::post('bedroom/{id}/edit', 'BedroomController@update')->name('bedroom.update');
	Route::delete('bedroom/delete/{id}', 'BedroomController@destroy')->name('bedroom.destroy');
	
	Route::get('bathrooms', 'BathroomController@index')->name('bathrooms.index');
	Route::get('bathroom/create', 'BathroomController@create')->name('bathroom.create');
	Route::post('bathroom', 'BathroomController@store')->name('bathroom.store');
	Route::get('bathroom/{id}/edit', 'BathroomController@edit')->name('bathroom.edit');
	Route::post('bathroom/{id}/edit', 'BathroomController@update')->name('bathroom.update');
	Route::delete('bathroom/delete/{id}', 'BathroomController@destroy')->name('bathroom.destroy');
	
	Route::get('properties', 'PropertyController@index')->name('properties.index');
	Route::get('property/create', 'PropertyController@create')->name('property.create');
	Route::post('property', 'PropertyController@store')->name('property.store');
	Route::get('property/{id}', 'PropertyController@show')->name('property.show');
	Route::get('property/{id}/edit', 'PropertyController@edit')->name('property.edit');
	Route::post('property/{id}/edit', 'PropertyController@update')->name('property.update');
	Route::delete('property/delete/{id}', 'PropertyController@destroy')->name('property.destroy');
	Route::post('property/{id}', 'PropertyController@updateSeasonRates')->name('property.updateSeasonRates');
	Route::post('property/update-property-amenities/{id}', 'PropertyController@updateAmenities')->name('property.updateAmenities');
    Route::get('property/reservation-calendar/{id}', 'PropertyController@showCalendar')->name('property.showCalendar');
	Route::post('property/update/pictures/{id}', 'PropertyController@updatePictures')->name('property.updatePictures');
	
	Route::get('sleeps', 'SleepController@index')->name('sleeps.index');
	Route::get('sleep/create', 'SleepController@create')->name('sleep.create');
	Route::post('sleep', 'SleepController@store')->name('sleep.store');
	Route::get('sleep/{id}/edit', 'SleepController@edit')->name('sleep.edit');
	Route::post('sleep/{id}/edit', 'SleepController@update')->name('sleep.update');
	Route::delete('sleep/delete/{id}', 'SleepController@destroy')->name('sleep.destroy');
	
	Route::get('seasonrate', 'SeasonController@index')->name('seasonrate.index');
	Route::get('seasonrate/create', 'SeasonController@create')->name('seasonrate.create');
	Route::post('seasonrate', 'SeasonController@store')->name('seasonrate.store');
	Route::get('seasonrate-daily', 'SeasonController@getDailyRate')->name('seasonrate.daily');
	Route::get('seasonrate/{id}/edit', 'SeasonController@edit')->name('seasonrate.edit');
	Route::post('seasonrate/{id}/edit', 'SeasonController@update')->name('seasonrate.update');
	Route::delete('seasonrate/delete/{id}', 'SeasonController@destroy')->name('seasonrate.destroy');
	
	Route::get('housekeepers', 'HouseKeeperController@index')->name('housekeepers.index');
	Route::get('housekeeper/create', 'HouseKeeperController@create')->name('housekeeper.create');
	Route::post('housekeeper', 'HouseKeeperController@store')->name('housekeeper.store');
	Route::get('housekeeper/{id}/edit', 'HouseKeeperController@edit')->name('housekeeper.edit');
	Route::post('housekeeper/{id}/edit', 'HouseKeeperController@update')->name('housekeeper.update');
	Route::delete('housekeeper/delete/{id}', 'HouseKeeperController@destroy')->name('housekeeper.destroy');
	
	Route::post('upload-files', 'AttachmentController@save');
	Route::get('delete/file/{id}', 'AttachmentController@delete');
	
	Route::get('lineitems', 'LineItemController@index')->name('lineitems.index');
	Route::get('lineitem/create', 'LineItemController@create')->name('lineitem.create');
	Route::post('lineitem', 'LineItemController@store')->name('lineitem.store');
	Route::get('lineitem/{id}/edit', 'LineItemController@edit')->name('lineitem.edit');
	Route::post('lineitem/{id}/edit', 'LineItemController@update')->name('lineitem.update');
	Route::delete('lineitem/delete/{id}', 'LineItemController@destroy')->name('lineitem.destroy');
	
	Route::get('reservation', 'ReservationController@index')->name('reservation.index');
	Route::get('reservation/step1', 'ReservationController@step1')->name('reservation.step1');
	Route::get('reservation/create/{property_id}', 'ReservationController@create')->name('reservation.create');
	Route::post('reservation', 'ReservationController@store')->name('reservation.store');
	Route::get('reservation/{id}/edit', 'ReservationController@edit')->name('reservation.edit');
	Route::post('reservation/{id}/edit', 'ReservationController@update')->name('reservation.update');
	Route::post('reservation/delete/{id}', 'ReservationController@destroy')->name('reservation.destroy');
	Route::get('reservation/view/{id}', 'ReservationController@show')->name('reservation.show');
	Route::get('reservation/cancel/{id}', 'ReservationController@reservation_cancel')->name('reservation.cancel');
	Route::post('process_payment/{reservation_id}', 'ReservationController@process_payment')->name('reservation.process_payment');
	Route::post('reservation/make_refund/{reservation_id}', 'ReservationController@make_refund')->name('reservation.make_refund');
	Route::post('reservation/void/{reservation_id}', 'ReservationController@void_payment')->name('reservation.void_payment');
	
	Route::get('get-customer', 'AdminController@get_user');
});

Route::get('calendar/{property_id}', 'Admin\ReservationController@calendar');


Route::get('/', function () {
	return redirect('login');
});

Route::get('/push-updates', function () {
	return view('Testing_file');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('log', function () {
	// from PHP documentations
	$logFile = file(storage_path() . '/logs/laravel.log');
	$logCollection = [];
	// Loop through an array, show HTML source as HTML source; and line numbers too.
	foreach ($logFile as $line_num => $line) {
		$logCollection[] = array('line' => $line_num, 'content' => htmlspecialchars($line));
	}
	dump($logFile);
});

Route::get('log/delete', function () {

	$file = storage_path() . '/logs/laravel.log';
	if (unlink($file)) {
		dump('deleted');
	}
});
