@extends('layouts.default')
@section('title')
    Email Template
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
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <h3 class="box-title">Templates</h3>
                                    <p class="m-t-10">
                                        {{--<a href="{{url('email_templates/create')}}" class="btn btn-success">Add Template</a>--}}
                                    </p>
                                </div>

                            </div>
                        </div> <!-- box-header -->

                        <div class="box-body">

                            {{-- Errors and messages --}}
                            @include('app-partials.messages', ['errors' => $errors])
                            {{-- // Errors and messages --}}

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                    <table class="table table-bordered table-hover normal-table">
                                        <tr>
                                            <th>Title</th>
                                            <th>Subject</th>
                                            <th>Type</th>
                                            
                                            <th class="action">Action</th>
                                        </tr>
                                        @if(count($email_templates)>0)
                                            @foreach($email_templates as $type => $templates)
                                                <tr style="background: #e9e9e9a1;">
                                                    <td colspan="6">
                                                        @if($type == 'email')
                                                        <b>{{ucfirst($type)}}</b>
                                                        @else
                                                        <b>{{strtoupper($type)}}</b>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @foreach($templates as $i)
                                                <tr data-delete="{{ route('admin.email_templates.destroy', $i) }}">

                                                    <td>{{$i->title}}</td>
                                                    <td>{{$i->subject}}</td>
                                                    <td>
                                                        @if(($i->type == 'email' || is_null($i->type)))
                                                        {{ucfirst($i->type)}}
                                                        @else
                                                        {{strtoupper($i->type)}}
                                                        @endif
                                                    </td>
                                                    

                                                    <td class="action">
                                                        <a href="{{route('admin.email_templates.edit', $i->id)}}"
                                                           class="fa fa-pencil"></a>
                                                        <!-- <a href="#" data-delete-trigger><i
                                                                    class="fa fa-trash"></i></a> -->
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6">No results found in Email Templates</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div> <!-- row -->

                        </div><!-- /.box-body -->
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection

@section('javascript')
    <script>
        jQuery(document).ready(function ($) {
            $('#form-submit').on('click', function (event) {
                event.preventDefault();
                $('form').submit();
            });
        });
    </script>
@endsection