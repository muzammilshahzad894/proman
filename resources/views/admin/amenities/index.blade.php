@extends('layouts.default')

@section('title')
Manage Amenities
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
                                <h3 class="box-title">Amenities</h3>
                                <div class="m-t-10">
                                    <a href="{{ url('admin/amenities/create') }}" class="btn btn-success">Add Amenity</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <table class="table table-striped table-hover m-b-20">
                                    <tr>
                                        <th>Group</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    @foreach($amenities as $amenity)
                                    <tr data-delete="{{ url('admin/amenities/delete') }}/{{$amenity->id}}">
                                        <td>{{ $amenity->group }}</td>
                                        <td>{{ $amenity->title }}</td>
                                        <td>{{ $amenity->type }}</td>

                                        <td class="text-center">
                                            <a href="{{ url('admin/amenities') }}/{{ $amenity->id }}/edit"><i class="fa fa-pencil"></i></a>
                                            <span class="">
                                                <a data-delete-trigger href="#"><i class="fa fa-trash"></i></a>
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
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