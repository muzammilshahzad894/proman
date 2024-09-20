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
