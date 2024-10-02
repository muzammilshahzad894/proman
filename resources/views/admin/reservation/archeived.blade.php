@extends('layouts.default')

@section('title')
Archived Reservations
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
                        <h3 class="box-title">Archeived Reservations </h3>
                        <br>
                        <br>
                    </div>

                    <div class="box-body">
                        @include('shared.errors')
                        @include('admin.reservation._search')
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <table class="table table-responsive table-hover table-bordered" id="reservations-table">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Arrival</th>
                                            <th>Departure</th>
                                            <th>Property</th>
                                            <th>Amount</th>
                                            <th>Created</th>
                                            <th class="text-center">Action</th>
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
                                                    <td>{{ mdyWithTime($reservation->created_at) }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ url('admin/reservation') }}/{{ $reservation->id }}/edit" style="margin-right: 5px;"><i class="fa fa-pencil"></i></a>

                                                        <a data-delete-trigger href="#"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">No Reservations Found</td>
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
</div>

@stop

@section('javascript')
<script>
    
</script>
@endsection