@extends('layouts.default')

@section('title')
Add Bathroom
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
                                <h3 class="box-title">Add Bathroom</h3>
                                <div class="m-t-10">
                                    <a class="btn btn-default" href="{{ url('admin/bathrooms') }}">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div id="show-messages"></div>
                        
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <form action="{{ url('admin/bathroom') }}" method="POST" role="form" enctype="multipart/form-data" class="ajax-submission">
                                    @csrf
                                    <div class="form-group">
                                        <label for="title" class="field-required">Value</label>
                                        <input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control input-half" id="title" name="title" value="{{ old('title') }}" placeholder="">
                                    </div>
                                    
                                    <div class="form-group input-3rd">
                                        <label for="display_order" class="field-required">Display Order </label>
                                        <input type="text" class="form-control" id="display_order" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="display_order" value="{{ old('display_order') }}" placeholder="">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div> <!-- content-wrapper -->
<!-- ./wrapper -->
@stop

@section('javascript')
@include('layouts.js.ajax-form-submission')
<script>
    function showtypesdetail() {
        var type = document.getElementById('type').options[document.getElementById('type').selectedIndex].value;

        if (type == "Dropdown") {
            document.getElementById("dropdown").style.display = "block";
        } else {
            document.getElementById("dropdown").style.display = "none";
        }
    }

    var counter = 2;

    function createInput(CntnrDv) {
        var newdiv = document.createElement('div');
        newdiv.innerHTML = "Value " + (counter + 1) + " <input type='text' class='form-control ' name='amenity_dropdown_value[]'><br>";
        document.getElementById(CntnrDv).appendChild(newdiv);
        counter++;

    }
</script>
@stop