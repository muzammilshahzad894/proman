<!DOCTYPE html>
<?php 
$data = $emailData['emailContent']; 
?>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ config('general.site_name') }}</title>
	
	</head>
	
	<body>
            <b>Dear {{ucfirst($data['user']->name)}},</b>
            <p>We have created a profile for you in {{ config('general.site_name') }} Admin Panel. Your account details are listed below:</p>
            <p>Please click <a href="{{ url('login') }}">{{ url('login') }}</a> to login.</p>
            <p style="margin-bottom: 0px;"><b>Login:</b> {{ $data['user']->email }}</p>
            <p style="margin-top: 0px;"><b>Password:</b> {{ $data['password'] }}</p>		
	</body>
</html>