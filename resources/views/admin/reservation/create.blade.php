@extends('layouts.default')

@section('title')
Add Reservation
@parent
@stop

@section('css')

<style type="text/css">
    .field-required:after {
        content: " *";
        color: red;
    }
    .hide {
        display: none;
    }
    .input-3rd {
        max-width: 33% !important;
    }
</style>
@stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">
                            Add Reservation ( {{ @$property->title }} )
                        </h3>
                    </div>
                    <div class="box-body">
                        @include('shared.errors')
                        <div id="show-messages"></div>
                        <div class="row">
                            <form action="{{ url('admin/reservation') }}" method="POST" role="form" enctype="multipart/form-data" class="ajax-submission">
                                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                    @csrf
                                    <input type="hidden" name="property_id" value="{{ $property->id }}">

                                    <div class="checkbox customerinfo">
                                        <label>
                                            <input 
                                                type="checkbox" 
                                                value="1" 
                                                name="returning_customer_checkbox" 
                                                id="returning-customer" 
                                                @if(old('returning_customer_checkbox')==1) checked @endif
                                            /> Returning Customer
                                        </label>
                                    </div>

                                    <div class="form-group " id="select-returning-customer" style="display: none;">
                                        <label for="">Select Customer</label>
                                        <select name="customer_id" id="input" class="form-control" style="width: 100%;  ">
                                            <option value="">Select</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name() }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row customerinfo">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="field-required">First Name</label>
                                                <input 
                                                    autocomplete="first_name_off" 
                                                    type="text" 
                                                    name="first_name" 
                                                    class="form-control" 
                                                    required 
                                                    value="{{ old('first_name') }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="field-required">Last Name</label>
                                                <input 
                                                    type="text" 
                                                    autocomplete="last_name_off" 
                                                    name="last_name" 
                                                    class="form-control" 
                                                    required 
                                                    value="{{ old('last_name') }}"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row customerinfo">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="field-required">Email</label>
                                                <input 
                                                    autocomplete="email_off" 
                                                    type="text" 
                                                    name="email" 
                                                    class="form-control" 
                                                    required 
                                                    value="{{ old('email') }}"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label class="field-required">Phone</label>
                                                <input 
                                                    autocomplete="phone_email" 
                                                    type="text" 
                                                    name="phone" 
                                                    required 
                                                    class="form-control phone_us" 
                                                    value="{{ old('phone') }}"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="addresss well" style="display: none;">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label class="field-required">Address</label>
                                                    <input 
                                                        autocomplete="address_off" 
                                                        type="text" 
                                                        name="address" 
                                                        required 
                                                        class="form-control"
                                                        value="{{ old('address') }}" 
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="field-required">City</label>
                                                        <input 
                                                            autocomplete="city_off" 
                                                            type="text" 
                                                            name="city" 
                                                            value="{{ old('city') }}" 
                                                            class="form-control" 
                                                            required
                                                        />
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="form-group ">
                                                        <label class="field-required">State</label>
                                                        <select required name="state" id="input" class="form-control">
                                                            <option value="">Select</option>
                                                            @foreach (states() as $key => $state)
                                                                <option @if(old('state')==$key) selected @endif value="{{ $key }}">{{ $state }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="form-group ">
                                                        <label class="field-required">Zip</label>
                                                        <input 
                                                            autocomplete="zip_off" 
                                                            name="zip" 
                                                            type="text" 
                                                            value="{{ old('zip') }}" 
                                                            class="form-control zipcode" 
                                                            required
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label class="field-required">Adults</label>
                                                <input 
                                                    name="adults" 
                                                    type="text" 
                                                    data-calc="true" 
                                                    class="form-control" 
                                                    onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                    value="{{ old('adults') }}" 
                                                    required
                                                />
                                                <p class="help-block">&nbsp;</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="">Children</label>
                                                <input 
                                                    type="text" 
                                                    name="children" 
                                                    data-calc="true" 
                                                    class="form-control" 
                                                    onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
                                                    value="{{ old('children') }}"
                                                />
                                                <p class="help-block">&nbsp;</p>
                                            </div>
                                        </div>
                                        @if($property->is_pet_friendly)
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="">Pets</label>
                                                <input 
                                                    name="pets" 
                                                    type="text" 
                                                    data-calc="true" 
                                                    class="form-control" 
                                                    onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
                                                    value="{{ old('pets') }}"
                                                />
                                                <p class="help-block">(Please add details in notes)</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                            <div class="form-group ">
                                                <label for="">Arival</label>
                                                <input 
                                                    type="text" 
                                                    data-calc="true" 
                                                    autocomplete="off" 
                                                    class="form-control arrivalDate"
                                                    id="arrival" 
                                                    name="arrival"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                            <div class="form-group ">
                                                <label for="">Departure</label>
                                                <input 
                                                    type="text" 
                                                    data-calc="true" 
                                                    class="form-control departureDate"
                                                    id="departure" 
                                                    name="departure" 
                                                    autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-danger" style="display: none; ">
                                    </div>
                                    
                                    <div class="">
                                        <div class="m-t-10 m-b-20">
                                            <div class="form-group input-3rd">
                                                <label for="">Lodging Amount</label>
                                                <input 
                                                    type="hidden" 
                                                    id="property_rate" 
                                                    value="{{ number_format_without_comma(@$property->seasons()->find(getCurrentSeason()->id)->pivot->daily_rate) }}"
                                                />
                                                <div class="input-group">
                                                    <div class="input-group-addon">$</div>
                                                    <input 
                                                        type="text" 
                                                        name="lodging_amount" 
                                                        class="form-control"
                                                        id="lodging_amount" 
                                                        readonly="" 
                                                        value="0" 
                                                        onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
                                                        data-calc="true"
                                                    />
                                                </div>
                                            </div>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="special_rate" data-calc="true" id="chk_special_rate" value="1"> Special Rate
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" data-calc="true" name="non_profit" id="non_profit" value="option2"> Non-Profit Reservation
                                            </label>
                                            <input type="hidden" name="pet_fee" id="pet_fee_value">
                                            @if($property->pet_fee_active==1)
                                            <label class="checkbox-inline">
                                                <input name="is_add_pet_fee" type="checkbox" data-calc="true" id="chk_add_pet_fee" value="option3"> Add Pet Fee
                                            </label>
                                            @endif
                                            <div class="form-group m-t-10 input-half" style="display: none;" id="special_rates_notes">
                                                <label for=""> Special Rates Notes</label>
                                                <textarea name="special_rates_notes" id="input" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Sum Detail</label>
                                            <div class="well">
                                                <p>
                                                    Lodging: <span id="lodging">$0</span>
                                                </p>

                                                <p id="p_pet_fee" style="display: none;">
                                                    Pet Fee: $<span id="pet_fee" data-fee="{{ empty($property->pet_fee)?0:$property->pet_fee }}">@if($property->pet_fee_active==1){{ $property->pet_fee }} @endif</span>
                                                </p>
                                                <p>
                                                    Lodgers Tax (@if($property->lodger_tax_active==1){{ $property->lodger_tax }} @endif%): $<span id="lodgers_text_label"></span>
                                                </p>
                                                @if ($property->sales_tax_active == 1 )
                                                <p>
                                                    Sales Tax ({{ $property->sales_tax }} %): $<span id="sales_text_label"></span>
                                                </p>
                                                <input type="hidden" name="sales_tax" id="input-sales_tax" class="form-control" value="">
                                                @endif
                                            </div>
                                        </div>
                                        <input type="hidden" name="lodgers_tax" id="inputLodgers_tax" class="form-control" value="">
                                        <div class="form-group input-3rd">
                                            <label for="">Cleaning Fee</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">$</div>
                                                <input type="text" data-calc="true" class="form-control" id="clearing_fee" placeholder=""
                                                onkeypress="return isNumber(event)"
                                                name="clearing_fee"
                                                value="@if($property->clearing_fee_active==1){{number_format($property->clearing_fee,2)}}@endif">
                                            </div>
                                        </div>
                                        <div class="form-group input-3rd">
                                            <label for="">Total Amount</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">$</div>
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    id="total_amount" 
                                                    onkeypress="return isNumber(event)"
                                                    name="total_amount" 
                                                    value="{{ old('total_amount') }}"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group input-3rd">
                                            <label for="">Amount To Deposit</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">$</div>
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    id="amount_deposited"
                                                    onkeypress="return isNumber(event)"
                                                    name="amount_deposited" 
                                                    value="{{ old('amount_deposited') }}"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group input-3rd">
                                            <label for="">Method of Payment</label>
                                            <select name="payment_mode" id="payment_mode" class="form-control">
                                                <option value="">Select</option>
                                                <option @if(old('payment_mode')=='check' ) selected="" @endif value="check">Check</option>
                                                <option @if(old('payment_mode')=='credit card' ) selected="" @endif value="credit card">Credit Card</option>
                                                <option @if(old('payment_mode')=='owner' ) selected="" @endif value="check">Owner</option>
                                            </select>
                                        </div>
                                        <section id="credit_card_section">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="control-label field-required" for="">Credit Card</label>
                                                        <input 
                                                            type="text" 
                                                            value="{{old('credit_card') ? old('credit_card') : (isset($reservation) ? $reservation['credit_card'] : '') }}" 
                                                            class="form-control" 
                                                            name="credit_card"
                                                        />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="control-label" for=""> &nbsp;</label>
                                                        <img class="img img-responsive" src="{{asset('img/creditcards.png')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="control-label   field-required" for="">Expiry</label>
                                                    </div>
                                                    <div class="col-sm-3 col-xs-6">
                                                        <select class="form-control " name="expiry_month" style="font-size: 12px" title="select a month">
                                                            <option value="01">January</option>
                                                            <option value="02">February</option>
                                                            <option value="03">March</option>
                                                            <option value="04">April</option>
                                                            <option value="05">May</option>
                                                            <option selected="" value="06">June</option>
                                                            <option value="07">July</option>
                                                            <option value="08">August</option>
                                                            <option value="09">September</option>
                                                            <option value="10">October</option>
                                                            <option value="11">November</option>
                                                            <option value="12">December</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 col-xs-6">
                                                        <select style="font-size: 12px" name="expiry_year" title="select a year" class="form-control">
                                                            @foreach(expiration_years() as $index => $expiration_year)
                                                                <option @if(old("card_expiry_year")==$expiration_year) selected="" @elseif($index==5) selected="" @endif value="{{$expiration_year}}">{{$expiration_year}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-3 col-xs-6">
                                                        <label class="control-label   field-required" for="">CVV</label>
                                                        <input type="text'" value="{{old('cvv') ? old('cvv') : (isset($reservation) ? $reservation['cvv'] : '') }}" class="form-control " name="cvv" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>

                                    <div class="form-group input-half">
                                        <label class="field-required">Select Housekeeper</label>
                                        <select name="housekeeper_id" id="state" class="form-control" required>
                                            <option value="">Select </option>
                                            @foreach($house_keepers as $house_keeper)
                                                <option value="{{ $house_keeper->id }}">{{ $house_keeper->first_name }} {{ $house_keeper->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group input-half">
                                        <label for="">Status</label>
                                        <select name="status" id="input" class="form-control">
                                            <option value="">Select</option>
                                            <option value="1">Booked</option>
                                            <option value="0">Pending</option>
                                        </select>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="1" name="dont_send_email">
                                            Do not send email.
                                        </label>
                                    </div>
                                    <div class="m-b-20 m-t-20">
                                        <button type="submit" class="btn btn-success">Add</button>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                                    <iframe src="/calendar/{{ $property->id }}" width="100%" height="922" frameborder="0"></iframe>
                                    <div class="form-group ">
                                        <label for="">Notes</label>
                                        <textarea name="notes" id="input" class="form-control" rows="5">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div> <!-- content-wrapper -->

<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@stop
@section('javascript')
@include('layouts.js.ajax-form-submission')
<script>
    $(document).on('change', '#payment_mode', function(event) {
        if ($(this).val() == 'credit card') {
            $('#credit_card_section').show();
        } else {
            $('#credit_card_section').hide();
        }
    });

    $('#payment_mode').trigger('change');
    $line_items = {!! json_encode(\App\Models\LineItem::all()) !!};

    var propertyData = {!!json_encode( $property)!!};
    var owner_reservatio = false;
    $select2done = false;

    $('#returning-customer').on('change', function(event) {
        event.preventDefault();

        if ($(this).is(':checked')) {
            $('#select-returning-customer').show();

            if ($select2done == false) {
                $select2done = true;
                $('#select-returning-customer select').select2().on('change', function(e) {
                    var id = $(this).val();
                    $.ajax({
                        url: "{{url('/')}}" + '/admin/get-customer',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            id: id
                        },
                    })
                    .done(function(data) {
                        console.log(data);
                        updateCustomer(data);
                    })
                    .fail(function() {
                        console.log("error get-customer");
                    })
                    .always(function() {
                        console.log("complete get-customer");
                    });
                });
            };
        } else {
            $('#select-returning-customer').hide();
            $('input[name="first_name"]').attr('readonly', false);
            $('input[name="last_name"]').attr('readonly', false);
            $('input[name="email"]').attr('readonly', false);
            $('input[name="phone"]').attr('readonly', false);
        };

    });

    function updateCustomer(data) {
        $('input[name="first_name"]').val(data.first_name).attr('readonly', true);
        $('input[name="last_name"]').val(data.last_name).attr('readonly', true);
        $('input[name="email"]').val(data.email).attr('readonly', true);
        $('input[name="phone"]').val(data.phone).attr('readonly', true);
    }
    
    $('#get-complete-address').on('change', function(event) {
        event.preventDefault();
        if ($(this).is(':checked')) {
            $('.addresss').show();
        } else {
            $('.addresss').hide();
        };
    });

    $('.addresss').show();

    function getDays() {
        var oneDay = 24 * 60 * 60 * 1000;
        var firstDate = new Date($('#arrival').val());
        var secondDate = new Date($('#departure').val());
        var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime()) / (oneDay)));
        console.log('getDays ' + diffDays);
        return diffDays;
    }

    function daysInArrival() {
        var oneDay = 24 * 60 * 60 * 1000;
        var firstDate = new Date();
        var secondDate = new Date($('#arrival').val());
        var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime()) / (oneDay))) + 1;
        return diffDays;
    }

    function calcLodgingAmount() {
        var amount = $("#property_rate").val();
        return amount;
    }

    function getRate() {
        $.ajax({
            method: "GET",
            url: "{{ url('admin/seasonrate-daily') }}",
            data: {
                property_id: "{{ $property->id }}",
                from_date: $('#arrival').val(),
                to_date: $('#departure').val()
            }
        })
        .done(function(data) {
            if (owner_reservatio) {
                data = 0;
            };
            if (data.status == 'error' && owner_reservatio == false) {
                showAlert(data.error);
                return;
            };

            hideAlert();
            $("#property_rate").val(data);
            $("#lodging_amount").val(calcLodgingAmount());
            updateCalculations();
        }).fail(function() {
            /* Act on the event */
        });
    }

    $(document).ready(function() {
        <?php $datesDisabled = ''; ?>
        $arrivalDate = $('.arrivalDate');
        $arrivalDate.daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            disabledDates: [{!! $datesDisabled !!}],
        });

        $departureDate = $('.departureDate');
        $departureDate.daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: moment($arrivalDate.val(), "MM-DD-YYYY").add(1, 'days'),
            disabledDates: [{!! $datesDisabled !!}],
        });

        $arrivalDate.change(function() {
            setReturnStartDate();
        });

        function setReturnStartDate() {
            if ($.trim($arrivalDate.val()) == "") {
                // nothing
            } else {
                $departureDate.daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    minDate: moment($arrivalDate.val(), "MM-DD-YYYY").add(1, 'days'),
                    startDate: moment($arrivalDate.val(), "MM-DD-YYYY").add(1, 'days')
                });
            }
        }
        
        $("#lodging_amount").val(calcLodgingAmount());

        $("#chk_special_rate").change(function() {
            if ($("#lodging_amount").is('[readonly]')) {
                $("#lodging_amount").attr('readonly', false);
                $("#special_rates_notes").show();
            } else {
                $("#lodging_amount").attr('readonly', true);
                $("#special_rates_notes").hide();
            }
        });

        $("#chk_add_pet_fee").change(function() {
            if ($(this).is(':checked')) {
                $("#p_pet_fee").show();
                updateCalculations();
            } else {
                $("#p_pet_fee").hide();
                updateCalculations();
            }
        });

        $('#arrival').change(function() {
            getRate();
        });

        $('#departure').change(function() {
            getRate()
        });

        $("input").change(function() {
            if ($(this).data('calc') == true) {
                updateCalculations();
            }
        });
    });

    function updateCalculations() {
        $("#lodging").text('$' + $("#lodging_amount").val());
        var clearing_fee = parseFloat($("#clearing_fee").val());
        clearing_fee = clearing_fee ? clearing_fee.toFixed(2) : 0.00;

        var pet_fee = 0;
        
        if ($("#chk_add_pet_fee").is(':checked')) {
            pet_fee = parseFloat($("#pet_fee").data('fee'));
        } else {
            pet_fee = 0;
        }
        $('#pet_fee').text(pet_fee.toFixed(2));

        var lodgers_tax = 0;
        // if lodger_tax_active
        if (propertyData.lodger_tax_active == 1) {
            lodgers_tax = propertyData.lodger_tax * parseFloat($("#lodging_amount").val()) / 100;
            lodgers_tax = parseFloat(lodgers_tax).toFixed(2);
            if (isNaN(lodgers_tax)) {
                lodgers_tax = 0;
            }
        };
        $("#lodgers_text_label").text(lodgers_tax);
        $("#inputLodgers_tax").val(lodgers_tax);

        var sales_tax = 0;
        // if sales tax active
        if (propertyData.sales_tax_active == 1) {
            sales_tax = propertyData.sales_tax * (parseFloat($("#lodging_amount").val()) + pet_fee) / 100;
            sales_tax = parseFloat(sales_tax).toFixed(2);
            if (isNaN(sales_tax)) {
                sales_tax = 0;
            }
        };

        $("#sales_text_label").text(sales_tax);
        $("#input-sales_tax").val(sales_tax);

        //total amount
        var lodging_amount = parseFloat($("#lodging_amount").val());

        if ($("#non_profit").is(':checked')) {
            lodgers_tax = 0;
            sales_tax = 0;
            pet_fee = 0;

            $("#lodgers_text_label").text(lodgers_tax);
            $("#sales_text_label").text(sales_tax);
            $('#pet_fee').text(pet_fee);
            $("#inputLodgers_tax").val(lodgers_tax);
        }

        var total_amount = parseFloat($("#lodging_amount").val()) + parseFloat(clearing_fee) + parseFloat(lodgers_tax) + parseFloat(sales_tax) + parseFloat(pet_fee);

        if (typeof $line_items !== 'undefined' && $line_items.length > 0) {
            for (i = 0; i < $line_items.length; i++) {
                var line_item_total = 0;
                
                if ($line_items[i].type == 'Fixed') {
                    total_amount = total_amount + parseFloat($line_items[i].value);
                    line_item_total = parseFloat($line_items[i].value);
                };
                if ($line_items[i].type == 'Percentage') {
                    $percentage = total_amount * parseFloat($line_items[i].value) / 100;
                    line_item_total = parseFloat($percentage);
                    total_amount = total_amount + parseFloat($percentage);
                };
            }
        };

        if (daysInArrival() > 30) {
            $amount_deposited = parseFloat(total_amount) / 2;
        } else {
            $amount_deposited = total_amount;
        };

        $("#amount_deposited").val(parseFloat($amount_deposited).toFixed(2));

        $("#total_amount").val(parseFloat(total_amount).toFixed(2));
        $('#summary').html("");
        $('#summary').append("<p>Cleaning Fee: " + to_currency_js(clearing_fee) + "</p>");
        $('#summary').append("<p>Pet Fee: " + to_currency_js(pet_fee) + "</p>");
        $('#summary').append("<p>Lodging Amount: " + to_currency_js(lodging_amount) + "</p>");
        $('#summary').append("<p>Lodgers Tax: " + to_currency_js(lodgers_tax) + "</p>");
        $('#summary').append("<p>Sales Tax: " + to_currency_js(sales_tax) + "</p>");
        $('#summary').append("<p>Total Amount: " + to_currency_js(total_amount) + "</p>");
        $('#pet_fee_value').val(pet_fee);

    }

    function to_currency_js(amount) {
        var float_amount = parseFloat(amount).toFixed(2);
        return "$" + float_amount;
    }

    function showAlert(text) {
        $('.alert').text(text);
        $('.alert').show();
        $('.btn.btn-success').attr('disabled', true);
    }

    function hideAlert() {
        $('.alert').hide();
        $('.btn.btn-success').attr('disabled', false);
    }
</script>

<style>
    .table-container {
        width: 581px;
        margin-left: auto;
        margin-right: auto;
    }

    .table.property-calendar td span {

        line-height: 38px;
    }

    .table.property-calendar td {
        height: 37px;
        width: 33px;
        text-align: center;
        position: relative;
        padding: 0;
        line-height: 2.228571;
    }

    .table.property-calendar td span:before {
        border: 19px solid transparent;
    }

    .booked {
        background: #ff0000;
        border: 1px solid #ccc;
    }

    .pending {
        background: #ffff00;
        border: 1px solid #ccc;
    }

    .owner {
        background: #999999;
        color: #fff;
        border: 1px solid #ccc;
    }

    .available {
        background: #fff;
        border: 1px solid #ccc;
    }

    .calendar-info-labels {
        text-align: center;
        line-height: 20px;
    }

    .calendar-info-labels .labels {
        display: inline-block;
        vertical-align: middle;
        margin: 0 5px;
    }

    .calendar-info-labels span {
        display: inline-block;
        vertical-align: middle;
        color: #fabc03;
        font-size: 10px;
        text-transform: capitalize;
    }

    .calendar-info-labels .color-box {
        height: 15px;
        width: 22px;
    }
</style>
@stop