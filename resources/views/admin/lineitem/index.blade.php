@extends('layouts.default')

@section('title')
Manage Line Items
@parent
@stop

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Line Items</h3>
                <div class="m-t-10">
                    <a class="btn btn-success" href="{{ url('admin/lineitem/create') }}">Add Line Item</a>
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <table class="table table-bordered table-hover normal-table">
                            <tr>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Type</th>
                                <th>Display Order</th>
                                <th class="text-center">Action</th>
                            </tr>
                            @foreach($lineitems as $lineitem)
                            <tr data-delete="{{ url('admin/lineitem/delete') }}/{{$lineitem->id}}">
                                <td>{{ $lineitem->title }}</td>
                                <td>{{ $lineitem->value }}</td>
                                <td>{{ $lineitem->type }}</td>
                                <td>{{ $lineitem->display_order }}</td>
                                <td class="text-center">
                                    <a data-toggle="tooltip" title="Edit" href="{{ url('admin/lineitem') }}/{{ $lineitem->id }}/edit" class="btn btn-primary btn-xs">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a data-toggle="tooltip" title="Delete" data-delete-trigger href="#" class="btn btn-danger btn-xs">
                                        <i class="fa fa-remove"></i>
                                    </a>
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
    </section>
</div> <!-- content-wrapper -->
@stop