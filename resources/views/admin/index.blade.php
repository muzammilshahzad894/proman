@extends('layouts.default')

@section('title')
    Dashboard
    @parent
@stop


@section('css')
    <style type="text/css">
        .bottom-margin-10 {
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content-header">
            <h1>
                Dashboard
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-lg-4 col-md-4  col-sm-6 col-xs-12">
                    Content here
                </div>
            </div>
        </section>
    </div>

@stop
@section('javascript')
    <script></script>
@endsection
