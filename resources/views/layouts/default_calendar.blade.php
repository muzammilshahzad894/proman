<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Stripe Token -->
  <meta name="stripe-key" content="{{ config('gateway.stripe.publish') }}">

  <title>@yield('title') | {{ config('general.site_name', 'Rezo Systems') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{url('/')}}/plugins/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{url('/')}}/plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{url('/')}}/plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('/')}}/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{url('/')}}/css/skins/_all-skins.min.css">

  <link rel="stylesheet" href="{{url('/')}}/css/style.css">
  


  @yield('css')

  <style>
      .datepicker.datepicker-dropdown.dropdown-menu {
              z-index: 999999;
      }
      input[type='number'] {
          -moz-appearance:textfield;
      }
      
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
          /* display: none; <- Crashes Chrome on hover */
          -webkit-appearance: none;
          margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
      }
      
      
  </style>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!-- ADD THE CLASS sidebar-collapse TO HIDE THE SIDEBAR PRIOR TO LOADING THE SITE -->
<body class="hold-transition skin-blue ">
<!-- Site wrapper -->
<div class="wrapper">


<div id="overlay">
 <i id="img-load"  class="fa fa-refresh fa-spin fa-4x "></i>
</div>
 
@if(strtolower(Auth::user()->type)=='guide')
        @include('layouts.partials.guide_header')
        @else
        @include('layouts.partials.header')
        @endif

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  @if(strtolower(Auth::user()->type)=='guide')
        @include('layouts.partials.guide_nav') 
        @else
        @include('layouts.partials.left') 
        @endif
   

  <!-- =============================================== -->

  @yield('content')

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; {{ date("Y") }} <a href="{{ url('/') }}">{{ config('general.site_name', 'Rezo Systems') }}</a>.</strong> All rights
    reserved.
  </footer>

 
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{url('/')}}/plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{url('/')}}/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="{{url('/')}}/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="{{url('/')}}/plugins/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{url('/')}}/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{url('/')}}/js/demo.js"></script>


  <?php if (true) { ?>
  <script src="{{url('/')}}/plugins/moment/moment.js"></script>
    <script src="{{url('/')}}/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" href="{{url('/')}}/plugins/bootstrap-daterangepicker/daterangepicker.css">

    <script src="{{url('/')}}/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
  
    <script src="{{url('/')}}/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="{{url('/')}}/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

   
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
   
   <script src="{{url('/')}}/plugins/select2/dist/js/select2.min.js"></script>
       <link rel="stylesheet" href="{{url('/')}}/plugins/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{url('/')}}/css/alt/AdminLTE-select2.min.css">

   
    
    
  <?php } ?>

  <script src="{{url('/')}}/plugins/sweetalert/sweetalert.min.js"></script>
  <link rel="stylesheet" href="{{url('/')}}/plugins/sweetalert/sweetalert.css">
  <script src="{{url('/')}}/js/main.js"></script>


@yield('javascript')
@yield('javascripts')

<script>
  @if(Session::has('success'))
    flashMessage = {
      type: "success",
      description: "{{ Session::pull('success') }}"
    };
  @elseif(Session::has('info'))
    flashMessage = {
      type: "info",
      description: "{{ Session::pull('info') }}"
    };
  @elseif(Session::has('warning'))
    flashMessage = {
      type: "warning",
      description: "{{ Session::pull('warning') }}"
    };
  @elseif(Session::has('error'))
    flashMessage = {
      type: "error",
      description: "{{ Session::pull('error') }}"
    };
  @endif

  if(typeof flashMessage !== 'undefined'){
    GLOBAL.displayFlashMessage(flashMessage);
  }
</script>
<script type="text/javascript">
     
    $( document ).ajaxStart(function() {
      $('#overlay').show();
    });

    $( document ).ajaxComplete(function(response,status) {
      $('#overlay').fadeOut();
      if(status.status==401){
        alert("Opps! Seems you couldn't submit form for a longtime your session is expired. Please try again");
        location.reload();
      }
    });
  </script>

@if(strtolower(Auth::user()->type)=='guide')
        
  <script>
        $(document).ready(function () {

            var modal_body = $('#myCalendar_body');

            $(document).on('click', '.myCalendar', function () {
                $('#myCalendar').modal('show');
                var guide_id = $(this).attr('data-id');
                getCalendar(null, guide_id);
            });

            $(document).on('click', '.go_to_last-page, .go_to_next-page', function (e) {
                e.preventDefault();
                var vm = $(this);
                var guide_id = $(this).attr('data-id');
                var data = {
                    'date': $(vm).attr('data-date')
                };
                if ($(vm)[0].hasAttribute('data-previous')) {
                    data['previous'] = true;
                } else {
                    data['next'] = true;
                }
                getCalendar(data, guide_id);
            });

            function getCalendar(data, guide_id) {

                $(modal_body).html("<div class='spinner'></div>");
                $.ajax({
                    type: "GET",
                    url: "{{ route('guides.mycalendar') }}" + '/' + guide_id,
                    data: data,
                    success: function (response) {
                        $(modal_body).html("");
                        if (response.status == true) {
                            $(modal_body).html(response.view);
                        }
                    }
                });
            }

            $(document).on('click', '#update_my_calendar', function (e) {
                e.preventDefault();
                var form = $('#my_calendar_form').serialize();

                $.ajax({
                    type: "POST",
                    url: "{{ route('guides.availability.store') }}",
                    data: form,
                    beforeSend: function () {
                        $(modal_body).html("<div class='spinner'></div>");
                    },
                    success: function (response) {
                        window.location.reload();
                    },
                    error: function (res) {
                        window.location.reload();
                    }
                });
            });

            $(document).on('change', '.off_dates', function () {
                if (this.checked == false) {
                    var hidden = $(this).siblings('.hidden_action_value');
                    $(hidden).attr('disabled', false)
                }
            });
        });
  </script>
@endif
@include('layouts.partials.toggle_side_bar_js')


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
  #overlay { 
display: none;  
opacity:    0.5; 
  background: #000; 
  width:      100%;
  height:     100%; 
  z-index:    2000;
  top:        0; 
  left:       0; 
  position:   fixed; 
}
#img-load {
    width: 50px;
    height: 57px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -28px 0 0 -25px;
}
.field-required:after{
      content:" *";
      color:red;
    }

    .required:after{
      content:" *";
      color:red;
    }
    .text-danger{
      color: red!important;
    }

</style>
