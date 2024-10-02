@extends('layouts.default')

@section('title')
Reservations
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
                            <div class="col-xs-3 col-sm-3 col-md- col-lg-3">
                                <h3 class="box-title">Reservations</h3>
                                <p class="m-t-10">
                                    <a href="{{ url('admin/reservation/step1') }}" class="btn btn-success">Add Reservation</a>
                                </p>
                            </div>
                            <div class="col-md-5 col-lg-5 text-right">
                                @include('admin.reservation._search')
                            </div>
                            <div class="col-md-4 col-lg-4 text-right">
                                <p class="m-t-10">
                                    <a href="{{ url('admin/archeived-reservations') }}" class="btn btn-info pull-right">Archived Reservations</a>
                                    <a href="{{ url('admin/reservations?status=cancelled') }}" style="margin-right:5px;" class="btn btn-danger pull-right">Cancelled Reservations</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <table class="table table-bordered table-hover normal-table" id="reservations-table">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th data-order="asc">Arrival</th>
                                            <th>Departure</th>
                                            <th>Property</th>
                                            <th>Amount</th>
                                            <th>From</th>
                                            <th>Created</th>
                                            @if(Auth::user()->type != "owner")
                                            <th class="text-center">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($reservations) > 0)
                                            @foreach($reservations as $reservation)
                                                <tr data-delete="{{ url('admin/reservation/delete') }}/{{ $reservation->id }}">
                                                    <td>
                                                        {{ @$reservation->customer ? $reservation->customer->name : "Customer Not Available" }}
                                                    </td>
                                                    <td>{{ mdy($reservation->arrival) }}</td>
                                                    <td>{{ mdy($reservation->departure) }}</td>
                                                    <td>{{ ucfirst(@$reservation->property->title) }}</td>
                                                    <td>{{ price_format($reservation->total_amount) }}</td>
                                                    <td>
                                                        <label class="label label-{{ $reservation->from_admin?'danger':'success' }}">{{ $reservation->from_admin?"Admin":"Frontend" }}</label>
                                                    </td>
                                                    <td>{{ mdyWithTime($reservation->created_at) }}</td>
                                                    <td class="text-center">
                                                        <a data-toggle="tooltip" title="Edit" href="{{ url('admin/reservation') }}/{{ $reservation->id }}/edit" class="btn btn-primary btn-xs">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <a data-toggle="tooltip" title="Delete" delete-trigger href="#" class="btn btn-danger btn-xs">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center">No Reservations Found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($reservations->total()>0)
                            <p>Showing {!! $reservations->firstItem() !!} to {!! $reservations->lastItem() !!} of {!! $reservations->total() !!}</p>
                            {{ $reservations->links() }}
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

@stop

@section('javascript')
<script>
    // delete trigger
    $(document).on('click', '[delete-trigger]', function(e){
        e.preventDefault();
        var url = $(this).closest('tr').data('delete');
        var tr = $(this).closest('tr');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this reservation!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function(){
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function(result) {
                    if(result=='success'){
                        tr.remove();
                        swal({
                            title: "Deleted!",
                            text: 'The entry was successfully deleted',
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }else{
                        swal("Error!", "Failed to delete reservation.", "error");
                    }
                }
            });
        });
    });
</script>
@endsection