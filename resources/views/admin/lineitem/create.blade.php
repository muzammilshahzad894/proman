@extends('layouts.default')

@section('title')
Create Line Item
@parent
@stop

@include('admin.users.__includes')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <h3 class="box-title">Add Line item</h3>
                                <div class="m-t-10">
                                    <a href="{{url('admin/lineitems')}}" class="btn btn-default">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        @include('shared.errors')
                        <div id="show-messages"></div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                @include('admin.lineitem.partials._form', [
                                    'url' => route('admin.lineitem.store'),
                                    'submitBtnText' => 'Add',
                                ])
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