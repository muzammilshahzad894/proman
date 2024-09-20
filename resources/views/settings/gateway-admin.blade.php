@extends('layouts.master')
@section('title',  'Gateway Settings')
@section('styles')
@endsection

@section('content')

  <div class="box">
    <div class="box-header">
      <div class="box-title">
        <h3 class="box-title text-center">Select Payment Gateway</h3> 
      </div>
    </div>

    @include('shared.errors')

    <div class="box-body">
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-sm-offet-6">

          <form class="" action="{{ url('settings/gateway') }}" method="post">
            {{ csrf_field() }}



                  <?php $gateway = old('config.gateway') ? old('config.gateway') : config('gateway.gateway') ; ?>

                  <div class="form-group">
                    <label for="input_name">Select Gateway</label>
                     <select name="config[gateway]" id="input_name" class="form-control select2" select-gateway>
                      <option value="" disabled>Select</option>
                      <option value="stripe" @if($gateway =='stripe') selected @endif>Stripe</option>
                      <option value="authorize" @if($gateway =='authorize') selected @endif>Authorize.net</option>
                      <option value="braintree" @if($gateway =='braintree') selected @endif>Braintree</option>
                      <option value="squareup" @if($gateway =='squareup') selected @endif>Squareup</option>
                    </select>
                  </div>


                  <?php $mode = old('config.mode') ? old('config.mode') : config('gateway.mode') ?>
                   <div class="form-group">
                    <label for="mode">Gateway Mode</label>
                    <select name="config[mode]" id="mode" class="form-control select2" >
                      <option value="" disabled>Select</option>
                      <option value="demo" @if($mode == 'demo') selected @endif>Demo</option>
                      <option value="live" @if($mode == 'live') selected @endif>Live</option>
                    </select>
                  </div>

                   <?php $method = old('config.method') ? old('config.method') : config('gateway.method') ?>
                   <div class="form-group">
                    <label for="method">Payment Method</label>
                    <select name="config[method]" id="method" class="form-control select2" >
                      <option value="" disabled>Select</option>
                      <option value="charge" @if($method == 'charge') selected @endif>Charge Card</option>
                      <option value="create_profile" @if($method == 'create_profile') selected @endif>Create Customer Profile</option>
                    </select>
                  </div>



                  <div stripe-setting style="display:none;">

                    <div class="form-group">
                      <label for="key1">Secret</label>
                      <input type="text" class="form-control" id="key1" name="config[stripe][secret]"
                      value="{{ old('config.stripe.secret') ? old('config.stripe.secret') : config('gateway.stripe.secret') }}">
                    </div>


                     <div class="form-group">
                      <label for="key2">Publish</label>
                      <input type="text" class="form-control" id="key2" name="config[stripe][publish]"
                      value="{{ old('config.stripe.publish') ? old('config.stripe.publish') : config('gateway.stripe.publish') }}">
                    </div>

                  </div>


                  <div braintree-setting style="display:none;">

                    <div class="form-group">
                      <label for="key1">Private Key</label>
                      <input type="text" class="form-control" id="key1" name="config[braintree][private_key]"
                      value="{{ old('config.braintree.private_key') ? old('config.braintree.private_key') : config('gateway.braintree.private_key') }}">
                    </div>

                     <div class="form-group">
                      <label for="key1">Public Key</label>
                      <input type="text" class="form-control" id="key1" name="config[braintree][public_key]"
                      value="{{ old('config.braintree.public_key') ? old('config.braintree.public_key') : config('gateway.braintree.public_key') }}">
                    </div>

                     <div class="form-group">
                      <label for="key1">Mechant Key</label>
                      <input type="text" class="form-control" id="key1" name="config[braintree][mechant_id]"
                      value="{{ old('config.braintree.mechant_id') ? old('config.braintree.mechant_id') : config('gateway.braintree.mechant_id') }}">
                    </div>

                  </div>

                  <div authorize-setting style="display:none;">

                       <div class="form-group">
                        <label for="key1">Login</label>
                        <input type="text" class="form-control" id="key1" name="config[authorize][login]"
                        value="{{ old('config.authorize.login') ? old('config.authorize.login') : config('gateway.authorize.login') }}">
                      </div>

                      <div class="form-group">
                        <label for="key1">Key </label>
                        <input type="text" class="form-control" id="key1" name="config[authorize][key]"
                        value="{{ old('config.authorize.key') ? old('config.authorize.key') : config('gateway.authorize.key') }}">
                      </div>


                  </div>

                  <div squareup-setting style="display:none;">
                      <div class="squareup-live">
                        <div class="form-group">
                          <label for="key1">Application ID</label>
                          <input type="text" class="form-control" id="key1" name="config[squareup][application_id]"
                          value="{{ old('config.squareup.application_id') ? old('config.squareup.application_id') : config('gateway.squareup.application_id') }}">
                        </div>
                          <div class="form-group">
                              <label for="key2">Access Token</label>
                              <input type="text" class="form-control" id="key2" name="config[squareup][access_token]"
                                     value="{{ old('config.squareup.access_token') ? old('config.squareup.access_token') : config('gateway.squareup.access_token') }}">
                          </div>
                          <div class="form-group">
                              <label for="key2">Location ID</label>
                              <input type="text" class="form-control" id="key2" name="config[squareup][location_id]"
                                     value="{{ old('config.squareup.location_id') ? old('config.squareup.location_id') : config('gateway.squareup.location_id') }}">
                          </div>
                      </div>


                  </div>




            <div class="m-t-20">
              <button type="submit" class="btn btn-success">Save</button>

            </div>

          </form>

        </div>
      </div>
    </div>

  </div>

@endsection


@section('scripts')

  <script>

      $('[{{$gateway}}-setting]').show();

     $('[select-gateway]').change(function(){
       var selectedOption = $(this).find(':selected').val();



       if(selectedOption == 'stripe'){
         $('[stripe-setting]').show();
         $('[braintree-setting]').hide();
         $('[authorize-setting]').hide();
         $('[squareup-setting]').hide();
       }

        if (selectedOption == 'authorize'){
          $('[authorize-setting]').show();
         $('[stripe-setting]').hide();
         $('[braintree-setting]').hide();
         $('[squareup-setting]').hide();
       }

        if (selectedOption == 'braintree'){
         $('[braintree-setting]').show();
         $('[stripe-setting]').hide();
         $('[authorize-setting]').hide();
         $('[squareup-setting]').hide();
       }

       if(selectedOption == 'squareup'){
         $('[squareup-setting]').show();
         $('[stripe-setting]').hide();
         $('[braintree-setting]').hide();
         $('[worldpay-setting]').hide();
         $('[authorize-setting]').hide();
       }

     });
   </script>

@stop
