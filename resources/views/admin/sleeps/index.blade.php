@extends('layouts.default')

@section('title')
Sleeps
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
                                <h3 class="box-title">Sleeps</h3>
                                <div class="m-t-10">
                                    <a href="{{ url('/admin/sleep/create') }}" class="btn btn-success">Add Sleep</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <table class="table table-responsive table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Value</th>
                                            <th>Display Order</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sleeps as $sleep)
                                        <tr data-delete="{{ url('admin/sleep/delete') }}/{{ $sleep->id }}">
                                            <td>{{ $sleep->title }}</td>
                                            <td>{{ $sleep->display_order }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('admin/sleep') }}/{{ $sleep->id }}/edit"><i class="fa fa-pencil"></i></a>
                                                <a data-delete-trigger href="#"><i class="fa fa-trash"></i></a>
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