@extends('layouts.default')

@section('title')
Edit Property
@parent
@stop

@section('css')
<link rel="stylesheet" href="{{url('/')}}/plugins/dropzone/dropzone.css">
<link rel="stylesheet" href="{{url('/')}}/plugins/dropzone/basic.css">
<style>
    .d-block {
        display: block;
    }
</style>
@stop

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <form action="{{ url('admin/property') }}/{{$property->id}}/edit" method="POST" role="form" enctype="multipart/form-data" class="ajax-submission">
            @csrf
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <h3 class="box-title">Edit Property</h3>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="company_name" class="field-required">Name</label>
                                        <input 
                                            name="title" 
                                            type="text" 
                                            class="form-control" 
                                            id="company_name"
                                            value="{{ isset($property) ? $property->title : old('title') }}"
                                            autofocus="autofocus"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="title" class="field-required">Category</label>
                                        <select name="category_id" id="home" class="form-control">
                                            <option value="">Select </option>
                                            @foreach($types as $type)
                                            <option value="{{ $type->id }}"
                                                @if(old('category_id')==$type->id)
                                                selected
                                                @elseif($property->category_id == $type->id && old('category_id') === NULL)
                                                selected
                                                @endif
                                            >
                                                {{ $type->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="title">Bedrooms</label>
                                        <select name="bedroom_id" class="form-control">
                                            <option value="">Select </option>
                                            @foreach($bedrooms as $bedroom)
                                            <option value="{{ $bedroom->id }}"
                                                @if(old('bedroom_id')==$bedroom->id)
                                                selected
                                                @elseif($property->bedroom_id == $bedroom->id && old('bedroom_id') === NULL)
                                                selected
                                                @endif
                                            >
                                                {{ $bedroom->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="title">Bathrooms</label>
                                        <select name="bathroom_id" id="state" class="form-control">
                                            <option value="">Select </option>
                                            @foreach($bathrooms as $bathroom)
                                            <option value="{{ $bathroom->id }}"
                                                @if(old('bathroom_id')==$bathroom->id)
                                                selected
                                                @elseif($property->bathroom_id == $bathroom->id && old('bathroom_id') === NULL)
                                                selected
                                                @endif
                                            >
                                                {{ $bathroom->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="title">Sleeps</label>
                                        <select name="sleep_id" class="form-control">
                                            <option value="">Select </option>
                                            @foreach($sleeps as $sleep)
                                            <option value="{{ $sleep->id }}"
                                                @if(old('sleep_id')==$sleep->id)
                                                selected
                                                @elseif($property->sleep_id == $sleep->id && old('sleep_id') === NULL)
                                                selected
                                                @endif
                                            >
                                                {{ $sleep->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row m-t-20">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                <label for="clearing-fee">Cleaning Fee ($)</label>
                                            </div>
                                            <div class="col-xs-4 text-right">
                                                <label for="clearing-fee-active">Active</label>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <input 
                                                type="text" 
                                                name="clearing_fee" 
                                                class="input-sm form-control" 
                                                id="clearing_fee" 
                                                value="{{ isset($property) ? number_format_without_comma($property->clearing_fee) : old('clearing_fee') }}"
                                                onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                            >
                                            <span class="input-group-addon">
                                                <input 
                                                    type="checkbox" 
                                                    name="clearing_fee_active" 
                                                    id="clearing-fee-active"
                                                    @if(isset($property))
                                                    {{ $property->clearing_fee_active == '1' ? 'checked' : '' }}
                                                    @else
                                                    {{ old('clearing_fee_active') == '1' ? 'checked' : '' }}
                                                    @endif
                                                >
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                <label for="pet-fee">Pet Fee ($)</label>
                                            </div>
                                            <div class="col-xs-4 text-right">
                                                <label for="pet-fee-active">Active</label>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <input 
                                                type="text" 
                                                name="pet_fee" 
                                                class="form-control input-sm" 
                                                id="pet-fee"
                                                value="{{ isset($property) ? number_format_without_comma($property->pet_fee) : old('pet_fee') }}"
                                                onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ old('pet_fee') }}"
                                            >
                                            <span class="input-group-addon">
                                                <input 
                                                    type="checkbox" 
                                                    name="pet_fee_active" 
                                                    id="pet-fee-active"
                                                    @if(isset($property))
                                                    {{ $property->pet_fee_active == '1' ? 'checked' : '' }}
                                                    @else
                                                    {{ old('pet_fee_active') == '1' ? 'checked' : '' }}
                                                    @endif
                                                >
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                <label for="lodger-tax">Lodger's Tax (%)</label>
                                            </div>
                                            <div class="col-xs-4 text-right">
                                                <label for="lodger-tax-active">Active</label>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <input 
                                                type="text" 
                                                name="lodger_tax" 
                                                class="form-control input-sm" 
                                                id="lodger-tax" 
                                                value="{{ isset($property) ? number_format_without_comma($property->lodger_tax) : old('lodger_tax') }}"
                                            >
                                            <span class="input-group-addon">
                                                <input 
                                                    type="checkbox" 
                                                    name="lodger_tax_active" 
                                                    id="lodger-tax-active"
                                                    @if(isset($property))
                                                    {{ $property->lodger_tax_active == '1' ? 'checked' : '' }}
                                                    @else
                                                    {{ old('lodger_tax_active') == '1' ? 'checked' : '' }}
                                                    @endif
                                                >
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                <label for="sales-tax">Sales Tax (%)</label>
                                            </div>
                                            <div class="col-xs-4 text-right">
                                                <label for="sales-tax-active">Active</label>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <input 
                                                type="text" 
                                                name="sales_tax" 
                                                class="form-control input-sm" 
                                                id="sales-tax" 
                                                value="{{ isset($property) ? number_format_without_comma( $property->sales_tax) : old('sales_tax') }}"
                                            >
                                            <span class="input-group-addon">
                                                <input 
                                                    type="checkbox" 
                                                    name="sales_tax_active" 
                                                    id="sales-tax-active"
                                                    @if(isset($property))
                                                    {{ $property->sales_tax_active == '1' ? 'checked' : '' }}
                                                    @else
                                                    {{ old('sales_tax_active') == '1' ? 'checked' : '' }}
                                                    @endif
                                                >
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Commision (%)</label>
                                        <input 
                                            value="{{ isset($property) ? number_format_without_comma($property->commision) : old('commision') }}"
                                            type="text" 
                                            class="form-control input-md" 
                                            name="commission"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="title">Housekeeper</label>
                                        <select name="housekeeper_id" class="form-control">
                                            <option value="">Select </option>
                                            @foreach($house_keepers as $house_keeper)
                                            <option 
                                                value="{{ $house_keeper->id }}"
                                                @if(old('housekeeper_id')==$house_keeper->id)
                                                selected
                                                @elseif($property->housekeeper_id == $house_keeper->id )
                                                selected
                                                @endif
                                            >
                                                {{ $house_keeper->first_name }} {{ $house_keeper->last_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row m-t-10">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Status" class="">Status</label><br>
                                        <input 
                                            name="status" 
                                            type="radio" 
                                            value="1"
                                            @if(isset($property))
                                            {{ $property->status == '1' ? 'checked' : '' }}
                                            @else
                                            {{ old('status') == '1' ? 'checked' : '' }}
                                            @endif
                                        > Active &nbsp;&nbsp;
                                        <input 
                                            name="status" 
                                            type="radio" 
                                            value="0" 
                                            @if(isset($property))
                                            {{ $property->status == '0' ? 'checked' : '' }}
                                            @else
                                            {{ old('status') == '0' ? 'checked' : '' }}
                                            @endif
                                        > Inactive
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="featured" class="">Featured</label><br>
                                        <input 
                                            name="is_featured" 
                                            type="radio" 
                                            value="1"
                                            @if(isset($property))
                                            {{ $property->is_featured == '1' ? 'checked' : '' }}
                                            @else
                                            {{ old('is_featured') == '1' ? 'checked' : '' }}
                                            @endif
                                        > Yes &nbsp;&nbsp;
                                        <input 
                                            name="is_featured" 
                                            type="radio" value="0"
                                            @if(isset($property))
                                            {{ $property->is_featured == '0' ? 'checked' : '' }}
                                            @else
                                            {{ old('is_featured') == '0' ? 'checked' : '' }}
                                            @endif
                                        > No
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="individual" class="">Pet Friendly</label><br>
                                        <input 
                                            name="is_pet_friendly" 
                                            type="radio" 
                                            value="1"
                                            @if(isset($property))
                                            {{ $property->is_pet_friendly == '1' ? 'checked' : '' }}
                                            @else
                                            {{ old('is_pet_friendly') == '1' ? 'checked' : '' }}
                                            @endif
                                        > Active &nbsp;&nbsp;
                                        <input 
                                            name="is_pet_friendly" 
                                            type="radio" 
                                            value="0"
                                            @if(isset($property))
                                            {{ $property->is_pet_friendly == '0' ? 'checked' : '' }}
                                            @else
                                            {{ old('is_pet_friendly') == '0' ? 'checked' : '' }}
                                            @endif
                                        > Inactive
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="individual" class="">Online Booking</label><br>
                                        <input 
                                            name="is_online_booking" 
                                            type="radio" 
                                            value="1"
                                            @if(isset($property))
                                            {{ $property->is_online_booking == '1' ? 'checked' : '' }}
                                            @else
                                            {{ old('is_online_booking') == '1' ? 'checked' : '' }}
                                            @endif
                                        > Active &nbsp;&nbsp;
                                        <input 
                                            name="is_online_booking" 
                                            type="radio" 
                                            value="0"
                                            @if(isset($property))
                                            {{ $property->is_online_booking == '0' ? 'checked' : '' }}
                                            @else
                                            {{ old('is_online_booking') == '0' ? 'checked' : '' }}
                                            @endif
                                        > Inactive
                                    </div>
                                </div>
                                <div class="col-md-2">
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
                                                    <?php echo $retVal = ($property->is_vacation == '1') ? 'checked' : ''; ?>
                                                > Vacation
                                            </label>
                                            <label class="radio-inline">
                                                <input 
                                                    type="radio" 
                                                    id="inlineCheckbox2" 
                                                    value="is_long_term" 
                                                    name="property_type" 
                                                    data-property_type
                                                    <?php echo $retVal = ($property->is_long_term == '1') ? 'checked' : ''; ?>
                                                > Long Term
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="pdf">PDF</label>
                                        <input name="pdf" type="file">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row m-t-20">
                                <div class="col-md-6 col-lg-6">
                                    <label for="title" class="field-required">Short Description</label>
                                    <textarea 
                                        name="short_description" 
                                        class="mceEditor"
                                        style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                        {{ old('short_description', $property->short_description) }}
                                    </textarea>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <label for="title">Long Description</label>
                                    <textarea 
                                        name="long_description" 
                                        class="mceEditor"
                                        style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                        {{ old('long_description', $property->long_description) }}
                                    </textarea>
                                </div>
                            </div>
                            
                            <div class="row m-t-20">
                                <div class="col-md-6 p-l-0">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="" class="d-block">Image Management</label>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Open</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="" class="d-block">Rates</label>
                                            <a class="btn btn-primary" data-toggle="modal" href='#Rates'>Manage</a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="" class="d-block">Amenities</label>
                                            <a class="btn btn-primary" data-toggle="modal" href='#Amenities'>Manage</a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="calender">Calendar</label><br>
                                            <input 
                                                type="checkbox" 
                                                value="1" 
                                                name="is_calendar_active"
                                                @if(isset($property))
                                                {{ $property->is_calendar_active == '1' ? 'checked' : '' }}
                                                @else
                                                {{ old('is_calendar_active') == '1' ? 'checked' : '' }}
                                                @endif
                                            > Active
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            
                            <!-- Modal -->
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg" style="width: 90%">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Upload Pictures</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">

                                                    <div id="drop-id" class="dropzone  m-b-20"> </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="well uploadTable-container">
                                                        <table class="table table-hover uploadTable">
                                                            <thead>
                                                                <tr>
                                                                    <th>Preview</th>
                                                                    <th>Title</th>
                                                                    <th>Order</th>
                                                                    <th>Main</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
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
                                            value="{{ old('display_order', $property->display_order) }}" 
                                            onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                        >
                                    </div>
                                </div>
                            </div>
                            @include('admin.property.season_rates')

                            @include('admin.property.amenities')

                            <div class="m-b-20">
                                <button type="submit" class="btn btn-primary m-t-10">Update</button>
                            </div>
                        </div><!-- /.box-body -->
                    </div>
                </div>
            </div>
        </form>
    </section>
</div> <!-- content-wrapper -->

<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<div class="modal fade" id="Rates">
    <div class="modal-dialog" style="width: 769px;">
        <div class="modal-content">
            <form action="{{ url('admin/property') }}/{{ $property->id }}" method="POST" role="form" class="ajax-submission">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Rates</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Season</th>
                                <th>Dates</th>
                                <th style="width: 150px;" class="daily">Daily</th>
                                <th style="width: 150px;" class="weekly">Weekly</th>
                                <th style="width: 150px;" class="monthly">Monthly</th>
                                <th style="width: 150px;" class="deposit">Deposit</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($seasons as $season)
                            <tr>
                                <td>{{ $season->title }} <input type="hidden" name="season_id[]" value="{{ $season->id }}"> </td>
                                <td>{{ $season->from_month }}/{{ $season->from_day }} - {{ $season->to_month }}/{{ $season->to_day }}</td>
                                
                                @foreach ($season->rates($property->id) as $propertyd)
                                @if ($propertyd->pivot->property_id != $property->id)
                                <?php continue;  ?>
                                @endif
                                <td class="daily">
                                    <div class="input-group">
                                        <div class="input-group-addon">$ </div>
                                        <input type="text" class="form-control" name="daily_rate[]" value="{{number_format_without_comma(@$propertyd->pivot->daily_rate)}}">
                                    </div>
                                </td>
                                <td class="weekly">
                                    @if($season->allow_weekly_rates==1)
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" class="form-control" name="weekly_rate[]" value="{{number_format_without_comma(@$propertyd->pivot->weekly_rate)}}">
                                    </div>
                                    @else
                                    <input type="hidden" class="form-control" name="weekly_rate[]">
                                    @endif
                                </td>
                                <td class="monthly">
                                    @if($season->allow_monthly_rates==1)
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" class="form-control" name="monthly_rate[]" value="{{number_format_without_comma(@$propertyd->pivot->monthly_rate)}}">
                                    </div>
                                    @else
                                    <input type="hidden" class="form-control" name="monthly_rate[]">
                                    @endif
                                </td>

                                <td class="deposit" style="display:none;">
                                    @if($season->allow_monthly_rates==1)
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" class="form-control" name="deposit[]" value="{{number_format_without_comma(@$propertyd->pivot->deposit)}}">
                                    </div>
                                    @else
                                    <input type="hidden" class="form-control" name="deposit[]">
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div> <!-- #Rates -->

<div class="modal fade" id="Amenities">
    <div class="modal-dialog" style="width: 769px;">
        <div class="modal-content">
            <form action="{{ url('admin/property/update-property-amenities') }}/{{ $property->id }}" method="POST" role="form" class="ajax-submission">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Amenities</h4>
                </div>
                <div class="modal-body">
                    <?php $AmenitiesCount = $property->amenities->count(); ?>
                    @if ($AmenitiesCount > 0)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Amenities</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($property->amenities as $amenity)
                            <tr>
                                <td>{{ $amenity->title }}
                                    <input type="hidden" name="amenity_id[]" value="{{ $amenity->id }}">
                                </td>
                                <td>
                                    @if($amenity->type=="InputText")
                                    <input type="text" name="value-{{ $amenity->id}}" id="input" class="form-control" value="{{ $amenity->pivot->value }}">
                                    @endif

                                    @if($amenity->type=="CheckBox")
                                    <input type="checkbox" name="value-{{ $amenity->id}}"
                                        @if ($amenity->pivot->value ==1)
                                    checked
                                    @endif
                                    value="1">
                                    @endif

                                    @if($amenity->type=="Dropdown")
                                    <select name="value-{{ $amenity->id}}" id="input" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($amenity->dropdownValues as $value)
                                        <option
                                            @if ($amenity->pivot->value == $value)
                                            selected
                                            @endif
                                            value="{{ $value->id }}">{{ $value->value }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <p> No Amenities attaced.</p>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    @if ($AmenitiesCount > 0)
                    <button type="submit" class="btn btn-primary">Update</button>
                    @endif
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div> <!-- #Amenities -->
@stop

@section('javascript')
@include('layouts.js.ajax-form-submission')
<script src="{{ url('/plugins/dropzone/dropzone.js') }}"></script>
<style>
    .uploadTable-container {
        display: none;
    }
</style>

<script>
    var base_url = "{{url('/')}}";

    function handleTable(counter) {
        if (counter >= 2) {
            $('.uploadTable-container').show();
        } else {
            $('.uploadTable-container').hide();
        };
    }

    Dropzone.autoDiscover = false;
    $uploadTable = $('.uploadTable');
    var counter = 1;

    var property_id = '{{ $property->id }}';

    var uploaded_pics = {!! json_encode($property->pictures) !!}

    function deleteFileFromServer(id) {
        $.ajax({
            url: "delete/file/" + id,
            type: "GET",
            success: function(response) {
                console.log('Delete', response.message);
            }
        });
    }

    function putFileinTable(file, res) {
        var html = "<tr data-filename='" + file.name + "'>";
        html = html + "<td><div style='background: url(" + base_url + "/uploads/properties/" + res.attachment.filename + "); width: 32px;height: 32px;background-size: cover;'></div><input class='form-control' name='pictures[]' type='hidden' value='" + res.attachment.id + "' /></td>";
        html = html + "<td><input class='form-control' name='pic_title[]' type='text' value='" + res.attachment.title + "' /></td>";
        html = html + "<td><input style='max-width: 119px;' class='form-control' name='order[]' type='text' value='" + res.attachment.order + "'/></td>";

        if (res.attachment.main == 1) {
            var main = 'checked';
        } else {
            var main = '';
        };

        html = html + "<td><input class='' name='main' type='radio' value='" + res.attachment.id + "'  " + main + " /></td>";
        html = html + "</tr>";
        $uploadTable.find('tbody').append(html);
        counter++;
        handleTable(counter);
    }

    jQuery(document).ready(function($) {
        var options = {
            url: "{{url('/')}}/admin/upload-files",
            paramName: 'file',

            maxFilesize: 5,
            addRemoveLinks: true,
            dictResponseError: 'Server not Configured',
            acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg",
            dictDuplicateFile: "Duplicate Files Cannot Be Uploaded",
            preventDuplicates: true,
            thumbnailWidth: "300",
            thumbnailHeight: "300",

            init: function() {
                var self = this;
                // config
                self.options.addRemoveLinks = true;
                self.options.dictRemoveFile = "Delete";
                //New file added
                self.on("addedfile", function(file, dataUrl) {
                    console.log('new file added ', file.name);

                });
                // Send file starts
                self.on("sending", function(file, xhr, formData) {
                    formData.append("_token", $('meta[name="csrf-token"]').attr('content'));

                    console.log('upload started', file);
                    $('.meter').show();
                });

                self.on('success', function(file, res) {
                    putFileinTable(file, res);
                    console.log('success');
                    console.log(res);
                });

                self.on('error', function(file) {
                    // alert('hi');
                    // $('.uploadTable').find('tbody tr').each(function(index, el) {

                    //  var el = $(el)
                    //   if (el.data('filename')==file.name) {
                    //      el.remove();
                    //   };
                    // });

                    //  console.log('error'+file);
                });


                // File upload Progress
                self.on("totaluploadprogress", function(progress) {
                    console.log("progress ", progress);
                    $('.roller').width(progress + '%');
                });

                self.on("queuecomplete", function(file, progress) {
                    $('.meter').delay(999).slideUp(999);
                });

                // On removing file
                self.on("removedfile", function(file) {
                    $('.uploadTable').find('tbody tr').each(function(index, el) {

                        var el = $(el)
                        if (el.data('filename') == file.name) {
                            el.remove();
                        };
                    });
                    counter--;
                    handleTable(counter);

                    deleteFileFromServer(file.obj.attachment.id);
                    console.log(file);
                });
            }
        };

        $uploadTable.find('tbody').html('');

        // Create the mock file:
        var mockFiles = [];

        console.log(uploaded_pics);
        var id = $(this).attr('data-id');
        $.each(uploaded_pics, function(index, obj) {
            mockFiles.push({
                name: obj.filename,
                size: obj.file_size,
                url: obj.file_url,
                obj: {
                    'attachment': obj
                }
            });
        });

        var myDropzone = $("#drop-id")[0].dropzone;
        if (!myDropzone) {
            myDropzone = new Dropzone("#drop-id", options);
            $.each(mockFiles, function(index, obj) {
                // Call the default addedfile event handler
                myDropzone.emit("addedfile", obj);
                // And optionally show the thumbnail of the file:
                myDropzone.emit("thumbnail", obj, obj.url);
                putFileinTable(obj, obj.obj);
            });
        } else {
            $.each(mockFiles, function(index, obj) {
                putFileinTable(obj, obj.obj);
            });
        }
    });

    $('[data-property_type]').on('change', function(event) {
        event.preventDefault();
        $('#Rates').modal('show');

        if ($(this).val() == 'is_long_term') {
            $('.daily').hide();
            $('.weekly').hide();
            $('.deposit').show();
        } else {
            $('.daily').show();
            $('.weekly').show();
            $('.deposit').hide();
        };
    });

    @if($property->is_long_term == '1')
    $('.daily').hide();
    $('.weekly').hide();
    $('.deposit').show();
    @else
    $('.daily').show();
    $('.weekly').show();
    $('.deposit').hide();
    @endif
</script>

<style>
  @if($property->is_long_term == '1'   )
        .daily {
          display: none; 
        }
         .weekly {
          display: none; 
        }
         .deposit {
          display: table-cell; 
        }
      
      @else

        .daily {
          display: table-cell; 
        }
         .weekly {
          display: table-cell; 
        }
         .deposit {
          display: none; 
        }
    
      @endif
  </style>
@stop