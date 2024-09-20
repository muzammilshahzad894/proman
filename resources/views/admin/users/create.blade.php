@extends('layouts.default')

@section('title')
	Create Admin
	@parent
@stop

@include('admin.users.__includes')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">

	<div class="row">
		<div class="col-md-6">
			
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Create Admin</h3>
				</div>
				<div class="box-body">
					@include('shared.errors')
				</div>
				<form action="{{ route('admin.users.store') }}" method="POST" class="form-horizontal">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					@include('admin.users.__form')
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Create</button>
						<a href="{{ route('admin.users.index') }}" class="btn btn-default">Cancel</a>
						
					</div>
				</form>
			</div>
		</div>
	</div>

	</div>
</div>

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