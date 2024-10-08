<form action="{{ $url }}" method="POST" role="form" enctype="multipart/form-data">
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
        @csrf
        <input type="hidden" name="property_id" value="{{ @$property->id }}">
        
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
                        value="{{ old('first_name') ? old('first_name') : (isset($reservation) ? @$reservation->customer->first_name : '') }}">
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
                        value="{{ old('last_name') ? old('last_name') : (isset($reservation) ? @$reservation->customer->last_name : '') }}">
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
                        value="{{ old('email') ? old('email') : (isset($reservation->customer->email) ? $reservation->customer->email : '') }}">
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
                        value="{{ old('phone') ? old('phone') : (isset($reservation->phone) ? $reservation->phone : '') }}">
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
                            class="form-control"
                            required
                            value="{{ old('address') ? old('address') : (isset($reservation->address) ? $reservation->address : '') }}"
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
                                class="form-control"
                                required
                                value="{{ old('city') ? old('city') : (isset($reservation->city) ? $reservation->city : '') }}"
                            />
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group ">
                            <label class="field-required">State</label>
                            <select required name="state" id="input" class="form-control">
                                <option value="">Select</option>
                                @foreach ( states() as $key => $state)
                                <option  
                                    @if(old('state') ? (old('state') == $key) : (isset($reservation->state) ? $reservation->state == $key : false)) selected @endif 
                                    value="{{ $key }}"
                                >
                                    {{ $state }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label class="field-required">Zip</label>
                            <input 
                                autocomplete="zip_off" 
                                name="zip" 
                                type="text" 
                                class="form-control zipcode"
                                required
                                value="{{ old('zip') ? old('zip') : (isset($reservation->zip) ? $reservation->zip : '') }}"
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
                        required
                        onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                        value="{{ old('adults') ? old('adults') : (isset($reservation->adults) ? $reservation->adults : '') }}"
                    />
                    <p class="help-block">&nbsp;</p>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <label>Children</label>
                    <input 
                        type="text" 
                        name="children" 
                        data-calc="true" 
                        class="form-control" 
                        onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
                        value="{{ old('children') ? old('children') : (isset($reservation->children) ? $reservation->children : '') }}"
                    />
                    <p class="help-block">&nbsp;</p>
                </div>
            </div>
            @if ($property->is_pet_friendly)
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="form-group">
                    <label>Pets</label>
                    <input 
                        name="pets" 
                        type="text" 
                        data-calc="true" 
                        class="form-control" 
                        onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
                        value="{{ old('pets') ? old('pets') : (isset($reservation->pets) ? $reservation->pets : '') }}"
                    />
                    <p class="help-block">(Please add details in notes)</p>
                </div>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <div class="form-group ">
                    <label>Arival</label>
                    <input 
                        type="text" 
                        data-calc="true" 
                        autocomplete="off" 
                        class="form-control arrivalDate"
                        id="arrival" 
                        name="arrival"
                        value="{{ old('arrival') ? date('m/d/Y', strtotime(old('arrival'))) : (isset($reservation->arrival) ? date('m/d/Y', strtotime($reservation->arrival)) : '') }}"
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
                        value="{{ old('departure') ? date('m/d/Y', strtotime(old('departure'))) : (isset($reservation->departure) ? date('m/d/Y', strtotime($reservation->departure)) : '') }}"
                    />
                </div>
            </div>

        </div>

        <div class="alert alert-danger" style="display: none; ">
        </div>

        <div class="">
            <div class="m-t-10 m-b-20">
                <div class="form-group input-3rd">
                    <label for="">Lodging Amount ($)</label>
                    <input
                        type="hidden"
                        id="property_rate"
                        value="{{ number_format_without_comma(@$property->seasons()->find(getCurrentSeason()->id)->pivot->daily_rate) }}"
                    />
                    <input
                        type="text"
                        name="lodging_amount"
                        class="form-control"
                        id="lodging_amount"
                        readonly=""
                        placeholder="" value="0"
                        onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                        data-calc="true"
                    />
                </div>

                <label class="checkbox-inline">
                    <input type="checkbox" name="special_rate" data-calc="true" id="chk_special_rate" value="1"> Special Rate
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" data-calc="true" name="non_profit" id="non_profit" value="option2"> Non-Profit Reservation
                </label>
                <input type="hidden" name="pet_fee" id="pet_fee_value">
                @if (@$property->pet_fee_active==1)
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
                        Pet Fee: $<span id="pet_fee" data-fee="{{ empty(@$property->pet_fee)?0:@$property->pet_fee }}">@if(@$property->pet_fee_active==1){{ @$property->pet_fee }} @endif</span>
                    </p>
                    <p>
                        Lodgers Tax (@if(@$property->lodger_tax_active==1){{ @$property->lodger_tax }} @endif%): $<span id="lodgers_text_label"></span>
                    </p>
                    @if (@$property->sales_tax_active == 1 )
                    <p>
                        Sales Tax ({{ @$property->sales_tax }} %): $<span id="sales_text_label"></span>
                    </p>
                    <input type="hidden" name="sales_tax" id="input-sales_tax" class="form-control" value="">
                    @endif
                </div>
            </div>
            <input
                type="hidden"
                name="lodgers_tax"
                id="inputLodgers_tax"
                class="form-control"
                value=""
            />
            <div class="form-group input-3rd">
                <label for="">Cleaning Fee</label>
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input 
                        type="text" 
                        data-calc="true" 
                        class="form-control" 
                        id="clearing_fee"
                        onkeypress="return isNumber(event)"
                        name="clearing_fee"
                        value="{{ old('clearing_fee') ? old('clearing_fee') : (isset($reservation) ? to_currency($reservation->cleaning_fee,0) : '') }}"
                    />
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
                        value="{{ old('total_amount') ? old('total_amount') : (isset($reservation) ? to_currency($reservation->total_amount,0) : '') }}"
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
                        value="{{ old('amount_deposited') ? old('amount_deposited') : (isset($reservation) ? to_currency($reservation->amount_deposited,0) : '') }}"
                    />
                </div>
            </div>
            <div class="form-group input-3rd">
                <label for="">Method of Payment</label>
                <select name="payment_mode" id="payment_mode" class="form-control">
                    <option value="">Select</option>
                    <option @if((old('payment_mode') ? (old('payment_mode') == 'check') : (isset($reservation) ? $reservation->basic_payment()->payment_mode == 'check' : false))) selected @endif value="check">Check</option>
                    <option @if((old('payment_mode') ? (old('payment_mode') == 'credit card') : (isset($reservation) ? $reservation->basic_payment()->payment_mode == 'credit card' : false))) selected @endif value="credit card">Credit Card</option>
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
                                name="credit_card">
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
                            <label class="control-label field-required" for="">Expiry</label>
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
        @if(Auth::user()->type != "owner")
        <div class="form-group input-half">
            <label for="">Select Housekeeper</label>
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
        <!-- end -->
        @endif
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" name="dont_send_email">
                Do not send email.
            </label>
        </div>
        <div class="m-b-20 m-t-20">
            <button type="submit" class="btn btn-success">{{ $submitBtnText }}</button>
        </div>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
        {!! $calendar !!}
        <div class="form-group ">
            <label for="">Notes</label>
            <textarea name="notes" id="input" class="form-control" rows="5">{{ old('notes') }}</textarea>
        </div>
    </div>
</form>