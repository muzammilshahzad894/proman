<div class="box-body">

    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
        <label for="full_name" class="col-sm-3 control-label field-required">Full Name</label>

        <div class="col-sm-7">
            <input autofocus="" type="text" name="name" class="form-control" id="full_name" placeholder="John Doe"
                value="{{ old('name') ? old('name') : '' }}">
            @foreach ($errors->get('name') as $message)
                <span class="help-block">{{ $message }}</span>
            @endforeach
        </div>
    </div>

    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
        <label for="email" class="col-sm-3 control-label field-required">Email</label>

        <div class="col-sm-7">
            <input type="email" name="email" class="form-control" id="email"
                placeholder="someone{{ '@' }}somewhere.com" value="{{ old('email') ? old('email') : '' }}">
            @foreach ($errors->get('email') as $message)
                <span class="help-block">{{ $message }}</span>
            @endforeach
        </div>
    </div>

    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
        <label for="type" class="col-sm-3 control-label field-required">Type</label>

        <div class="col-sm-7">
            <select name="type" id="input" class="form-control" required="required">
                <option value="">Select</option>
                <option value="admin" @if (old('type') == 'admin') selected @endif>Admin</option>
                <option value="staff" @if (old('type') == 'staff') selected @endif>Staff</option>

            </select>
            @foreach ($errors->get('type') as $message)
                <span class="help-block">{{ $message }}</span>
            @endforeach

        </div>
    </div>


    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}" data-password-container>
        <label for="password" class="col-sm-3 control-label field-required">Password</label>

        <div class="col-sm-3">
            <input type="password" name="password" class="form-control" id="password" placeholder="Secret123"
                data-password>
            @if (!$errors->has('password'))
            @endif
            @foreach ($errors->get('password') as $message)
                <span class="help-block">{{ $message }}</span>
            @endforeach
        </div>
        <div class="col-sm-6">
            <span>Password must be at least 6 character.</span>
        </div>
    </div>

    <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}"
        data-password-confirmation-container>
        <label for="password_confirmation" class="col-sm-3 control-label field-required">Confirm Password</label>

        <div class="col-sm-3">
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                placeholder="Secret123" data-password-confirmation style="max-width: 150px;">

            @foreach ($errors->get('password_confirmation') as $message)
                <span class="help-block">{{ $message }}</span>
            @endforeach
        </div>
    </div>

    <div class="form-group {{ $errors->has('email_details') ? 'has-error' : '' }}" data-email-details-container>
        <label for="email_details" class="col-sm-3 control-label"></label>

        <div class="col-sm-8">
            <label for="email_details">
				<input type="checkbox" name="email_details" id="email_details" data-email-details style="margin-right: 4px;  vertical-align: sub;">
				Email details to new Admin
			</label>
            @foreach ($errors->get('email_details') as $message)
                <span class="help-block">{{ $message }}</span>
            @endforeach
        </div>
    </div>
</div>

</div>
<!-- /.box-body -->
