<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #333333;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dddddd;
            word-wrap: break-word; /* Add this to prevent content overflow */
        }
        .linkButton {
            display: inline-block;
            background-color: #3c8dbc;
            color: #ffffff !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
        }
        .linkButton:hover {
            background-color: #2f7197;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{config('general.site_name')}}</h1>
        </div>
        <div class="content">
            <h2>Reset Password</h2>
            <p>This password reset link will expire in 60 minutes.</p>
            <p>If you did not request a password reset, no further action is required.</p>
            <p>
                <a class="linkButton" href="{{ $url }}">Reset Password</a>
            </p>
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
            <p><a href="{{ $url }}">{{ $url }}</a></p>
        </div>
        <div class="footer">
            <p>Regards,<br>{{config('general.site_name')}}</p>
        </div>
    </div>
</body>
</html>
