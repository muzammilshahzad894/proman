@extends('layouts.default')

@section('title')
	Mail Settings
	@parent
@stop

@section('css')
	<link rel="stylesheet" href="{{ URL::asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
        <style>
            .dn{
                display: none;
            }
        </style>
@stop

@section('javascript')
	<!-- Bootstrap WYSIHTML5 -->
	<script src="{{ URL::asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>



  <script>
    $('[data-driver]').change(function(){
      var selectedOption = $(this).find(':selected').val();

      if(selectedOption == 'smtp'){
        $('[data-smtp]').show();
        $('[data-mailgun]').hide();
      } else {
        $('[data-smtp]').hide();
        $('[data-mailgun]').show();
      }
    });


    $(document).on('submit', '#mail_password_form', function(event) {
          event.preventDefault();
          $('#password_button').html("Please Wait....");
          $.ajax({
          url: "{{url('/')}}/admin/settings/confirm_password",
          type: 'POST',
          data: $('#mail_password_form').serialize()
          })
          .done(function(response) {
              $('#password_button').html("Show <i class='fa fa-eye'></i>");
              console.log(response.status);
              if(response.status == true)
              {
                  $('#msg').html("<p style='color:green;'>"+response.mail_key+"</p>");
              }else{
                  $('#msg').html("<p style='color:red;'>Wrong Password</p>");
              }

          })
          .fail(function() {

          })
          .always(function() {

          });

      });

      $("#close_button, #close_x_button").click(function(){
        $('#password').val('');
        $('#msg').html('');
      });

  $(document).ready(function(){
          $("#edit_mailgun_key").click(function(){
            $('#mailgun_settings').show();
            $('#smtp_settings').hide();
          });

          $("#edit_smtp_key").click(function(){
            $('#mailgun_settings').hide();
            $('#smtp_settings').show();
          });

          $("#edit_log_key").click(function(){
            $('#mailgun_settings').hide();
            $('#smtp_settings').show();
          });

        });

    function updateDriver(el){
        let driver = $(el).val();
        let url = '{{ route('admin.settings.mail_driver', $setting) }}';

        // Create an object with the form data
        var formData = {
            'token': $('#token').val(),
            'config[driver]': driver,
        };

        // Make an AJAX request
        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Set CSRF token if required
            },
            data: formData,
            success: function(response) {
                // Request succeeded
                // Process the response as needed
                if(response.status){
                    swal({
                        title: "Success!",
                        text: response.message,
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });
                }else{
                    swal({
                        title: "Error!",
                        text: response.message,
                        type: "error",
                        timer: 1000,
                        showConfirmButton: false
                    });
                }
                location.reload();

            },
            error: function(xhr, status, error) {
                // Request failed
                // Handle errors
                swal({
                    title: "Error!",
                    text: response.message,
                    type: "error",
                    timer: 1000,
                    showConfirmButton: false
                });
                location.reload();
            }
        });
    }
  </script>


