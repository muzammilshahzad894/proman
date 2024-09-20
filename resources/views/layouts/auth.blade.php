<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>@yield('title') | {{ config('general.site_name', 'Rezo Systems') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{assetsUrl()}}/plugins/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{assetsUrl()}}/plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{assetsUrl()}}/plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{assetsUrl()}}/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{assetsUrl()}}/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">

 

    
@yield('content')




<!-- jQuery 3 -->
<script src="{{assetsUrl()}}/plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{assetsUrl()}}/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="{{assetsUrl()}}/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>

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

@yield('javascript')



</body>
</html>
