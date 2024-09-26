@extends('layouts.default')

@section('title')
Types
@parent
@stop

@section('content')

<!-- =============================================== -->


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="box-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="box-title">Types</h3>
                                        <p class="m-t-10">
                                            <a href="{{ url('admin/type/create') }}" class="btn btn-success">Add Type</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="box-body">
                                <table class="table table-bordered table-hover normal-table">
                                    <thead>
                                        <tr>
                                            <th>Preview</th>
                                            <th>Type</th>
                                            <th>Diplay Order</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($types as $type)
                                        <tr data-delete="{{ url('admin/type/delete') }}/{{ $type->id }}">
                                            <td><img src="{{url('/types')}}/{{ $type->image }}" style="max-width:125px;"></td>
                                            <td>{{ $type->title }}</td>
                                            <td>{{ $type->display_order }}</td>
                                            <td class="text-center">
                                                <a data-toggle="tooltip" title="Edit" href="{{ url('admin/type') }}/{{ $type->id }}/edit" class="btn btn-primary btn-xs">
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