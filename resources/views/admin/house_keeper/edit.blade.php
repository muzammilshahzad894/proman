@extends('layouts.default')

@section('title')
Edit Housekeeper
@parent
@stop

@include('admin.users.__includes')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <form action="{{ url('admin/housekeeper') }}/{{$house_keeper->id}}/edit" method="POST" role="form" class="ajax-submission">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">
                                Edit Housekeeper
                            </h3>
                            <p class="m-t-10">
                                <a href="{{ url('/admin/housekeepers') }}" class="btn btn-default">Housekeepers List</a>
                            </p>
                        </div>

                        <div class="box-body">
                            @include('shared.errors')
                            <div id="show-messages"></div>
                            <div class="row">
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="title" class="field-required">First Name</label>
                                                <input name="first_name" type="text" class="form-control" id="title" value="{{ old('first_name', $house_keeper->first_name) }}" autofocus="autofocus">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="title" class="field-required">Last Name</label>
                                                <input name="last_name" type="text" class="form-control" id="title" value="{{ old('last_name', $house_keeper->last_name) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="title">Address 1</label>
                                        <input name="address1" type="text" class="form-control" id="title"
                                            value="{{ old('address1', $house_keeper->address1) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="title">Address 2</label>
                                        <input name="address2" type="text" class="form-control" id="title"
                                            value="{{ old('address2', $house_keeper->address2) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="title">City</label>
                                        <input name="city" id="cronjob" type="text" class="form-control"
                                            value="{{ old('city', $house_keeper->city) }}" placeholder="examlpe New York">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="title">State</label>
                                        <select name="state" id="state" class="form-control select2">
                                            <option>Select</option>
                                            @foreach(states() as $state)
                                            <option value="{{ $state }}" @if(old('state', $house_keeper->state) == $state) selected @endif>{{ $state }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="title">ZIP Code</label>
                                        <input name="zip_code" id="minimum_stay" type="text" class="form-control zipcode" value="{{ old('zip_code', $house_keeper->zip_code) }}" placeholder="example 87">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="title" class="field-required">Phone</label>
                                        <input name="phone" type="text" class="form-control phone_us" id="title"
                                            value="{{ old('phone', $house_keeper->phone) }}" autofocus="autofocus">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="title" class="field-required">Email</label>
                                        <input name="email" type="email" class="form-control" id="title" value="{{ old('email', $house_keeper->email) }}" autofocus="autofocus">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <label for="title">Notes</label>
                                    <textarea name="notes" id="inputMessage" class="form-control" rows="3">{{ old('notes', $house_keeper->notes) }}</textarea>
                                </div>
                            </div>
                            <div class="row m-t-20">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="title">Display Order</label>
                                        <input name="display_order" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control" id="title" value="{{ old('display_order', $house_keeper->display_order) }}">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary m-t-10">Update</button>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </form>
    </section>
</div> <!-- content-wrapper -->

@stop

@section('javascript')
@include('layouts.js.ajax-form-submission')
<script>
</script>
@stop