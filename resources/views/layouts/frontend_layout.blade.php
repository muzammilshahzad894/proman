<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>


    @if(config('general.favicon')!="")
    <link rel="shortcut icon" href="{{asset('uploads/'.config('general.favicon'))}}" type="image/x-icon">
    <link rel="icon" href="{{asset('uploads/'.config('general.favicon'))}}" type="image/x-icon">
    @endif

    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('/')}}frontend/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('/')}}frontend/css/style.css">

    <!-- <link rel="stylesheet" href="{{asset('/')}}/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"> -->
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
    .content_container{
      background: white;
      /* padding: 15px; */
    }

  .wrap .container{
      padding-top: 26px;
      padding-bottom: 70px;
  }

  </style>
  @yield('css')
</head>
<body >
  <div id="wrapper">

    <header id="header">
      <div class="container">
        <div class="header-top clearfix">

          <div class="logo pull-left">

            <a href="{{url('')}}">
              <img src="{{URL::asset('/')}}frontend/images/logo.png" alt="Logo not found">
            </a>
          </div>
          <nav id="nav" class="navbar navbar-default pull-right">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">

                    
                </ul>
            </div><!-- /.navbar-collapse -->
          </nav>
        </div>
      </div>
    </header><!--Header-->
    <div class="content_container row">
        <div class="wrap">
          <div class="container m-b-100 m-t-100">
          @yield('content')
        </div>
      </div>
    </div>

    <footer id="footer">
      <div class="footer-menu text-center">
        <div class="container">

          <ul>

          </ul>
        </div>
      </div><!--Footer Menu-->

      <div class="copyright text-center">
        <div class="container">
          <p>Copyrights &#9400; {{config('general.site_name')}} {{date('Y')}} all rights reserved</p>
        </div>
      </div><!--CopyRight-->
    </footer><!--Footer-->
  </div>
    <script src="{{URL::asset('/')}}frontend/js/jquery.min.js"></script>
    <script src="{{URL::asset('/')}}frontend/js/bootstrap.min.js"></script>
    <script src="{{asset('/')}}/plugins/select2/dist/js/select2.min.js"></script>
    <!-- <script src="{{asset('/')}}/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="{{asset('/')}}/js/main.js"></script> -->


     @yield('javascripts')
     @yield('javascript')



</body>
</html>    