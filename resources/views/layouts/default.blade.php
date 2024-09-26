<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Stripe Token -->
  <meta name="stripe-key" content="{{ config('gateway.stripe.publish') }}">
  <title>{{ config('general.site_name', 'Rezo Systems') }} | @yield('title')</title>
 
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
  <link rel="stylesheet" href="{{url('/')}}/plugins/sweetalert/sweetalert.css">

  <link rel="stylesheet" href="{{url('/')}}/css/admin_style.css">
  <link rel="stylesheet" href="{{ asset('css/icheck-bootstrap.min.css') }}">

  @if(config('general.favicon')!="")
  <link rel="shortcut icon" href="{{asset('uploads/'.config('general.favicon'))}}" type="image/x-icon">
  <link rel="icon" href="{{asset('uploads/'.config('general.favicon'))}}" type="image/x-icon">
  @endif


  @yield('css')

  <style>

      .custom_fieldset{
        margin: 8px 2px;
        padding:0px  2px 0px 3px;
        border: 1px solid #cfcfcf;
      }
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

      form.cmxform label.error, label.error {
          color: red;
      }

      @media only screen and (max-width: 1050px) {
        .nav {
          font-size: 10px;
        }
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

@if((\Route::currentRouteName()!="admin.dashboard"))
<div id="overlay">
 <i id="img-load"  class="fa fa-refresh fa-spin fa-4x "></i>
</div>
@endif
 
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
  @include('layouts.partials.terms_of_use_modal')
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>System Date and Time</b> <?php echo  date('m/d/y h:i a e')?>
    </div>
    <strong>Copyright &copy; <a href="{{url('/')}}">{{ config('general.site_name') }}</a>.</strong> All rights
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

<script src="{{ asset('jquery-validation') }}/dist/jquery.validate.js"></script>

  <!-- Sweet Alert -->
  <script src="{{url('/')}}/plugins/sweetalert/sweetalert.min.js"></script>

  
  <script src="{{url('/')}}/plugins/Minimalist-jQuery-Table-Sort-Plugin-tablesort/jquery.tablesort.js"></script>


  <?php if (true) { ?>
  <script src="{{url('/')}}/plugins/moment/moment.js"></script>
    <script src="{{url('/')}}/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" href="{{url('/')}}/plugins/bootstrap-daterangepicker/daterangepicker.css">

    <script src="{{url('/')}}/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
  
    <script src="{{url('/')}}/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="{{url('/')}}/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

    <script src="{{url('/')}}/plugins/select2/dist/js/select2.min.js"></script>
    <script src="{{url('/')}}/plugins/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js"></script>
    
    <link rel="stylesheet" href="{{url('/')}}/plugins/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{url('/')}}/css/alt/AdminLTE-select2.min.css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/full_calender.css')}}"/>
    <script type="text/javascript" src="{{asset('js/full_calender.js')}}"></script>
    <script src="{{url('/')}}/plugins/tiny_mce/tinymce.min.js"></script>

    <script src="{{ asset('plugins/chart.js/new-2.7.2/Chart.bundle.js') }}"></script>
    @if(Request::route()->getName()!="admin.reports.products.sale")
    <script src="{{ asset('chartjs-plugin-labels-master/src/chartjs-plugin-labels.js') }}"></script>
    @endif
    

    <script type="text/javascript">
        tinymce.init({
        theme: "modern",
        mode : "specific_textareas",
        editor_selector : "mceEditor",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager",
             "insertdatetime media table contextmenu jbimages"
        ],
      
      relative_urls : false,
      remove_script_host : false,
      convert_urls : true,
      document_base_url : "{{url('/')}}",
      
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: 'Test 1'},
            {title: 'Test template 2', content: 'Test 2'}
        ]
    });

    var g_readTerms = false;
      


    </script>

    
    
  <?php } ?>

  <script src="{{url('/')}}/js/main.js?v={{time()}}"></script>
  
  <script>
    jQuery(document).ready(function($) {
      if ($('.singledatepicker').length) {
        $('.singledatepicker').daterangepicker({
          singleDatePicker: true,
          showDropdowns: true
        });
      };

      if ($('.timepicker').length) {  
        $('.timepicker').timepicker({ 'scrollDefault': 'now' });
      };


      /******** Dateranger picker ********/
      if ($('.drp').length) {
        $('.drp').daterangepicker({
          timePicker: false,
          timePickerIncrement: 30,
          opens: 'left',
          locale: {
            format: 'MM/DD/YYYY'
          }
        });
      };
        
      if ($('.drp_withtime').length) {
        $('.drp_withtime').daterangepicker({
          timePicker: true,
          timePickerIncrement: 30,
          opens: 'left',
          locale: {
              format: 'MM/DD/YYYY h:mm A'
          }
        });
      };
      
      if ($('.select2').length) {
        $('.select2').select2();
      };
      
      if ($('.phone_us').length) {
        $('.phone_us').mask('(000) 000-0000', {placeholder: "(000) 000-0000"});
      };

      if ($('.extension').length) {
        $('.extension').mask('000', {placeholder: "000"});
      };

      if ($('.zipcode').length) {
        $('.zipcode').mask('00000', {placeholder: "00000"});
      };      
    });
  </script>


@yield('javascript')
@yield('javascripts')

@include('layouts.partials.toggle_side_bar_js')

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

@if(isset($_GET['show_mode']) && $_GET['show_mode']=='popup')
<script type="text/javascript">
  $(document).ready(function() {
    $('.sidebar-toggle')[0].click();
    $('.main-header').hide();
    $('.main-footer').hide();
  });
</script>
@endif


</body>
</html>


<style>
  fieldset {
    border: 1px dashed #3c8dbc;
    border-radius: 10px;
    padding: 7px;
    margin-bottom: 20px;
  }
  
  legend {
    font-size: 12px;
    font-weight: bold;
    background-color: #3c8dbc;
    color: #fff;
    padding: 0 10px;
    border: 2px solid #ebebeb;
    border-radius: 5px;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
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
