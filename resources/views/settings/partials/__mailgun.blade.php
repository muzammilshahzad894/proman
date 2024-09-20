<div class="box" id="mailgun_settings" data-mailgun @if(old('config.driver') && old('config.driver') == 'mailgun') style="display: block;" @else style="display: none;" @endif>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group {{ $errors->has('config.domain') ? 'has-error' : '' }}">
                    <label for="site-name" class="field-required">config domain</label>
                    <input name="config[domain]" id="site-name"
                           type="text" class="form-control" placeholder="Enter config domain"
                           value="{{ old('config.domain') ? old('config.domain') : '' }}"
                           >

                    @foreach($errors->get('config.domain') as $message)
                        <span class="help-block text-danger">{{ $message }}</span>
                    @endforeach
                </div>
            </div>
            
            <div class="col-xs-6">
                <div class="form-group {{ $errors->has('config.secret') ? 'has-error' : '' }}">
                    <label for="default-email" class="field-required">config key</label>
                    <input name="config[secret]" id="default-email"
                           type="password" class="form-control" placeholder="Enter config key"
                           autocomplete="new-password"
                           value="{{ old('config.secret') ? old('config.secret') : '' }}"
                    >

                    @foreach($errors->get('config.secret') as $message)
                        <span class="help-block text-danger">{{ $message }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
