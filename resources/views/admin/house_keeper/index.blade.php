@extends('layouts.default')

@section('title')
Housekeepers
@parent
@stop

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
                                <h3 class="box-title">Housekeepers</h3>
                                <p class="m-t-10">
                                    <a href="{{ url('admin/housekeeper/create') }}" class="btn btn-success">Add Housekeeper</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-body">
                        @include('shared.errors')
                        <div class="row">
                            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                                <table class="table table-responsive table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Email</th>
                                            <th>Diplay Order</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($house_keepers as $house_keeper)
                                        <tr data-delete="{{ url('admin/housekeeper/delete') }}/{{ $house_keeper->id }}">
                                            <td>{{ $house_keeper->last_name }}</td>
                                            <td>{{ $house_keeper->first_name }}</td>
                                            <td>{{ $house_keeper->email }}</td>
                                            <td>{{ $house_keeper->display_order }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('admin/housekeeper') }}/{{ $house_keeper->id }}/edit" style=""><i class="fa fa-pencil"></i></a>
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