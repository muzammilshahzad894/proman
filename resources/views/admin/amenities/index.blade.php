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
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="box-title">Amenities</h3>
                                <div class="m-t-10">
                                    <a href="{{ url('admin/amenities/create') }}" class="btn btn-success">Add Amenity</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <table class="table table-responsive table-bordered table-hover">
                            <tr>
                                <th>Group</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            @foreach($amenities as $amenity)
                            <tr data-delete="{{ url('admin/amenities/delete') }}/{{$amenity->id}}">
                                <td>{{ $amenity->group }}</td>
                                <td>{{ $amenity->title }}</td>
                                <td>{{ $amenity->type }}</td>

                                <td class="text-center">
                                    <a data-toggle="tooltip" title="Edit" href="{{ url('admin/amenities') }}/{{ $amenity->id }}/edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                                    <a data-toggle="tooltip" title="Delete" data-delete-trigger href="#" class="btn btn-danger btn-xs">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div> <!-- content-wrapper -->
@stop