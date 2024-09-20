@extends('layouts.default')
@section('title')
    Edit Template
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
                                    <h3 class="box-title">Edit Template</h3>
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

                                    <form action="{{url('admin/email_templates')}}" method="post">

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type = "hidden" name = "id" value = "{{$emailTemplate->id}}">
                                        {{--<!-- text input -->--}}

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="field-required">Display Title</label>
                                                    <input  {{$readonly}} type="text"  value="{{old('title')?old('title'):$emailTemplate->title}}"  class="form-control" name="title">
                                                </div>

                                                <div @if($emailTemplate->type=='sms') hidden @endif class="form-group">
                                                    <label class="field-required">Subject</label>
                                                    <input type="text"  value="{{old('subject')?old('subject'):$emailTemplate->subject}}"  class="form-control" name="subject">
                                                </div>
                                            </div>
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <!-- <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea class="form-control">{{$emailTemplate->description}}</textarea>
                                                </div> -->
                                                <div class="form-group">
                                                    <label>Status</label><br>
                                                    <label><input type="radio" name="status" value="1" {{$emailTemplate->status?'checked':''}}> On</label>
                                                    <label><input type="radio" name="status" value="0" {{!$emailTemplate->status?'checked':''}}> Off</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>Type</label><br>
                                                    <label><input type="radio" name="type" value="email" {{($emailTemplate->type == 'email' || is_null($emailTemplate->type)) ?'checked':''}}> Email</label>
                                                    <label><input type="radio" name="type" value="sms" {{$emailTemplate->type == 'sms' ?'checked':''}}> SMS</label>
                                                </div>
                                                @if($emailTemplate->type=='email')
                                                <div class="form-group">
                                                    <label>Admin Copy</label><br>
                                                    <label><input type="radio" name="admin_copy" value="1" {{$emailTemplate->admin_copy?'checked':''}}> On</label>
                                                    <label><input type="radio" name="admin_copy" value="0" {{!$emailTemplate->admin_copy?'checked':''}}> Off</label>
                                                </div>
                                                @endif
                                                @if($emailTemplate->title == "Reservation Details" || $emailTemplate->title == "Reservation Details With Discount")
                                                <div class="form-group">
                                                    <label>Use HTML</label><br>
                                                    <label><input type="radio" name="use_html" value="1" {{$emailTemplate->use_html?'checked':''}}> On</label>
                                                    <label><input type="radio" name="use_html" value="0" {{!$emailTemplate->use_html?'checked':''}}> Off</label>
                                                </div>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                @if($readonly=="")
                                                <p style="color: red">Templates are editable (no readonly) for programmer</p>
                                                @endif
                                                <div class="form-group">
                                                    <label class="field-required">Fixed Body</label>
                                                    <textarea rows="10"  class="form-control mceEditor"  name="static_body">{!! $emailTemplate->static_body !!}</textarea>

                                                </div>
                                                @if(strtolower($emailTemplate->type)!='')
                                                <div @if($emailTemplate->type=='sms') hidden @endif  class="form-group">
                                                    <label class="">Email Body</label>
                                                    <textarea rows="10"  class="form-control mceEditor"  name="dynamic_body">{{old('dynamic_body')?old('dynamic_body'):$emailTemplate->dynamic_body}}</textarea>

                                                </div>
                                                @endif
                                            </div>
                                        </div>



                                        <button class="btn btn-primary" type="submit">Update</button>

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