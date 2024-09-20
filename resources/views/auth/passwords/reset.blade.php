@extends('layouts.auth')
@section('title')
    Reset Password
    @parent
@stop
@section('content')

<div class="login-box">
  <div class="login-logo">
    <a href=""><b>{{ config('general.site_name', 'Rezo Systems') }}</b></a>
  </div>

<!-- /.login-logo -->
<div class="login-box-body">
  <p class="login-box-msg">Reset Password</p>

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif

  <form  method="post" action="{{ url('password/reset') }}">


    {{ csrf_field() }}

     <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
      <input type="email" name="email"  class="form-control" placeholder="Email" alue="{{ $email or old('email') }}" autofocus>
      <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      @if ($errors->has('email'))
          <span class="help-block">
              <strong>{{ $errors->first('email') }}</strong>
          </span>
      @endif
    </div>

    <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
      <input type="password" id="password" name="password"  class="form-control" placeholder="Password">
      <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      @if ($errors->has('password'))
          <span class="help-block">
              <strong>{{ $errors->first('password') }}</strong>
          </span>
      @endif
    </div>

     <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
      <input type="password" id="password_confirmation" name="password_confirmation"  class="form-control" placeholder="Confirm password">
      <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      @if ($errors->has('password_confirmation'))
          <span class="help-block">
              <strong>{{ $errors->first('password_confirmation') }}</strong>
          </span>
      @endif
    </div>

    <div class="row">
      <div class="col-xs-6">
      
      </div>
      <!-- /.col -->
      <div class="col-xs-6">
        <button type="submit" class="btn btn-primary btn-block btn-flat"> Reset Password</button>
      </div>
      <!-- /.col -->
    </div>
  </form>

    

</div>
<!-- /.login-box-body -->

</div>
<!-- /.login-box -->

@stop

@section('javascript')
<script>
    $(function(){
        $('#password, #password_confirmation').keyup(function(){
            password        = $('#password').val();
            confirmPassword = $('#password_confirmation').val();
            var green = '#00a65a';
            var red = '#dd4b39';
            var defaultColor = '';

            if(password.length > 2 && $.trim(confirmPassword) != ''){
                if(password === confirmPassword){
                    $('label[for=password_confirmation]').css('color', green);
                    $('#password_confirmation').css('border-color', green);
                }else{
                    $('label[for=password_confirmation]').css('color', red);
                    $('#password_confirmation').css('border-color', red);
                }
            }else{ //default label
                    $('label[for=password_confirmation]').css('color', defaultColor);
                    $('#password_confirmation').css('border-color', defaultColor);
            }
        });
    });
</script>
@stop