@stop

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="row">

      {{-- @include('shared.errors') --}}


          <div class="col-lg-5 col-md-5 margin-bottom">
            {{-- mail settings driver change form  --}}
            <div style="display:none;">
              <form action="{{ route('admin.settings.mail_driver', $setting) }}" id="mail_driver_form" method="post">
                @csrf
                <input type="hidden" name="config_driver_toggle" id="config_driver">
              </form>
            </div>

            <form action="{{ route('admin.settings.mail', $setting) }}" id="mail_toogle_form" method="post">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <?php $driver = old('config.driver') ? old('config.driver') : config('mail.driver') ; ?>

            <div class="row">
              <div class="col-xs-12">

                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">{{ $setting->title }}
                      <p><small>{{ucwords(config('mail.driver'))}} settings integration</small></p>

                    </h3>
                  </div>
                  <div class="box-body">
                    <div class="row">
                      <div class="col-xs-12">


                        <div class="form-group {{ $errors->has('config.driver') ? 'has-error' : '' }}">
                          <?php $driver = old('config.driver') ? old('config.driver') : config('mail.driver') ?>
                          <label class="field-required">Driver</label>
                          <select name="config[driver]" class="form-control" onchange="updateDriver(this)">
                            <option selected disabled>Select mail driver</option>
                            <option value="smtp" @if($driver == 'smtp') selected @endif>SMTP</option>
                            <option value="mailgun" @if($driver == 'mailgun') selected @endif>Mailgun (API)</option>
                            <option value="log" @if($driver == 'log') selected @endif>Log Developer Testing</option>
                          </select>

                          @foreach($errors->get('config.driver') as $message)
                            <span class="help-block text-danger">{{ $message }}</span>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- ./row -->
            <div class="row">
              <div class="col-xs-12" data-revealed>

                <div class="box">
                  <div class="box-body">
                    <div class="row">
                      <div class="col-xs-12">
                          @if($driver =='mailgun')
                          <div class="well">
                              <div class="text-right">
                                  {{-- <a href="#showTransactionModal" data-toggle="modal">
                                      <i class="fa fa-eye"></i></a> --}}
                                  <a href="#" id="edit_mailgun_key"><i
                                      class="fa fa-pencil"></i></a>
                                  {{-- <a href="#" id="edit_mailgun_info" style="display:none;" ><i
                                      class="fa fa-pencil"></i></a> --}}
                              </div>
                              <p><strong> Config Domain: </strong> {{ @$config['domain'] }}</p>
                              <p><strong> Config Key: </strong> xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
                          </div>
                          @elseif($driver =='smtp')
                          <div class="well">
                              <div class="text-right">
                                  {{-- <a href="#showTransactionModal" data-toggle="modal">
                                      <i class="fa fa-eye"></i></a> --}}
                                  <a href="javascript:void(0);" id="edit_smtp_key"><i
                                      class="fa fa-pencil"></i></a>
                                  {{-- <a href="#" id="edit_smtp_key" style="display:none;" ><i
                                      class="fa fa-pencil"></i></a> --}}
                              </div>
                              <p><strong> Host: </strong> {{ @$config['host'] }}</p>
                              <p><strong> Port: </strong> {{ @$config['port'] }}</p>
                              <p><strong> Username: </strong> {{ @$config['username'] }}</p>
                              <p><strong> Password: </strong> xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
                          </div>
                          @elseif($driver =='log')
                          <div class="well">
                              <div class="text-right">
                                  {{-- <a href="#showTransactionModal" data-toggle="modal">
                                      <i class="fa fa-eye"></i></a> --}}
                                  <a href="javascript:void(0);" id="edit_log_key"><i
                                      class="fa fa-pencil"></i></a>
                                  {{-- <a href="#" id="edit_log_key" style="display:none;" ><i
                                      class="fa fa-pencil"></i></a> --}}
                              </div>
                              <p><strong> Host: </strong> {{ @$config['host'] }}</p>
                              <p><strong> Port: </strong> {{ @$config['port'] }}</p>
                              <p><strong> Username: </strong> {{ @$config['username'] }}</p>
                              <p><strong> Password: </strong> xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                  @include('settings.partials.__mailgun')

                  @include('settings.partials.__smtp')

              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div class="box-header">
                  </div>
                  <div class="box-body">
                    <div class="row">
                      {{--</div>--}}
                      <div class="col-xs-6">
                        <div class="form-group {{ $errors->has('config.from.address') ? 'has-error' : '' }}">
                          <label for="site-url" class="field-required">From (email)</label>
                          <input name="config[from][address]" id="site-url"
                               type="text" class="form-control" placeholder="somecompany{{'@'}}somewhere.com"
                               value="{{ old('config.from.address') ? old('config.from.address') : config('mail.from.address') }}">
                          @foreach($errors->get('config.from.address') as $message)
                            <span class="help-block text-danger">{{ $message }}</span>
                          @endforeach
                        </div>
                      </div>
                      <div class="col-xs-6">
                        <div class="form-group {{ $errors->has('config.from.name') ? 'has-error' : '' }}">
                          <label for="site-url" class="field-required">From (name)</label>
                          <input name="config[from][name]" id="site-url"
                               type="text" class="form-control" placeholder="SomeCompany"
                               value="{{ old('config.from.name') ? old('config.from.name') : config('mail.from.name') }}">
                          @foreach($errors->get('config.from.name') as $message)
                            <span class="help-block text-danger">{{ $message }}</span>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <button type="submit" class="btn btn-primary">Update Settings</button>
        </form>

    </div>
          <div class="col-lg-7 col-md-7">
            <!-- ./row -->
            <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">Email Management
                              <small></small>
                          </h3>
                      </div>
                      <div class="box-body">
                          <form action="{{ route('admin.settings.send-email') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="row">
                                  <div class="col-xs-6">
                                    <div class="form-group {{ $errors->has('to') ? 'has-error' : '' }}">
                                      <label for="site-url" class="field-required">To (email)</label>
                                      <input name="to" id="site-url"
                                           type="email" class="form-control" placeholder="someone{{'@'}}somewhere.com"
                                           value="{{ old('to') }}"
                                      >
                                      @foreach($errors->get('to') as $message)
                                        <span class="help-block text-danger">{{ $message }}</span>
                                      @endforeach
                                    </div>
                                  </div>
                            </div>
                            <div class="row">
                                    <div class="col-md-12">
                                            <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
                                                    <label for="message" class="control-label">Message</label>
                                                    <textarea name="message" cols="30" rows="5" placeholder="Message" class="form-control mceEditor">
                                                        {{ old('message') }}
                                                    </textarea>
                                                    @foreach($errors->get('message') as $message)
                                                            <span class="help-block">{{ $message }}</span>
                                                    @endforeach
                                            </div>
                                    </div>
                            </div>
                            <button type="submit" class="btn btn-primary margin-bottom">Send</button>
                          </form>
                            <h3 class="box-title hide">Email History</h3>

                      </div>
                  </div>
              </div>
            </div>
          </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
function updateMailDriver(el) {
  $('#config_driver').val($(el).val())
  $('#mail_driver_form').submit();
}
</script>


@stop

@include('settings.partials.show_mail_key_popup')

@section('javascript')
<script>
  // $('[data-driver]').change(function(){
  //   var selectedOption = $(this).find(':selected').val();

  //   if(selectedOption == 'smtp'){
  //     $('[data-smtp]').show();
  //     $('[data-mailgun]').hide();
  //   } else {
  //     $('[data-smtp]').hide();
  //     $('[data-mailgun]').show();
  //   }
  // });



</script>
@stop

