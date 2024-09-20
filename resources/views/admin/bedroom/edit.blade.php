@extends('layouts.default')

@section('title')
Edit Bedroom
@parent
@stop



@section('content')

<!-- =============================================== -->


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->

    <section class="content">

        <div class="row">

            <div class="col-md-12 col-lg-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">
                            Edit Bedroom
                        </h3>
                        <p class="m-t-10">
                            <a href="{{ url('/admin/bedrooms') }}" class="btn btn-default">Back</a>
                        </p>
                    </div>
                    <div class="box-body">
                        <div id="show-messages"></div>
                        
                        <form action="{{ url('admin/bedroom') }}/{{$bedroom->id}}/edit" method="POST" role="form" enctype="multipart/form-data" class="ajax-submission">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="title" class="field-required">Value</label>
                                        <input name="title" type="text" class="form-control" id="title" value="{{ old('title', $bedroom->title) }}" autofocus="autofocus">
                                    </div>
                                </div>
                            </div>
                            <div class="row m-t-20">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="title" class="field-required">Display Order</label>
                                        <input name="display_order" type="text" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="title" value="{{ old('display_order', $bedroom->display_order) }}">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary m-t-10">Update</button>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
</div> <!-- content-wrapper -->
@stop

@section('javascript')
@include('layouts.js.ajax-form-submission')
<script>
</script>
@stop