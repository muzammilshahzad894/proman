@extends('layouts.default')
@section('title')
	Edit Admin
	@parent
@stop


@include('admin.users.__includes')
@section('content')

<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">

		<div class="row">
			<div class="col-md-6">
				<!-- Horizontal Form -->
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Your Profile</h3>
					</div>
					<div class="box-body">
						@include('shared.errors')
					</div>
					<!-- /.box-header -->
					<!-- form start -->
					
					<form action="{{ route('admin.users.update', $user) }}" method="POST" class="form-horizontal">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="_method" value="PATCH">
						@include('admin.users.__form_edit', compact('user'))
						<div class="box-footer">
							<button type="submit" class="btn btn-primary ">Update</button>
							<a href="{{ route('admin.users.index') }}" class="btn btn-default">Cancel</a>
							
						</div>
						<!-- /.box-footer -->
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

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