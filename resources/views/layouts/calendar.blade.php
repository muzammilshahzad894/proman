<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{url('/')}}/plugins/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{url('/')}}/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{url('/')}}/plugins/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="{{url('/')}}/css/skins/_all-skins.min.css">
    
    @yield('css')
    
    <link rel="stylesheet" href="{{ url('css')}}/style.css?01">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition  " style="overflow: hidden;">
    <!-- Site wrapper -->
    <div class="wrapper">
        @yield('content')
    </div>
    <!-- ./wrapper -->
</body>
</html>

<style>
    .content {
        min-height: 250px;
        padding: 15px;
        margin-right: auto;
        margin-left: auto;
        padding-left: 25px;
        padding-right: 25px;
        padding-top: 25px;
        padding-bottom: 100px;
    }
</style>