@extends('layouts.default')

@section('title')
Amenity - Edit
@parent
@stop

@section('content')
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <h3 class="box-title">Edit Amenity</h3>
                                <div class="m-t-10">
                                    <a href="{{ url('/admin/amenities') }}" class="btn btn-default">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        
                        <div id="show-messages"></div>
                    
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <form action="{{ url('/admin/amenities') }}/{{$amenity->id}}/edit" method="PUT" class="ajax-submission">
                                    @csrf
                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <label for="" class="field-required">Title</label>
                                        <input type="text" name="title" class="form-control" id="full_name" placeholder=""
                                            value="{{ old('title', @$amenity->title) }}">
                                        @foreach($errors->get('name') as $message)
                                        <span class="help-block">{{ $message }}</span>
                                        @endforeach
                                    </div>

                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <label for="email" class="control-label field-required">Type</label>
                                        <select name="type" id="type" class="form-control" onchange="showtypesdetail();">
                                            <option value=""> - select - </option>
                                            <option value="InputText"
                                                @if(@$amenity->type == "InputText") selected @endif> InputText </option>
                                            <option value="CheckBox"
                                                @if(@$amenity->type == "CheckBox") selected @endif> CheckBox </option>
                                            <option value="Dropdown"
                                                @if(@$amenity->type == "Dropdown") selected @endif> Dropdown </option>
                                        </select>
                                        @foreach($errors->get('email') as $message)
                                        <span class="help-block">{{ $message }}</span>
                                        @endforeach
                                    </div>

                                    <div id="CntnrDv"></div>

                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <label for="email" class="control-label field-required">Group</label>
                                        <select name="group" class="form-control" id="grouped">
                                            <option value=""> - select - </option>
                                            <option value="General"
                                                @if(@$amenity->group == "General") selected @endif > General </option>
                                            <option value="Bedding"
                                                @if(@$amenity->group == "Bedding") selected @endif> Bedding </option>
                                            <option value="Amenities"
                                                @if(@$amenity->group == "Amenities") selected @endif
                                                > Amenities </option>
                                        </select>
                                        @foreach($errors->get('email') as $message)
                                        <span class="help-block">{{ $message }}</span>
                                        @endforeach
                                    </div>
                                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                        <label for="password" class="control-label field-required">Display Order</label>
                                        <input type="text" name="display_order"
                                            value="{{ old('title', @$amenity->display_order) }}"
                                            onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control" id="password" placeholder="" style="max-width: 150px;">
                                        @foreach($errors->get('password') as $message)
                                        <span class="help-block">{{ $message }}</span>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="btn btn-success ">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div><!-- .row -->

    </section>
</div> <!-- content-wrapper -->
@stop


@section('javascript')
@include('layouts.js.ajax-form-submission')
<script>
    var initialType = "<?php echo $amenity->type; ?>"
    var gotOption = <?php echo json_encode($amenity->option); ?>

    var option = JSON.parse(gotOption);

    if (initialType == "Dropdown" || initialType == "CheckBox") {
        var CntnrDv = document.getElementById('CntnrDv');
        CntnrDv.innerHTML = "<textarea name='option' id='option' class='form-control' rows='9' cols='50'>" + option + "</textarea><br>";
    }

    function showtypesdetail() {
        var type = document.getElementById('type').options[document.getElementById('type').selectedIndex].value;
        var CntnrDv = document.getElementById('CntnrDv');

        if (type == "Dropdown" || type == "CheckBox") {
            createTextarea();
        } else {
            removeTextarea();
        }
    }

    function createTextarea() {
        removeTextarea();
        var CntnrDv = document.getElementById('CntnrDv');
        CntnrDv.innerHTML = "<textarea name='option' id='option' class='form-control' rows='9' cols='50' placeholder='Add options one per line'></textarea><br>";
    }

    function removeTextarea() {
        var CntnrDv = document.getElementById('CntnrDv');
        var oldTextArea = document.getElementById('option');
        if (oldTextArea) {
            CntnrDv.removeChild(oldTextArea);
        }
    }
</script>
@stop