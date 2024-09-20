@extends(env('FRONTEND_VIEW_FOLDER','demo-frontend').'.frontend_layout')
@section('css')

    <!-- yeh css disturb kar rahi thi -->
    <!-- <link rel="stylesheet" href="{{ asset('/css/style.css') }}"> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

@if(request()->has('kiosk') || session('from_kiosk')==1)
<style type="text/css">
  header li{
        pointer-events:none;
        cursor: no-drop;
    }
    header{
        pointer-events:none;
        cursor: no-drop;
    }
</style>
@endif

@endsection
<style type="text/css">
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

  .international {
      display: none;
  }
  form.cmxform label.error, label.error {
      color: red;
  }

  #loading-overlay {
    opacity:    0.5; 
    display: none;
    background: #fdfdfd;
    width:      100%;
    height:     100%; 
    z-index:    10;
    top:        0; 
    left:       0; 
    position:   fixed; 
    cursor: no-drop;   ; 
  }
  #img-load {
    width: 50px;
    height: 57px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -28px 0 0 -25px;
  }
  .alert{
        font-size: 12px;
        line-height: initial;
  }
</style>

<div id="loading-overlay">
  <i id="img-load"  class="fa fa-refresh fa-spin fa-4x "></i>
</div>

@section('content')
  @yield('content')
@endsection


@section('javascripts')

@if(env('FRONTEND_VIEW_FOLDER')!='sage-brush-cycles-frontend'
&& env('FRONTEND_VIEW_FOLDER')!='absolute-bike-sedona-frontend' 
&& env('FRONTEND_VIEW_FOLDER')!='absolute-bikes-frontend' 
&& env('FRONTEND_VIEW_FOLDER')!='norfolkkayaks-frontend'
&& env('FRONTEND_VIEW_FOLDER')!='epic-frontend'
&& env('FRONTEND_VIEW_FOLDER')!='redraven-frontend'
&& env('FRONTEND_VIEW_FOLDER')!='project-bike-frontend'
&& env('FRONTEND_VIEW_FOLDER')!='basecamp-vt-frontend'
)

{{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous"> --}}

@endif

<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

<script src="{{ asset('plugins/moment/moment.js') }}"></script>

<script src="{{ asset('plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('js/main.js') }}"></script>

<script src="{{ asset('jquery-validation') }}/dist/jquery.validate.js"></script>


@yield('javascript')

<script type="text/javascript">
  /*$( document ).ajaxStart(function() {
      $('#loading-overlay').fadeIn();
    });
    $( document ).ajaxComplete(function() {
      $('#loading-overlay').fadeOut();
    });*/
</script>

@endsection




