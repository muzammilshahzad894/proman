@extends('layouts.default')
@section('title')
	Gateway Settings
	@parent
@stop


@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">


	<div class="row">

		<div class="box">
    <div class="box-header">
      <div class="box-title">
        <h3 class="box-title text-center">Payment Gateway</h3> 
        <p><small>{{ucwords(config('gateway.gateway'))}} gateway integration</small></p>
      </div>
    </div>

    {{-- @include('shared.errors') --}}

    <div class="box-body">
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-sm-offet-6">
          <form method="post" id="gateway_mode_toggle_form" action="{{url('admin/settings/gateway_mode_toggle')}}">
                    {!!csrf_field()!!}
                    <select  class="form-control" style="max-width:120px" name="config[mode]" onchange="$('#gateway_mode_toggle_form').submit()">
                      <option @if(config('gateway.mode')=="demo") selected="" @endif value="demo">Demo</option>
                      <option  @if(config('gateway.mode')=="live") selected="" @endif value="live">Live</option>
                    </select>
                  </form>
          <form action="{{ route('admin.settings.gateway', $setting) }}" method="post">
            {{ csrf_field() }}
            <?php $gateway = old('config.gateway') ? old('config.gateway') : config('gateway.gateway') ; ?>

                  @if($gateway =='authorize')
                  <div class="well">
                      <div class="text-right"> 
                          {{-- <a href="#showTransactionModal" data-toggle="modal"> 
                              <i class="fa fa-eye"></i></a>       --}}
                          <a href="#" id="show_gateway_loginID_transactionKey"><i
                              class="fa fa-pencil"></i></a> 
                          <a href="#" id="hide_gateway_loginID_transactionKey" style="display:none;" ><i
                              class="fa fa-pencil"></i></a>
                      </div>
                      <p><strong> Gateway Mode: </strong> {{ @$config['mode'] }}</p>
                      <p style="overflow-wrap:break-word;"><strong> Login ID: </strong> {{ old('config.authorize.login') ? old('config.authorize.login') : config('gateway.authorize.login') }}</p>
                      <p><strong> Transaction Key: </strong> xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
                  </div>
                  @elseif($gateway =='squareup')
                    <div class="well">
                      <div class="text-right"> 
                          {{-- <a href="#showTransactionModal" data-toggle="modal"> 
                              <i class="fa fa-eye"></i></a>       --}}
                          <a href="#" id="show_squareup_gateway_applicationID"><i
                              class="fa fa-pencil"></i></a> 
                          <a href="#" id="hide_squareup_gateway_applicationID" style="display:none;" ><i
                              class="fa fa-pencil"></i></a>
                      </div>
                      <p><strong> Gateway Mode: </strong> {{ @$config['mode'] }}</p>
                      <p><strong> Application ID: </strong> {{ old('config.squareup.application_id') ? old('config.squareup.application_id') : config('gateway.squareup.application_id') }}</p>
                      <p><strong> Access Token: </strong> xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
                  </div>
                  @elseif($gateway =='heartland')
                  <div class="well">
                    <div class="text-right"> 
                        {{-- <a href="#showTransactionModal" data-toggle="modal"> 
                            <i class="fa fa-eye"></i></a>       --}}
                        <a href="#" id="show_gateway_heartlandSecretKey"><i
                            class="fa fa-pencil"></i></a> 
                        <a href="#" id="hide_gateway_heartlandSecretKey" style="display:none;" ><i
                            class="fa fa-pencil"></i></a>
                    </div>
                    <p><strong> Gateway Mode: </strong> {{ @$config['mode'] }}</p>
                    <p style="overflow-wrap:break-word;"><strong> Public Key: </strong> {{ old('config.heartland.public') ? old('config.heartland.public') : config('gateway.heartland.public') }}</p>
                    <p><strong> Secret Key: </strong> xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
                </div>
                @elseif($gateway == 'stripe')
                <div class="well">
                  <div class="text-right"> 
                      {{-- <a href="#showTransactionModal" data-toggle="modal"> 
                          <i class="fa fa-eye"></i></a>       --}}
                      <a href="#" id="show_gateway_stripeSecretKey"><i
                          class="fa fa-pencil"></i></a> 
                      <a href="#" id="hide_gateway_stripeSecretKey" style="display:none;" ><i
                          class="fa fa-pencil"></i></a>
                  </div>
                  <p><strong> Gateway Mode: </strong> {{ @$config['mode'] }}</p>
                  @php 
                    if(config('gateway.stripe.publish'))
                    {
                      $first_five_publish_key_stripe  = substr(config('gateway.stripe.publish'), 0, 5);
                      $last_five_publish_key_stripe = substr(config('gateway.stripe.publish'), -5);
                      $publish_key_stripe = $first_five_publish_key_stripe.'*****'.$last_five_publish_key_stripe;
                    }else{
                      $publish_key_stripe = null;
                    }
                  @endphp
                  <p style="overflow-wrap:break-word;"><strong> Publishable Key: </strong> {{$publish_key_stripe}}</p>
                  <p><strong> Secret Key: </strong> xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
              </div>
                @elseif($gateway == 'braintree')
                <div class="well">
                  <div class="text-right"> 
                      {{-- <a href="#showTransactionModal" data-toggle="modal"> 
                          <i class="fa fa-eye"></i></a>       --}}
                      <a href="#" id="show_gateway_braintreeSecretKey"><i
                          class="fa fa-pencil"></i></a> 
                      <a href="#" id="hide_gateway_braintreeSecretKey" style="display:none;" ><i
                          class="fa fa-pencil"></i></a>
                  </div>
                  <p><strong> Gateway Mode: </strong> {{ @$config['mode'] }}</p>
                  <p style="overflow-wrap:break-word;"><strong> Public Key: </strong> {{ old('config.braintree.public_key') ? old('config.braintree.public_key') : config('gateway.braintree.public_key') }}</p>
                  <p style="overflow-wrap:break-word;"><strong> Merchant ID: </strong> {{ old('config.braintree.mechant_id') ? old('config.braintree.mechant_id') : config('gateway.braintree.mechant_id') }}</p>
                  <p><strong> Private Key: </strong> xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
              </div>
                  @endif


                  <div class="form-group hide">
                    <label for="input_name">Select Gateway</label>
                     <select name="config[gateway]" id="input_name" class="form-control select2" select-gateway>
                      <option value="" disabled>Select</option>
                      <option value="stripe" @if($gateway =='stripe') selected @endif>Stripe</option>
                      <option value="authorize" @if($gateway =='authorize') selected @endif>Authorize.net</option>
                      <option value="braintree" @if($gateway =='braintree') selected @endif>Braintree</option>

                      <option value="squareup" @if($gateway =='squareup') selected @endif>Squareup</option>

                      <option value="heartland" @if($gateway =='heartland') selected @endif>Heart Land</option>

                    </select>
                  </div>


                  <?php $mode = old('config.mode') ? old('config.mode') : config('gateway.mode') ?>
                   <div id="gateway" class="form-group" style="display:none;">
                    <label for="mode">Gateway Mode</label><br>
                    <select name="config[mode]" id="mode" class="form-control" class="form-control select2" >
                      <option value="">Select</option>
                      <option value="demo">Demo</option>
                      <option value="live">Live</option>
                    </select>
                  </div>

                   <?php $method = old('config.method') ? old('config.method') : config('gateway.method') ?>
                   <div class="form-group hide">
                    <label for="method">Payment Method</label>
                    <select name="config[method]" id="method" class="form-control select2" >
                      <option value="" disabled>Select</option>
                      <option value="charge" @if($method == 'charge') selected @endif>Charge Card</option>
                      <option value="create_profile" @if($method == 'create_profile') selected @endif>Create Customer Profile</option>
                    </select>
                  </div>



                  <div stripe-setting style="display:none;">

                    


                     <div id="stripepublishKey" style="display:none;" class="form-group">
                      <label for="key2">Publishable Key</label>
                      <input type="text" class="form-control" id="key2" name="config[stripe][publish]"
                      >
                    </div>

                    <div id="stripesecretKey" style="display:none;" class="form-group">
                      <label for="key1">Secret Key</label>
                      <input type="password" class="form-control" id="key1" name="config[stripe][secret]"
                      >
                    </div>

                  </div>
                  <div heartland-setting style="display:none;">

                    


                     <div id="publicKey" style="display:none;" class="form-group">
                      <label for="key2">Public Key</label>
                      <input type="text" class="form-control" id="key2" name="config[heartland][public]"
                      value="{{ old('config.heartland.public') ? old('config.heartland.public') : config('gateway.heartland.public') }}">
                    </div>

                    <div id="secretKey" style="display:none;" class="form-group">
                      <label for="key1">Secret Key</label>
                      <input type="password" class="form-control" id="key1" name="config[heartland][secret]"
                      value="{{ old('config.heartland.secret') ? old('config.heartland.secret') : config('gateway.heartland.secret') }}">
                    </div>

                  </div>


                  <div  braintree-setting style="display:none;">

                    <div id="braintreepublicKey" style="display:none;" class="form-group">
                      <label for="key1">Public Key</label>
                      <input type="text" class="form-control" id="key1" name="config[braintree][public_key]"
                      >
                    </div>
                    
                    <div id="braintreepriKey" style="display:none;" class="form-group">
                      <label for="key1">Private Key</label>
                      <input type="password" class="form-control" id="key1" name="config[braintree][private_key]"
                      >
                    </div>

                     

                     <div id="braintreemerchentid" style="display:none;" class="form-group">
                      <label for="key1">Merchant ID</label>
                      <input type="text" class="form-control" id="key1" name="config[braintree][mechant_id]"
                      >
                    </div>

                  </div>

                  <div authorize-setting style="display:none;">

                      <div id="loginID" style="display:none;" class="form-group">
                        <label for="key1">Login ID <span style="color: red">*</span></label>
                        <input type="text" readonly onfocus="this.removeAttribute('readonly');" class="form-control" autocomplete="off" id="key1" name="config[authorize][login]"
                        value="">
                      </div>

                      <div id="transactionKey" style="display:none;" class="form-group">
                        <label for="key1">Transaction Key <span style="color: red">*</span></label>
                        <input type="password" autocomplete="off" class="form-control" id="key1" name="config[authorize][key]"
                        value="">
                      </div>
                  </div>
                  
                  <div squareup-setting style="display:none;">
                    <div class="squareup-live">
                      <div class="form-group" id="showsquareupapplication_id" style="display:none;">
                        <label for="squareupapplication_id">Application ID</label>
                        <input type="text" class="form-control" id="squareupapplication_id" name="config[squareup][application_id]"
                        value="{{ old('config.squareup.application_id') ? old('config.squareup.application_id') : config('gateway.squareup.application_id') }}">
                      </div>
                        <div class="form-group" id="showsquareupaccess_token" style="display:none;">
                            <label for="squareupaccess_token">Access Token</label>
                            <input type="password" class="form-control" id="key2" name="config[squareup][access_token]"
                                   value="{{ old('config.squareup.access_token') ? old('config.squareup.access_token') : config('gateway.squareup.access_token') }}">
                        </div>
                        <div class="form-group" id="showsquareuplocation_id" style="display:none;">
                            <label for="squareuplocation_id">Location ID</label>
                            <input type="text" class="form-control" id="squareuplocation_id" name="config[squareup][location_id]"
                                   value="{{ old('config.squareup.location_id') ? old('config.squareup.location_id') : config('gateway.squareup.location_id') }}">
                        </div>
                    </div>





                </div>



            
            <div class="row m-t-20" style="display:none;" id="update_button">
              <div class="col-md-8">
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
              
            </div>

          </form>


        </div><!-- hreer -->
        @if (config('gateway.gateway' ) == 'authorize' || config('gateway.gateway' ) == 'stripe' || config('gateway.gateway' ) == 'squareup')
              <div class="col-md-6">
                <div class="form-group">
                  <br><button class="btn btn-primary" onclick="test_gateway_connection()" type="button">Test Gateway Connection</button>
                  <p><strong id="test_gateway_connection_response"></strong></p>
                </div>
              </div>
              <div class="col-lg-12">
                
              </div>
              @endif
      </div>
      
    </div>

  </div>

	</div>
	<!-- ./row -->

	</div>

	</div>
