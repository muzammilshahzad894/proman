<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('/')}}frontend/images/favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('/')}}frontend/css/bootstrap.min.css">
    
    
    <!-- <link rel="stylesheet" href="{{assetsUrl()}}/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"> -->
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style type="text/css">
    .active_item{
          border-bottom: solid 5px #e6e4e4;
    }
    .social_media a{
      color: black;
      background: white;
      padding: 15px;
    }
    .field-required:after{
      content:" *";
      color:red;
    }
    .content_container{
      background: white;
      padding: 15px;
    }
    .required:after{
      content:" *";
      color:red;
    }
    .text-danger{
      color: red!important;
    }

  .international {
    display: none;
}

  </style>
  @yield('css')
</head>
<body >
  @yield('content')
     <script src="{{URL::asset('/')}}frontend/js/jquery.min.js"></script>
    <script src="{{URL::asset('/')}}frontend/js/bootstrap.min.js"></script>
    <!-- <script src="{{assetsUrl()}}/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="{{assetsUrl()}}/js/main.js"></script> -->

    
     @yield('javascripts')
     @yield('javascript')
     
     
    
</body>
</html>