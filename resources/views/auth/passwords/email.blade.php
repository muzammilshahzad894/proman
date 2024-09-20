@extends('layouts.auth')
@section('title')
    Reset Password
    @parent
@stop
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

@section('content')

<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>{{ config('general.site_name', 'Rezo Systems') }}</b></a>
  </div>

<!-- /.login-logo -->
<div class="login-box-body">
  <p class="login-box-msg">Reset Password</p>

  <form  method="post" action="{{ route('password.email') }}">

    {{ csrf_field() }}
    @include('shared.errors')

    <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
      <input type="email" name="email"  class="form-control" placeholder="Email" value="{{ old('email') }}">
      <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      @if ($errors->has('email'))
          <span class="help-block">
            @if ($errors->first('email') == 'passwords.throttled')
              {{-- <strong>You have exceeded the limit for password reset requests. Please try again in 1 minute.</strong> --}}
            @else
              <strong>{{ $errors->first('email') }}</strong>
            @endif
          </span>
      @endif
    </div>

    
    <div class="row">
      <div class="col-xs-4">
        <a class="btn btn-primary" href="{{ route('login') }}">Login</a><br>
      </div>
      <!-- /.col -->
      <div class="col-xs-8">
        <button type="submit" class="btn btn-primary btn-block btn-flat"> Send Password Reset Link</button>
      </div>
      <!-- /.col -->
    </div>

    <!-- ADDED Google Captcha by ZAIN -->
    @if(@$config->google_captcha_key_new)
    <div class="row" style="padding: 10px;">
      <div class="col-xs-12">
    <div class="g-recaptcha" data-sitekey="{{@$config->google_captcha_key_new}}"></div>
      </div>
    </div>
    @endif
  </form>

   
    

</div>
<!-- /.login-box-body -->

</div>
<!-- /.login-box -->

@stop

