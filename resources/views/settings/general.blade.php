@extends('layouts.default')
@section('title')
    General Settings
    @parent
@stop


@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">

            <form action="{{ route('admin.settings.general', $setting) }}" method="post" enctype="multipart/form-data">

                <div class="row">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="col-lg-6 col-md-5">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">{{ $setting->title }}
                                            <small>{{ $setting->description }}</small>
                                        </h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div
                                                    class="form-group {{ $errors->has('config.site_name') ? 'has-error' : '' }}">
                                                    <label for="site-name" class="field-required">Site name</label>
                                                    <input autofocus="" name="config[site_name]" id="site-name"
                                                        type="text" class="form-control" placeholder="Example Services"
                                                        value="{{ old('config.site_name') ? old('config.site_name') : $config['site_name'] }}">
                                                    @foreach ($errors->get('config.site_name') as $message)
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div
                                                    class="form-group {{ $errors->has('config.site_url') ? 'has-error' : '' }}">
                                                    <label for="site-url" class="field-required">Site url</label> <input
                                                        name="config[site_url]" id="site-url" type="url"
                                                        class="form-control" placeholder="http://example.com"
                                                        value="{{ old('config.site_url') ? old('config.site_url') : $config['site_url'] }}">
                                                    @foreach ($errors->get('config.site_url') as $message)
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div
                                                    class="form-group {{ $errors->has('config.default_email') ? 'has-error' : '' }}">
                                                    <label for="default-email" class="field-required">Default email</label>
                                                    <input name="config[default_email]" id="default-email" type="email"
                                                        class="form-control"
                                                        placeholder="example{{ '@' }}somewhere.com"
                                                        value="{{ old('config.default_email') ? old('config.default_email') : $config['default_email'] }}">
                                                    @foreach ($errors->get('config.default_email') as $message)
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div
                                                    class="form-group {{ $errors->has('config.phone') ? 'has-error' : '' }}">
                                                    <label for="default-email" class="field-required">Default Phone</label>
                                                    <input name="config[phone]" type="text" class="form-control"
                                                        id="phone" placeholder="(111) 111-1111"
                                                        value="{{ old('config.phone') ? old('config.phone') : @$config['phone'] }}">
                                                    @foreach ($errors->get('config.phone') as $message)
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div
                                                    class="form-group {{ $errors->has('config.admin_phone') ? 'has-error' : '' }}">
                                                    <label for="default-email" class="field-required">Admin Mobile
                                                        Phone</label>
                                                    <input name="config[admin_phone]" type="text" class="form-control"
                                                        id="admin_phone" placeholder="(111) 111-1111"
                                                        value="{{ old('config.admin_phone') ? old('config.admin_phone') : @$config['admin_phone'] }}">
                                                    @foreach ($errors->get('config.admin_phone') as $message)
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title m-b-10">Time Zone</h3>

                                    </div>

                                    <div class="box-body">
                                        <div class="form-group">
                                            <select name="config[timezone]" class="form-control">
                                                <option value="">Select</option>
                                                <option @if (@$config['timezone'] == 'America/Los_Angeles') selected="" @endif
                                                    value="America/Los_Angeles">PST</option>
                                                <option @if (@$config['timezone'] == 'America/Denver') selected="" @endif
                                                    value="America/Denver">MST</option>
                                                <option @if (@$config['timezone'] == 'America/New_York') selected="" @endif
                                                    value="America/New_York">EST</option>
                                                <option @if (@$config['timezone'] == 'America/Chicago') selected="" @endif
                                                    value="America/Chicago">CST</option>
                                                @if (config('site.australia_timezone'))
                                                    <option @if (@$config['timezone'] == 'Australia/Adelaide') selected="" @endif
                                                        value="Australia/Adelaide">ACST</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>

                            </div>
                            <div class="col-xs-6">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title m-b-10">Favicon</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="form-group">
                                            <input accept="{{ allowedImageTypes() }}" type="file"
                                                name="config[favicon]">
                                            <p>Dimensions: 16 X 16 </p>
                                            <p>ICO format is the recommended.</p>
                                            @if (config('general.favicon') != '')
                                                <img style="margin-top: 10px" width="40px"
                                                    src="{{ asset('uploads/' . config('general.favicon')) }}"
                                                    class="img img-responsive">
                                            @endif
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-7">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title m-b-10">Additional Emails</h3>

                            </div>
                            <div class="box-body">
                                <div class="box-tools">
                                    <input data-add type="email" class="form-control"
                                        placeholder="someone{{ '@' }}somewhere.com"
                                        style="width:200px; display:inline-block">

                                    <a data-add-trigger class="btn btn-primary btn-sm" href="#"
                                        style="display:inline-block; margin: 0 5px 4px 0;">Add</a>

                                </div>
                            </div>
                            <br>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-striped">
                                    <tbody data-add-target>
                                        <tr>
                                            <th>#</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th style="width: 40px"></th>
                                        </tr>
                                        <?php $i = 0; ?>
                                        @foreach ($config['additional_emails'] as $additional_email)
                                            <tr
                                                data-delete="{{ route('admin.settings.destroy_email', [
                                                    'setting_id' => $setting->id,
                                                    'email' => $additional_email,
                                                ]) }}">
                                                <td>{{ ++$i }}.</td>
                                                <td>{{ $additional_email }}</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td><a class="btn btn-danger btn-xs" href="#"
                                                        data-delete-trigger>Delete</a>
                                                </td>
                                                <input type="hidden" name="config[additional_emails][]"
                                                    value="{{ $additional_email }}">
                                            </tr>
                                        @endforeach

                                        {{-- Template for new entries --}}
                                        <tr style="display:none;" data-template data-delete>
                                            <td></td>
                                            <td><span class="label label-warning">Pending</span></td>
                                            <td>
                                                <a class="btn btn-danger btn-xs" href="#"
                                                    data-delete-trigger="no-confirm">Delete</a>
                                            </td>
                                            <input type="hidden" value="">
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="row">
                            <div class="col-md-6">

                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title m-b-10">Google Map Link</h3>

                                    </div>

                                    <div class="box-body">
                                        <div
                                            class="form-group {{ $errors->has('config.google_map_link') ? 'has-error' : '' }}">
                                            <input name="config[google_map_link]" type="text" class="form-control"
                                                value="{{ old('config.google_map_link') ? old('config.google_map_link') : @$config['google_map_link'] }}">
                                            @foreach ($errors->get('config.google_map_link') as $message)
                                                <span class="help-block text-danger">{{ $message }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                            </div>

                        </div>
                        @if (config('site.survey'))
                            <div class="col-xs-8">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title m-b-10">Google Bussiness Review Link</h3>

                                    </div>

                                    <div class="box-body">
                                        <div
                                            class="form-group {{ $errors->has('config.google_review_link') ? 'has-error' : '' }}">
                                            <input name="config[google_review_link]" type="text" class="form-control"
                                                placeholder="https://www.google.com/"
                                                value="{{ old('config.google_review_link') ? old('config.google_review_link') : @$config['google_review_link'] }}">
                                            @foreach ($errors->get('config.google_review_link') as $message)
                                                <span class="help-block text-danger">{{ $message }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>

                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary">Update Settings</button>
                    </div>
                </div>
                <!-- ./row -->
            </form>

    </div>
    </div>

@stop



@section('javascript')
    <script type="text/javascript" src="{{ asset('jquery_widgets/masked_input.js') }}"></script>
    <script type="text/javascript">
        @if (config('site.phone_format'))
            $("#phone").mask("{{ config('site.phone_format') }}");
            $("#admin_phone").mask("{{ config('site.phone_format') }}");
        @else
            $("#phone").mask("(999) 999-9999");
            $("#admin_phone").mask("(999) 999-9999");
        @endif


        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>

    <script>
        $(document).on('click', '[data-add-trigger]', function(event) {
            event.preventDefault();
            //alert('hi'); 
            // extract value from input and clear it
            var $emailField = $('[data-add]');
            var newEmail = $emailField.val();

            if (!newEmail) {
                return;
            }

            $emailField.val('');

            // the table which holds additional emails
            var $table = $('[data-add-target]');

            // entry template
            var $template = $('[data-template]');
            var $newEntry = $template.clone();

            // prepare the template
            $newEntry.removeAttr('style').removeAttr('data-template');
            $newEntry.find('input').attr('name', 'config[additional_emails][]').val(newEmail);

            // find out the entry number for the new entry
            var number = Number($table.find('tr').last().prev().find('td:nth-child(1)').text());
            number = number ? ++number : 1;

            // set the entry number and email
            $newEntry.find('td:nth-child(1)').text(number + '.');
            $newEntry.find('td:nth-child(2)').text(newEmail);

            // add the new entry before the template
            $template.before($newEntry);
        });
    </script>



    <script>
        $('#image-upload').change(function() {
            var $imageUploadLabel = $('[for=image-upload]');

            if ($(this).val()) {
                $imageUploadLabel.removeClass('btn-default');
                $imageUploadLabel.addClass('btn-warning');
            } else {
                $imageUploadLabel.addClass('btn-warning');
                $imageUploadLabel.removeClass('btn-default');
            }
        });
    </script>

@stop
