@extends('layouts.default')

@section('title')
Bedrooms
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
                                <h3 class="box-title">Bedrooms</h3>
                                <p class="m-t-10">
                                    <a href="{{ url('admin/bedroom/create') }}" class="btn btn-success">Add Bedroom</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <table class="table table-responsive table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Value</th>
                                            <th>Diplay Order</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bedrooms as $bedroom)
                                        <tr data-delete="{{ url('admin/bedroom/delete') }}/{{ $bedroom->id }}">
                                            <td>{{ $bedroom->title }}</td>
                                            <td>{{ $bedroom->display_order }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('admin/bedroom') }}/{{ $bedroom->id }}/edit"><i class="fa fa-pencil"></i></a>
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