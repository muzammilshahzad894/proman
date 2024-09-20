<div class="alerts m-t-10">
@if (Session::has('info'))
	<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {!!  Session::get('info') !!}
	</div>
@endif

@if (Session::has('error'))
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	{!!  Session::get('error') !!}
</div>
@endif

@if (Session::has('success'))
<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	{!!  Session::get('success') !!}
</div>
@endif

@if (Session::has('warning'))
<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {!!  Session::get('warning') !!}
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    
      @foreach($errors->getMessages() as $errors)
    	@foreach ($errors as $error)
			@if ($error == 'passwords.throttled')
				<strong>You have exceeded the limit for password reset requests. Please try again in 1 minute.</strong>
			@else
				{!! $error !!}<br>
			@endif
    	@endforeach
    @endforeach
    
</div>
@endif
</div>