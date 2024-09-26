@extends('layouts.default')

@section('title')
Add Property
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
        <form action="{{ url('admin/property') }}" method="POST" role="form" enctype="multipart/form-data" class="ajax-submission">
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
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>General Details</legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="company_name" class="field-required">Name</label>
                                                    <input 
                                                        name="title" 
                                                        type="text" 
                                                        class="form-control" 
                                                        id="company_name"
                                                        value="{{ old('title') }}" 
                                                        autofocus="autofocus"
                                                        placeholder="Enter property name"
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="title" class="field-required">Category</label>
                                                    <select name="category_id" id="home" class="form-control">
                                                        <option value="">Select </option>
                                                        @foreach($types as $type)
                                                        <option value="{{ $type->id }}"
                                                            @if(old('category_id')==$type->id) selected @endif
                                                            >{{ $type->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="title">Bedrooms</label>
                                                    <select name="bedroom_id" id="state" class="form-control">
                                                        <option value="">Select </option>
                                                        @foreach($bedrooms as $bedroom)
                                                        <option value="{{ $bedroom->id }}"
                                                            @if(old('bedroom_id')==$bedroom->id) selected @endif
                                                            >{{ $bedroom->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="title">Bathrooms</label>
                                                    <select name="bathroom_id" id="state" class="form-control">
                                                        <option value="">Select </option>
                                                        @foreach($bathrooms as $bathroom)
                                                        <option value="{{ $bathroom->id }}"
                                                            @if(old('bathroom_id')==$bathroom->id) selected @endif
                                                            >{{ $bathroom->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="title">Sleeps</label>
                                                    <select name="sleep_id" id="state" class="form-control">
                                                        <option value="">Select </option>
                                                        @foreach($sleeps as $sleep)
                                                        <option value="{{ $sleep->id }}"
                                                            @if(old('sleep_id')==$sleep->id) selected @endif
                                                            >{{ $sleep->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="title">Housekeeper</label>
                                                    <select name="housekeeper_id" id="state" class="form-control">
                                                        <option value="">Select </option>
                                                        @foreach($house_keepers as $house_keeper)
                                                        <option value="{{ $house_keeper->id }}">{{ $house_keeper->first_name }} {{ $house_keeper->last_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>Fee | Taxes</legend>
                                        <div class="row">
                                            <div class="col-md-4">
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
                                                            value="{{ old('clearing_fee') }}" 
                                                            onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                        >
                                                        <span class="input-group-addon">
                                                            <input 
                                                                type="checkbox" 
                                                                name="clearing_fee_active" 
                                                                id="clearing-fee-active"
                                                            >
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
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
                                                            onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ old('pet_fee') }}"
                                                        >
                                                        <span class="input-group-addon">
                                                            <input 
                                                                type="checkbox" 
                                                                name="pet_fee_active" 
                                                                id="pet-fee-active"
                                                            >
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
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
                                                            value="{{ old('lodger_tax') }}"
                                                        >
                                                        <span class="input-group-addon">
                                                            <input 
                                                                type="checkbox" 
                                                                name="lodger_tax_active" 
                                                                id="lodger-tax-active"
                                                            >
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
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
                                                            value="{{ old('sales_tax') }}"
                                                        >
                                                        <span class="input-group-addon">
                                                            <input 
                                                                type="checkbox" 
                                                                name="sales_tax_active" 
                                                                id="sales-tax-active"
                                                            >
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Commision (%)</label>
                                                    <input value="{{ old('commission') }}" type="text" class="form-control input-md" name="commission">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>Property Status</legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="Status" class="">Status</label><br>
                                                    <input name="status" type="radio" value="1"
                                                        @if(old('status')==1) checked @endif
                                                        @if(old('status')==null) checked @endif> Active &nbsp;&nbsp;
                                                    <input name="status" type="radio" value="0" @if(old('status')!==null && old('status')==0) checked @endif> Inactive
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="featured" class="">Featured</label><br>
                                                    <input 
                                                        name="is_featured" 
                                                        type="radio" 
                                                        value="1"
                                                        @if(old('is_featured')==1) checked @endif
                                                        @if(old('is_featured')==null) checked @endif
                                                    > Yes &nbsp;&nbsp;
                                                    <input 
                                                        name="is_featured" 
                                                        type="radio" value="0"
                                                        @if(old('is_featured')!==null && old('is_featured')==0) checked @endif
                                                    > No
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="individual" class="">Pet Friendly</label><br>
                                                    <input 
                                                        name="is_pet_friendly" 
                                                        type="radio" 
                                                        value="1"
                                                        @if(old('is_pet_friendly')==null) checked @endif
                                                        @if(old('is_pet_friendly')==1) checked @endif
                                                    > Active &nbsp;&nbsp;
                                                    <input 
                                                        name="is_pet_friendly" 
                                                        type="radio" 
                                                        value="0"
                                                        @if(old('is_pet_friendly')!==null && old('is_pet_friendly')==0) checked @endif
                                                    > Inactive
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="individual" class="">Online Booking</label><br>
                                                    <input 
                                                        name="is_online_booking" 
                                                        type="radio" 
                                                        value="1"
                                                        @if(old('is_online_booking')==null) checked @endif
                                                        @if(old('is_online_booking')==1) checked @endif
                                                    > Active &nbsp;&nbsp;
                                                    <input 
                                                        name="is_online_booking" 
                                                        type="radio" 
                                                        value="0"
                                                        @if(old('is_online_booking')!==null && old('is_online_booking')==0) checked @endif
                                                    > Inactive
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>File Management</legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pdf">PDF</label>
                                                    <input name="pdf" type="file">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="" class="d-block">Image Management</label>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Open</button>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
                                        <legend>Property Information</legend>
                                        <div classs="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Property Type</label>
                                                    <div>
                                                        <label class="radio-inline">
                                                            <input type="radio" id="inlineCheckbox1" value="is_vacation" name="property_type" data-property_type checked> Vacation
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" id="inlineCheckbox2" value="is_long_term" name="property_type" data-property_type> Long Term
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="title" class="field-required">Short Description</label>
                                                <textarea 
                                                    name="short_description" 
                                                    class="mceEditor"
                                                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                                    {{ old('short_description') }}
                                                </textarea>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <label for="title">Long Description</label>
                                                <textarea 
                                                    name="long_description" 
                                                    class="mceEditor"
                                                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                                    {{ old('long_description') }}
                                                </textarea>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>Additional Information</legend>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="" class="d-block">Rates</label>
                                                    <a class="btn btn-primary" data-toggle="modal" href='#modal-id'>Manage</a>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="" class="d-block">Amenities</label>
                                                    <a class="btn btn-primary" data-toggle="modal" href='#ammenties'>Manage</a>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="calender">Calendar</label><br>
                                                    <input type="checkbox" value="1" name="is_calendar_active"> Active
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="field-required">Display Order</label>
                                                    <input type="text" class="form-control input-md" id="" name="display_order" value="{{ old('display_order') }}" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            
                            @include('admin.property.season_rates')

                            @include('admin.property.amenities_popup')
                            
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
                                                    <div id="myId" class="dropzone m-b-20"> </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="well uploadTable-container">
                                                        <table class="table table-hover uploadTable">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        Preview
                                                                    </th>
                                                                    <th>Title</th>
                                                                    <th>Order</th>
                                                                    <th>Main</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
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
                            <div class="m-b-20">
                                <button type="submit" class="btn btn-primary m-t-10">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div> <!-- content-wrapper -->

<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@stop

@section('javascript')
@include('layouts.js.ajax-form-submission')
<script src="{{url('/')}}/plugins/dropzone/dropzone.js"></script>
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

    function putFileinTable(file, res) {
        var encodedFilename = encodeURIComponent(res.attachment.filename);
        var html = "<tr data-filename='" + file.name + "'>";
        html = html + "<td><div style='background: url(" + base_url + "/uploads/properties/" + encodedFilename + "); width: 32px;height: 32px;background-size: cover;'></div><input class='form-control' name='pictures[]' type='hidden' value='" + res.attachment.id + "' /></td>";
        html = html + "<td><input class='form-control' name='pic_title[]' type='text' value='' /></td>";
        html = html + "<td><input style='max-width: 119px;' class='form-control' name='order[]' type='text'/></td>";
        html = html + "<td><input class='' name='main' type='radio' value='" + res.attachment.id + "' /></td>";
        html = html + "</tr>";
        $uploadTable.find('tbody').append(html);
        counter++
        handleTable(counter);
    }

    jQuery(document).ready(function($) {
        Dropzone.options.myId = {
            url: "{{url('/')}}/admin/upload-files",
            paramName: 'file',

            maxFilesize: 5,
            addRemoveLinks: true,
            dictResponseError: 'Server not Configured',
            acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg",
            dictDuplicateFile: "Duplicate Files Cannot Be Uploaded",
            preventDuplicates: true,

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

                // self.on('thumbnail', function(file, dataUrl) {
                //       console.log('dataUrl');
                //       console.log(dataUrl);
                //   });

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
                });
            }
        };
        var myDropzone = new Dropzone("div#myId");
    });

    $('[data-property_type]').on('change', function(event) {
        event.preventDefault();
        $('#modal-id').modal('show');

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
</script>
@stop