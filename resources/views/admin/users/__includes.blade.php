@section('javascripts')
	<script>
		// makes password-confirmation field green if
		// passwords match and makes it red otherwise
		$('[data-password-confirmation]').on('keyup', function (e) {
			if($('[data-password]').val() === $(this).val()){
				$(this).closest('[data-password-confirmation-container]')
						.removeClass('has-error').addClass('has-success');
			} else {
				$(this).closest('[data-password-confirmation-container]')
						.removeClass('has-success').addClass('has-error');
			}
		});
	</script>
@stop
