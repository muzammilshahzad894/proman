<div class="box" id="smtp_settings" data-smtp style="display:  none;">
    <div class="box-body">
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group {{ $errors->has('config.host') ? 'has-error' : '' }}">
                    <label for="site-name" class="field-required">Host</label>
                    <input name="config[host]" id="site-name"
                           type="text" class="form-control" placeholder="mail.somehosting.com"
                           value="{{ old('config.host') ? old('config.host') : '' }}"
                    >

                    @foreach($errors->get('config.host') as $message)
                        <span class="help-block text-danger">{{ $message }}</span>
                    @endforeach
                </div>
            </div>
            
            <div class="col-xs-3">
                <div class="form-group {{ $errors->has('config.port') ? 'has-error' : '' }}">
                    <label for="default-email" class="field-required">Port</label>
                    <input name="config[port]" id="default-email"
                           type="text" class="form-control" placeholder="12345"
                           autocomplete="new-password"
                           value="{{ old('config.port') ? old('config.port') : '' }}"
                    >

                    @foreach($errors->get('config.port') as $message)
                        <span class="help-block text-danger">{{ $message }}</span>
                    @endforeach
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group {{ $errors->has('config.username') ? 'has-error' : '' }}">
                    <label for="site-url" class="field-required">Username</label>
                    <input name="config[username]" id="site-url"
                          type="text" class="form-control" placeholder="somecompany{{'@'}}somewhere.com"
                          value="{{ old('config.username') ? old('config.username') : '' }}"
                    >

                    @foreach($errors->get('config.username') as $message)
                        <span class="help-block text-danger">{{ $message }}</span>
                    @endforeach
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group {{ $errors->has('config.password') ? 'has-error' : '' }}">
                    <label for="site-url" class="field-required">Password</label>
                    <input name="config[password]" id="site-url"
                          type="password" class="form-control" placeholder="secret123"
                          autocomplete="new-password"
                          value="{{ old('config.password') ? old('config.password') : '' }}"
                    >

                    @foreach($errors->get('config.password') as $message)
                        <span class="help-block text-danger">{{ $message }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
