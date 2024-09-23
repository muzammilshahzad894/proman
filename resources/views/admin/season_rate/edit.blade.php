@extends('layouts.default')

@section('title')
Edit Season Rate
@parent
@stop

@include('admin.users.__includes')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <form action="{{ url('admin/seasonrate')}}/{{ $season_rate->id }}/edit" method="POST" role="form" class="ajax-submission">
            @csrf
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">
                                Edit Season Rate
                            </h3>
                            <p class="m-t-10">
                                <a href="{{ url('admin/seasonrate') }}" class="btn btn-default">Season Rate List</a>
                            </p>
                        </div>

                        <div class="box-body">
                            @include('shared.errors')
                            <div id="show-messages"></div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="title" class="field-required">Title</label>
                                        <input name="title" type="text" class="form-control" id="title" value="{{ old('title', $season_rate->title) }}" autofocus="autofocus">
                                    </div>
                                </div>
                            </div>

                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <label for="title" class="">From Date</label>
                                </div>
                            </div>
                            <div class="row m-t-20">
                                <div class="col-md-2" style="max-width: 123px;">
                                    <div class="form-group ">
                                        <label for="title" class="field-required">Month</label>
                                        <select class="form-control" name="from_month">
                                            <option value="">Select</option>
                                            @for($i=1; $i<=12; $i++) 
                                                <option value="{{ $i }}" @if(old('from_month')==$i) selected @elseif ($season_rate->from_month == $i) selected @endif>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2" style="max-width: 123px;">
                                    <div class="form-group ">
                                        <label for="title" class="field-required">Date</label>
                                        <select name="from_day" class="form-control">
                                            <option value="">Select</option>
                                            @for($i=1; $i<=31; $i++)
                                                <option value="{{ $i }}" @if(old('from_day')==$i) selected @elseif ($season_rate->from_day == $i) selected @endif>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row m-t-10">
                                <div class="col-md-3">
                                    <label for="title" class="">To Date</label>
                                </div>
                            </div>
                            <div class="row m-t-20">
                                <div class="col-md-2" style="max-width: 123px;">
                                    <div class="form-group">
                                        <label for="title" class="field-required">Month</label>
                                        <select name="to_month" class="form-control">
                                            <option value="">Select</option>
                                            @for($i=1; $i<=12; $i++) 
                                                <option value="{{ $i }}" @if(old('to_month')==$i) selected @elseif ($season_rate->to_month == $i) selected @endif>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2" style="max-width: 123px;">
                                    <div class="form-group">
                                        <label for="title" class="field-required">Date</label>
                                        <select name="to_day" class="form-control">
                                            <option value="">Select</option>
                                            @for($i=1; $i<=31; $i++) 
                                                <option value="{{ $i }}" @if(old('to_day')==$i) selected @elseif ($season_rate->to_day == $i) selected @endif>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row m-t-10">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title">Type</label><br>
                                        <input name="type" type="radio" value="Holiday" id="Holiday" @if (old('type')=='Holiday' )
                                            checked="checked"
                                            @elseif ($season_rate->type == 'Holiday')
                                        checked="checked"
                                        @endif
                                        >
                                        <label for="Holiday">Holiday</label>
                                        <input name="type" type="radio" value="Standard" id="Standard" @if (old('type')=='Standard' )
                                            checked="checked"
                                            @elseif ($season_rate->type == 'Standard')
                                        checked="checked"
                                        @endif
                                        >
                                        <label for="Standard">Standard</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title">Shown on Frontend</label><br>
                                        <input name="show_on_frontend" type="radio" value="1" @if (old('show_on_frontend')=="1" )
                                            checked="checked"
                                            @elseif ($season_rate->show_on_frontend == "1")
                                        checked="checked"
                                        @endif
                                        >
                                        Yes
                                        <input name="show_on_frontend" type="radio" value="0" @if (old('show_on_frontend')=="0" )
                                            checked="checked"
                                            @elseif ($season_rate->show_on_frontend == "0")
                                        checked="checked"
                                        @endif
                                        >
                                        No
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title">Allow Weekly Rates</label><br>
                                        <input name="allow_weekly_rates" type="radio" value="1" @if (old('allow_weekly_rates')=="1" )
                                            checked="checked"
                                            @elseif ($season_rate->allow_weekly_rates == "1")
                                        checked="checked"
                                        @endif
                                        >
                                        Yes
                                        <input name="allow_weekly_rates" type="radio" value="0" @if (old('allow_weekly_rates')=="0" )
                                            checked="checked"
                                            @elseif ($season_rate->allow_weekly_rates == "0")
                                        checked="checked"
                                        @endif
                                        >
                                        No
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title">Allow Monthly Rates</label><br>
                                        <input name="allow_monthly_rates" type="radio" value="1" @if (old('allow_monthly_rates')=="1" )
                                            checked="checked"
                                            @elseif ($season_rate->allow_monthly_rates == "1")
                                        checked="checked"
                                        @endif
                                        >
                                        Yes
                                        <input name="allow_monthly_rates" type="radio" value="0" @if (old("allow_monthly_rates")=="0" )
                                            checked="checked"
                                            @elseif ($season_rate->allow_monthly_rates == "0")
                                        checked="checked"
                                        @endif
                                        >
                                        No
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="title">Balance Payment Days</label>
                                        <input name="balance_payment_days" style="    max-width: 123px;" id="cronjob" type="text" class="form-control" value="{{ old('balance_payment_days', $season_rate->balance_payment_days) }}" placeholder="#">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="title">Minimum Nights</label>
                                        <input name="minimum_nights" style="    max-width: 123px;" id="minimum_stay" type="text" class="form-control" value="{{ old('minimum_nights', $season_rate->minimum_nights) }}" placeholder="#">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2" style="    max-width: 123px;">
                                    <div class="form-group">
                                        <label for="title">Display Order</label>
                                        <input name="display_order" id="sortby" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                            class="form-control" value="{{ old('display_order', $season_rate->display_order) }}" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <p>
                                <br>
                                <button type="submit" class="btn btn-success">Update Season Rate</button>
                            </p>
                            <br>
                        </div><!-- /.box-body -->
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