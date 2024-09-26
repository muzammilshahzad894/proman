@extends('layouts.default')

@section('title')
Season Rates
@parent
@stop

@section('content')

<!-- =============================================== -->


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
                                <h3 class="box-title">Season Rates</h3>
                                <p class="m-t-10">
                                    <a href="{{ url('admin/seasonrate/create') }}" class="btn btn-success">Add Season</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                                <table class="table table-bordered table-hover normal-table">
                                    <thead>
                                        <tr>
                                            <th>Diplay Order</th>
                                            <th>Name</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Allow Weekly</th>
                                            <th>Allow Monthly</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($season_rates as $season_rate)
                                        <tr data-delete="{{ url('admin/seasonrate/delete') }}/{{ $season_rate->id }}">
                                            <td>{{ $season_rate->display_order }}</td>
                                            <td>{{ $season_rate->title }}</td>
                                            <td>{{ $season_rate->from_month }} / {{ $season_rate->from_day }}</td>
                                            <td>{{ $season_rate->to_month }} / {{ $season_rate->to_day }}</td>
                                            <td>
                                                @if ($season_rate->allow_weekly_rates== 1)
                                                <span class="label label-success">Yes</span>
                                                @else
                                                <span class="label label-danger">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($season_rate->allow_monthly_rates== 1)
                                                <span class="label label-success">Yes</span>
                                                @else
                                                <span class="label label-danger">No</span>
                                                @endif
                                            </td>
                                            <td class="text-center">                                                
                                                <a data-toggle="tooltip" title="Edit" href="{{ url('admin/seasonrate') }}/{{ $season_rate->id }}/edit" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a data-toggle="tooltip" title="Delete" data-delete-trigger href="#" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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