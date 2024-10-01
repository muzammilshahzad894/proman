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
                        <div class="row">
                            @include('admin.reservation.partials._form')
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
<script>
    $(document).on('change', '#payment_mode', function(event) {
        if ($(this).val() == 'credit card') {
            $('#credit_card_section').show();
        } else {
            $('#credit_card_section').hide();
        }

    });

    $('#payment_mode').trigger('change');
 
    $line_items = [];
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


                });;

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
        var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        var firstDate = new Date($('#arrival').val());
        var secondDate = new Date($('#departure').val());
        var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime()) / (oneDay)));
        return diffDays;
    }

    function daysInArrival() {
        var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        var firstDate = new Date();
        var secondDate = new Date($('#arrival').val());
        var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime()) / (oneDay))) + 1;
        return diffDays;
    }

    function calcLodgingAmount() {
        var amount = $("#property_rate").val();
        console.log('amount ' + amount);
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
        if (propertyData.sales_tax_active == 1) {
            sales_tax = propertyData.sales_tax * (parseFloat($("#lodging_amount").val()) + pet_fee) / 100;
            console.log('839 ' + sales_tax);
            sales_tax = parseFloat(sales_tax).toFixed(2);
            console.log('841 ' + sales_tax);
            if (isNaN(sales_tax)) {
                sales_tax = 0;
            }
        };

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
                    console.log('percentage ' + $percentage);
                    line_item_total = parseFloat($percentage);
                    total_amount = total_amount + parseFloat($percentage);
                };
                var text = '<p>' + $line_items[i].title + ': ' + line_item_total + '</p>';
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