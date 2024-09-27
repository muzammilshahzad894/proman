<form action="{{ $url }}" method="POST" role="form" enctype="multipart/form-data" class="ajax-submission">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="box-title">Add Property</h3>
                            <p class="m-t-10">
                                <a href="{{ url('/admin/properties') }}" class="btn btn-default">Properties List</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    @include('shared.errors')
                    <div id="show-messages"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend>General Details</legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="company_name" class="field-required">Name</label>
                                                    <input
                                                        name="title"
                                                        type="text"
                                                        class="form-control"
                                                        id="company_name" 
                                                        value="{{ old('title') ? old('title') : (isset($property) ? $property->title : '') }}"
                                                        autofocus="autofocus"
                                                        placeholder="Enter property name">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Property Type</label>
                                                    <div>
                                                        <label class="radio-inline">
                                                            <input 
                                                                type="radio" 
                                                                id="inlineCheckbox1" 
                                                                value="is_vacation" 
                                                                name="property_type" 
                                                                data-property_type 
                                                                checked
                                                            /> Vacation
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input 
                                                                type="radio" 
                                                                id="inlineCheckbox2" 
                                                                value="is_long_term" 
                                                                name="property_type" 
                                                                data-property_type
                                                                @if(old('property_type') ? old('property_type') == 'is_long_term' : (isset($property) ? $property->is_long_term == '1' : false)) checked @endif
                                                            /> Long Term
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="title" class="field-required">Category</label>
                                                    <select name="category_id" id="home" class="form-control">
                                                        <option value="">Select </option>
                                                        @foreach($types as $type)
                                                        <option 
                                                            value="{{ $type->id }}"
                                                            @if(old('category_id') ? old('category_id') == $type->id : (isset($property) ? $property->category_id == $type->id : false)) selected @endif
                                                        >
                                                            {{ $type->title }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="title">Bedrooms</label>
                                                    <select name="bedroom_id" id="state" class="form-control">
                                                        <option value="">Select </option>
                                                        @foreach($bedrooms as $bedroom)
                                                        <option 
                                                            value="{{ $bedroom->id }}"
                                                            @if(old('bedroom_id') ? old('bedroom_id') == $bedroom->id : (isset($property) ? $property->bedroom_id == $bedroom->id : false)) selected @endif
                                                        >
                                                            {{ $bedroom->title }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="title">Bathrooms</label>
                                                    <select name="bathroom_id" id="state" class="form-control">
                                                        <option value="">Select </option>
                                                        @foreach($bathrooms as $bathroom)
                                                        <option 
                                                            value="{{ $bathroom->id }}"
                                                            @if(old('bathroom_id') ? old('bathroom_id') == $bathroom->id : (isset($property) ? $property->bathroom_id == $bathroom->id : false)) selected @endif
                                                        >
                                                            {{ $bathroom->title }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="title">Sleeps</label>
                                                    <select name="sleep_id" id="state" class="form-control">
                                                        <option value="">Select </option>
                                                        @foreach($sleeps as $sleep)
                                                        <option 
                                                            value="{{ $sleep->id }}"
                                                            @if(old('sleep_id') ? old('sleep_id') == $sleep->id : (isset($property) ? $property->sleep_id == $sleep->id : false)) selected @endif
                                                        >
                                                            {{ $sleep->title }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="title">Housekeeper</label>
                                                    <select name="housekeeper_id" id="state" class="form-control">
                                                        <option value="">Select </option>
                                                        @foreach($house_keepers as $house_keeper)
                                                        <option 
                                                            value="{{ $house_keeper->id }}"
                                                            @if(old('housekeeper_id') ? old('housekeeper_id') == $house_keeper->id : (isset($property) ? $property->housekeeper_id == $house_keeper->id : false)) selected @endif
                                                        >
                                                            {{ $house_keeper->first_name }} {{ $house_keeper->last_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div classs="row">
                                            <div class="col-md-12">
                                                <label for="title" class="field-required">Short Description</label>
                                                <textarea
                                                    name="short_description"
                                                    class="mceEditor"
                                                    style="width: 100%; height: 168px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                                >
                                                    {{ old('short_description') ? old('short_description') : (isset($property) ? $property->short_description : '') }}
                                                </textarea>
                                                <div class="d-flex justify-content-between align-items-center long-desc-div" onclick="toggleLongDescription()">
                                                    <div id="add-long-description">Add Long Description</div>
                                                    <div>
                                                        <span id="add-long-des-icon">+</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" id="long-description" style="display: none;">
                                        <label for="title">Long Description</label>
                                        <textarea
                                            name="long_description"
                                            class="mceEditor"
                                            style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                        {{ old('long_description') ? old('long_description') : (isset($property) ? $property->long_description : '') }}
                                        </textarea>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend>Additional Information</legend>
                                <div class="row">

                                    <div class="col-md-4">
                                        <table class="table table-bordered table-hover normal-table">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Rates</th>
                                                    <th>Active</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="width:100px;">Cleaning Fee</td>
                                                    <td style="width:200px;">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">$</div>
                                                            <input
                                                                type="text"
                                                                name="clearing_fee"
                                                                class="form-control"
                                                                id="clearing_fee"
                                                                value="{{ old('clearing_fee') ? old('clearing_fee') : (isset($property) ? number_format_without_comma($property->clearing_fee) : '') }}"
                                                                onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="checkbox"
                                                            name="clearing_fee_active"
                                                            id="clearing-fee-active"
                                                            @if(old('clearing_fee_active') == '1' || (isset($property) && $property->clearing_fee_active == '1')) checked @endif
                                                        >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:100px;">Pet Fee</td>
                                                    <td style="width:200px;">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">$</div>
                                                            <input
                                                                type="text"
                                                                name="pet_fee"
                                                                class="form-control"
                                                                id="pet-fee"
                                                                value="{{ old('pet_fee') ? old('pet_fee') : (isset($property) ? number_format_without_comma($property->pet_fee) : '') }}"
                                                                onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="checkbox"
                                                            name="pet_fee_active"
                                                            id="pet-fee-active"
                                                            @if(old('pet_fee_active') == '1' || (isset($property) && $property->pet_fee_active == '1')) checked @endif
                                                        >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:100px;">Lodger's Tax</td>
                                                    <td style="width:200px;">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">%</div>
                                                            <input
                                                                type="number"
                                                                name="lodger_tax"
                                                                class="form-control"
                                                                id="lodger-tax"
                                                                value="{{ old('lodger_tax') ? old('lodger_tax') : (isset($property) ? number_format_without_comma($property->lodger_tax) : '') }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="checkbox"
                                                            name="lodger_tax_active"
                                                            id="lodger-tax-active"
                                                            @if(old('lodger_tax_active') == '1' || (isset($property) && $property->lodger_tax_active == '1')) checked @endif>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:100px;">Sales Tax</td>
                                                    <td style="width:200px;">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">%</div>
                                                            <input
                                                                type="number"
                                                                name="sales_tax"
                                                                class="form-control"
                                                                id="sales-tax"
                                                                value="{{ old('sales_tax') ? old('sales_tax') : (isset($property) ? number_format_without_comma($property->sales_tax) : '') }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="checkbox"
                                                            name="lodger_tax_active"
                                                            id="lodger-tax-active"
                                                            @if(old('sales_tax_active') == '1' || (isset($property) && $property->sales_tax_active == '1')) checked @endif>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="row d-flex justify-content-center align-items-center pr-30">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Commision (%)</label>
                                                    <input 
                                                        type="text" 
                                                        class="form-control input-md" 
                                                        name="commission"
                                                        value="{{ old('commission') ? old('commission') : (isset($property) ? $property->commission : '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="pdf">PDF</label>
                                                    <input name="pdf" type="file">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="btn-group d-flex" style="font-size: 0.878vw;" role="group" aria-label="Actions">
                                                    <a class="icon-btn-group-left" data-toggle="modal" data-target="#img-modal">
                                                        <i class="fa fa-image"></i> Images
                                                    </a>
                                                    <a class="icon-btn-group-center" data-toggle="modal" data-target="#rates-modal">
                                                        <i class="fa fa-usd"></i>Rates
                                                    </a>
                                                    <a class="icon-btn-group-right" data-toggle="modal" data-target="#amenities-modal">
                                                        <i class="fa fa-list"></i> Amenities
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="d-flex">
                                                        <input
                                                            type="checkbox"
                                                            name="status"
                                                            class="mr-10"
                                                            @if(old('status') == '1' || (isset($property) && $property->status == '1')) checked @endif>Status
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="d-flex">
                                                        <input
                                                            type="checkbox"
                                                            @if(old('is_featured') == '1' || (isset($property) && $property->is_featured == '1')) checked @endif
                                                            name="is_featured"
                                                            class="mr-10">Featured
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="d-flex">
                                                        <input
                                                            type="checkbox"
                                                            name="is_pet_friendly"
                                                            class="mr-10"
                                                            @if(old('is_pet_friendly') == '1' || (isset($property) && $property->is_pet_friendly == '1')) checked @endif>Pet Friendly
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="d-flex">
                                                        <input
                                                            type="checkbox"
                                                            name="is_online_booking"
                                                            class="mr-10"
                                                            @if(old('is_online_booking') == '1' || (isset($property) && $property->is_online_booking == '1')) checked @endif>Online Booking
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="d-flex">
                                                        <input
                                                            type="checkbox"
                                                            name="is_calendar_active"
                                                            class="mr-10"
                                                            @if(old('is_calendar_active') == '1' || (isset($property) && $property->is_calendar_active == '1')) checked @endif>Calendar
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="field-required">Display Order</label>
                                                    <input 
                                                        type="text" 
                                                        class="form-control input-md" 
                                                        name="display_order" 
                                                        value="{{ old('display_order') ? old('display_order') : (isset($property) ? $property->display_order : '') }}"
                                                        onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    
                    {!! $season_rates_modal !!}
                    
                    {!! $amenities_modal !!}

                    <!-- Modal -->
                    {!! $imageModal !!}
                    <div class="m-b-20">
                        <button type="submit" class="btn btn-primary m-t-10">{{ $submitBtnText }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>