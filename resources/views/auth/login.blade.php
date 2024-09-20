@extends('layouts.auth')
@section('title')
    Login
    @parent
@stop
@section('content')
<div style="margin-top: 9%;" class="login-logo">
    <a href="javascript:void"><b>{{ config('general.site_name', 'Rezo Systems') }}</b></a>
  </div>
<div style="margin: 0% auto" class="login-box">
  

<!-- /.login-logo -->
<div class="login-box-body">
  <p class="login-box-msg">Sign in to start your session</p>

  @include('shared.errors')

  <form  method="post" action="{{ route('login') }}">

    {{ csrf_field() }}

    <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
      <input autofocus="" type="email" name="email"  class="form-control" placeholder="Email" value="{{ old('email') }}">
      <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      @if ($errors->has('email'))
          <span class="help-block">
              <strong>{{ $errors->first('email') }}</strong>
          </span>
      @endif
    </div>

    <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
      <input type="password" name="password"  class="form-control" placeholder="Password">
      <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      @if ($errors->has('password'))
          <span class="help-block">
              <strong>{{ $errors->first('password') }}</strong>
          </span>
      @endif
    </div>
    <div class="row">
      <div class="col-xs-8">
        <div class="checkbox icheck">
          <label>
            <input  type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} checked> Remember Me
          </label>
        </div>
      </div>
      <!-- /.col -->
      <div class="col-xs-4">
        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
      </div>
      <div class="col-xs-12">
        
                <a href="{{ url('/password/request') }}">I forgot my password</a><br>
            
      </div>

      <!-- /.col -->
    </div>
  </form>

  


</div>
<!-- /.login-box-body -->

</div>
<!-- /.login-box -->

@stop

