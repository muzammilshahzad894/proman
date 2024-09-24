@extends('layouts.default')

@section('title')
Create Type
@parent
@stop

@include('admin.users.__includes')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="box-title"> Add Type</h3>
                                <div class="m-t-10">
                                    <a href="{{url('admin/types')}}" class="btn btn-default">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-body">
                        <div id="show-messages"></div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <form action="{{ url('admin/type') }}" method="POST" role="form" enctype="multipart/form-data" class="ajax-submission">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="" class="field-required">Title</label>
                                                <input 
                                                    name="title" 
                                                    type="text" 
                                                    class="form-control" 
                                                    id="title" 
                                                    value="{{ old('title') }}" 
                                                    autofocus="autofocus"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Image</label>
                                                <input name="image" type="file" class="form-control" id="imgInp">
                                                <img id="blah" style="max-width:125px;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row m-t-20">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="" class="field-required">Display Order</label>
                                                <input 
                                                    name="display_order" 
                                                    type="text" 
                                                    class="form-control input-sm" 
                                                    onkeypress="return isNumber(event)" 
                                                    id="title" 
                                                    value="{{ old('display_order') }}"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary m-t-10">Add</button>
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


@stop

@section('javascript')
@include('layouts.js.ajax-form-submission')

<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imgInp").change(function() {
        readURL(this);
    });
</script>

@stop