@stop

@include('settings.partials.show_tansaction_key_popup')

@section('javascript')

  <script>
    //abcdef
        $(document).on('submit', '#transaction_password_form', function(event) {
          event.preventDefault();
          $('#password_button').html("Please Wait....");
          $.ajax({
          url: "{{url('/')}}/admin/settings/confirm_password",
          type: 'POST',
          data: $('#transaction_password_form').serialize()
          })
          .done(function(response) {
              $('#password_button').html("Show <i class='fa fa-eye'></i>");
              if(response.status == true)
              {
                  $('#msg').html("<p style='color:green;'>"+response.transaction_key+"</p>");
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
          $("#show_gateway_loginID_transactionKey").click(function(){
  
            $("#gateway").show();
            $("#loginID").show();
            $("#transactionKey").show();
            $("#update_button").show();
            $("#show_gateway_loginID_transactionKey").hide();
            $("#hide_gateway_loginID_transactionKey").show();
          });    

          $("#hide_gateway_loginID_transactionKey").click(function(){
              $("#gateway").hide();
              $("#loginID").hide();
              $("#transactionKey").hide();
              $("#update_button").hide();
              $("#show_gateway_loginID_transactionKey").show();
              $("#hide_gateway_loginID_transactionKey").hide();
          });
          $("#show_squareup_gateway_applicationID").click(function(){
            $("#gateway").show();
            $("#showsquareupapplication_id").show();
            $("#showsquareupaccess_token").show();
            $("#showsquareuplocation_id").show();
            $("#update_button").show();
            $("#show_squareup_gateway_applicationID").hide();
            $("#hide_squareup_gateway_applicationID").show();
          });    

          $("#hide_squareup_gateway_applicationID").click(function(){
              $("#gateway").hide();
              $("#showsquareupapplication_id").hide();
              $("#showsquareupaccess_token").hide();
              $("#showsquareuplocation_id").hide();
              $("#update_button").hide();
              $("#show_squareup_gateway_applicationID").show();
              $("#hide_squareup_gateway_applicationID").hide();
            });    
          $("#show_gateway_heartlandSecretKey").click(function(){
  
            $("#gateway").show();
            $("#secretKey").show();
            $("#publicKey").show();
            $("#update_button").show();
            $("#show_gateway_heartlandSecretKey").hide();
            $("#hide_gateway_heartlandSecretKey").show();
          });    

          $("#hide_gateway_heartlandSecretKey").click(function(){
              $("#gateway").hide();
              $("#secretKey").hide();
              $("#publicKey").hide();
              $("#update_button").hide();
              $("#show_gateway_heartlandSecretKey").show();
              $("#hide_gateway_heartlandSecretKey").hide();
          });
          $("#show_gateway_stripeSecretKey").click(function(){
  
            $("#gateway").show();
            $("#stripepublishKey").show();
            $("#stripesecretKey").show();
            $("#update_button").show();
            $("#show_gateway_stripeSecretKey").hide();
            $("#hide_gateway_stripeSecretKey").show();
          });    

          $("#hide_gateway_stripeSecretKey").click(function(){
              $("#gateway").hide();
              $("#stripesecretKey").hide();
              $("#stripepublishKey").hide();
              $("#update_button").hide();
              $("#show_gateway_stripeSecretKey").show();
              $("#hide_gateway_stripeSecretKey").hide();
          });
          $("#show_gateway_braintreeSecretKey").click(function(){
  
            $("#gateway").show();
            $("#braintreepublicKey").show();
            $("#braintreeprivateKey").show();
            $("#braintreemerchentid").show();
            $("#update_button").show();
            $("#show_gateway_braintreeSecretKey").hide();
            $("#hide_gateway_braintreeSecretKey").show();
          });    

          $("#hide_gateway_braintreeSecretKey").click(function(){
              $("#gateway").hide();
              $("#braintreepublicKey").hide();
              $("#braintreeprivateKey").hide();
              $("#braintreemerchentid").hide();
              $("#update_button").hide();
              $("#show_gateway_braintreeSecretKey").show();
              $("#hide_gateway_braintreeSecretKey").hide();
          });
        });

      $('[{{$gateway}}-setting]').show();

     $('[select-gateway]').change(function(){
       var selectedOption = $(this).find(':selected').val();



       if(selectedOption == 'stripe'){
         $('[stripe-setting]').show();
         $('[braintree-setting]').hide();
         $('[authorize-setting]').hide();

         $('[squareup-setting]').hide();

         $('[heartland-setting]').hide();

       }

        if (selectedOption == 'authorize'){
          $('[authorize-setting]').show();
         $('[stripe-setting]').hide();
         $('[braintree-setting]').hide();

         $('[squareup-setting]').hide();

         $('[heartland-setting]').hide();

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

         $('[heartland-setting]').hide();
       }
        if (selectedOption == 'heartland'){
         $('[heartland-setting]').show();
         $('[stripe-setting]').hide();
         $('[authorize-setting]').hide();
         $('[braintree-setting]').hide();

       }
     });
   </script>

   <script type="text/javascript">
     function test_gateway_connection() {
        $('#test_gateway_connection_response').css('color', 'red').html('Please wait...');
       $.ajax({
         url: '{{url("admin/test_gateway_connection")}}',
         
       })
       .done(function() {
        $('#test_gateway_connection_response').css('color', 'green');
         $('#test_gateway_connection_response').html("Connected");
       })
       .fail(function(response) {
          console.log(response);
          $('#test_gateway_connection_response').html("Not Connected. Error Message: "+response.responseJSON.message);
       })
       .always(function() {
         console.log("complete");
       });
       
     }
   </script>

@stop
