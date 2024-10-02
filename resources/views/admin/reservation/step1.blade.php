@extends('layouts.default')

@section('title')
Properties
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
                                <h3 class="box-title">Please select a property below:</h3>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <table class="table table-bordered table-hover normal-table">
                                    <thead>
                                        <tr>
                                            <th>Preview</th>
                                            <th>Name</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($properties) > 0)
                                            @foreach($properties as $property)
                                            <tr>
                                                <td>
                                                    @if ($property->pictures->count())
                                                    <img
                                                        src="{{url('/uploads/properties/')}}/{{$property->mainpictures->last()->filename}}"
                                                        title="{{$property->mainpictures->last()->title}}"
                                                        style="max-width: 100px;"
                                                    />
                                                    @endif
                                                </td>

                                                <td>
                                                    {{ $property->title }}
                                                </td>

                                                <td class="text-center">
                                                    <a href="{{ url('admin/reservation/create') }}/{{ $property->id }}" class="btn btn-success btn-xs">Add Reservation</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center">No properties found.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($properties->total()>0)
                            <p>Showing {!! $properties->firstItem() !!} to {!! $properties->lastItem() !!} of {!! $properties->total() !!}</p>
                            {{ $properties->links() }}
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div> <!-- content-wrapper -->

<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
@stop