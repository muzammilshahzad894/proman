@extends('layouts.default')
@section('title')
    Add Template
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
                                    <h3 class="box-title">Add Template</h3>
                                    <p class="m-t-10">
                                        <a href="{{ route('admin.email_templates.index') }}" class="btn btn-default">Back</a>
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

                                    <form action="{{url('')}}/admin/email_templates" method="post" enctype="multipart/form-data">

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type = "hidden" name = "id" value = "0">



                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="field-required">Display Title</label>
                                                    <input type="text"  value="{{old('title')?old('title'):''}}"  class="form-control" name="title">
                                                </div>

                                                <div class="form-group">
                                                    <label class="field-required">Subject</label>
                                                    <input type="text"  value="{{old('subject')?old('subject'):''}}"  class="form-control" name="subject">
                                                </div>
                                                <div class="form-group">
                                                    <label class="field-required">Fixed Body</label>
                                                    <textarea class="form-control mceEditor" name="static_body">{{old('static_body')?old('static_body'):''}}</textarea>

                                                </div>
                                                <div class="form-group">
                                                    <label class="">Email Body</label>
                                                    <textarea  class="form-control mceEditor"  name="dynamic_body">{{old('dynamic_body')?old('dynamic_body'):''}}</textarea>

                                                </div>



                                            </div>

                                        </div>


                                        <button class="btn btn-success" type="submit">Add</button>


                                    </form>
                                </div>
                            </div> <!-- row -->

                        </div><!-- /.box-body -->
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection