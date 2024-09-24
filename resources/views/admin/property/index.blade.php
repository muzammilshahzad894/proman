@extends('layouts.default')

@section('title')
Properties
@parent
@stop

@section('css')
<link rel="stylesheet" href="{{url('/')}}/plugins/dropzone/dropzone.css">
<link rel="stylesheet" href="{{url('/')}}/plugins/dropzone/basic.css">

<style>
    .dz-image img {
        width: 125px;
        height: 125px;
    }
</style>
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
                                <h3 class="box-title">View Properties</h3>
                                <p class="m-t-10 m-b-10">
                                    <a href="{{ url('admin/property/create') }}" class="btn btn-success">Add Property</a>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <form action="{{ url('admin/property') }}" method="GET" class="form-inline" role="form">
                                    <div class="form-group">
                                        <input type="text" name="search" class="form-control input-full" id="" placeholder="Search by Property name" value={{ isset($search) ? $search : '' }}>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            </div>
                            <div class="col-md-6 col-lg-6">

                            </div>
                        </div>

                        <div class="row m-t-20">
                            <div class="col-md-6 col-lg-6">
                                <span><a href="{{ url('admin/property') }}" class="btn btn-primary btn-sm">All</a></span>
                                <span><a href="{{ url('admin/property?search=vacationRental') }}" class="btn btn-primary btn-sm">Vacation Rental</a></span>
                                <span><a href="{{ url('admin/property?search=longTerm') }}" class="btn btn-primary btn-sm">Long Term</a></span>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <table class="table table-responsive table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Preview</th>
                                            <th>Property Name</th>
                                            <th>Rating</th>
                                            <th>Type/Unit</th>
                                            <th>Rates</th>
                                            <th>Images</th>
                                            <th>Ameneties</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($properties as $key => $property)
                                        <tr data-delete="{{ url('admin/property/delete') }}/{{$property->id}}">
                                            <td>
                                                @if ($property->pictures->count())
                                                <img
                                                    src="{{url('/uploads/properties/')}}/{{$property->mainpictures->last()->filename}}"
                                                    title="{{$property->mainpictures->last()->title}}"
                                                    width="100" height="80">
                                                @endif
                                            </td>
                                            <td>{{ $property->title }}</td>
                                            <td>
                                                <span class="fa fa-star-o"></span>
                                                <span class="fa fa-star-o"></span>
                                                <span class="fa fa-star-o"></span>
                                                <span class="fa fa-star-o"></span>
                                                <span class="fa fa-star-o"></span>
                                            </td>
                                            <td>{{ $property->category_id }}<br>
                                                @if($property->bedroom_id>0) Beds {{$property->bedroom_id}}
                                                <br>@endif

                                                @if($property->bathrrom_id>0) Bath {{$property->bathroom_id}}
                                                <br>@endif

                                                @if($property->is_vacation>0) Vacation
                                                <br>@endif

                                                @if($property->is_long_term>0) Longterm
                                                <br>@endif

                                            </td>
                                            <td><a data-toggle="modal" href='#Rates-{{$key}}' class="btn btn-primary">Rates</a></td>
                                            <td>
                                                <a data-toggle="modal"
                                                    href='#Pictures-{{$key}}'
                                                    class="edit-pics-button btn btn-primary"
                                                    data-id="{{ $property->id }}"
                                                    data-pics="{{ json_encode($property->pictures) }}">Pictures
                                                </a>
                                            </td>
                                            <td><a data-toggle="modal" href='#Amenities-{{$key}}' class="btn btn-primary">Amenities</a></td>
                                            <td><a href="{{ url('admin/reservation/create') }}/{{ $property->id }}" class="btn btn-primary">Add Reservation</a></td>
                                            <td><a href="{{ url('admin/property/reservation-calendar') }}/{{ $property->id }}" class="btn btn-primary">Calender</a></td>
                                            <td><a href="{{ url('admin/property/'. $property->id)  }}" class="btn btn-primary">View Res</a></td>
                                            <td class="text-center">
                                                <a href="{{ url('admin/property') }}/{{ $property->id }}/edit"><i class="fa fa-pencil"></i></a>
                                                <a data-delete-trigger href="#"><i class="fa fa-trash"></i></a>
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

@foreach($properties as $key => $property )

<div class="modal fade" id="Rates-{{$key}}">
    <div class="modal-dialog" style="width: 769px;">
        <div class="modal-content">
            <form action="{{ url('admin/property') }}/{{ $property->id }}" method="POST" role="form">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Rates</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Season</th>
                                <th>Dates</th>
                                @if($property->is_vacation)
                                <th style="width: 150px; ">Daily</th>
                                <th style="width: 150px; ">Weekly</th>
                                <th style="width: 150px; ">Monthly</th>
                                @else
                                <th style="width: 150px; ">Monthly</th>
                                <th style="width: 150px;">Deposit</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seasons as $season)
                            <tr>
                                <td>{{ $season->title }} <input type="hidden" name="season_id[]" value="{{ $season->id }}"> </td>
                                <td>{{ $season->from_month }}/{{ $season->from_day }} - {{ $season->to_month }}/{{ $season->to_day }}</td>
                                @foreach ($season->rates($property->id) as $propertyd)


                                @if ($propertyd->pivot->property_id != $property->id)
                                <?php continue;  ?>
                                @endif

                                @if($property->is_vacation)
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-addon">$ </div>
                                        <input type="text" class="form-control" name="daily_rate[]" value="{{number_format_without_comma(@$propertyd->pivot->daily_rate)}}">
                                    </div>
                                </td>
                                @else
                                <input type="hidden" class="form-control" name="daily_rate[]">
                                @endif

                                @if($property->is_vacation)
                                <td>
                                    @if($season->allow_weekly_rates==1)
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" class="form-control" name="weekly_rate[]" value="{{number_format_without_comma(@$propertyd->pivot->weekly_rate)}}">
                                    </div>
                                    @else
                                    <input type="hidden" class="form-control" name="weekly_rate[]">
                                    @endif
                                </td>
                                @else
                                <input type="hidden" class="form-control" name="weekly_rate[]">
                                @endif
                                
                                <td>
                                    @if($season->allow_monthly_rates==1)
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" class="form-control" name="monthly_rate[]" value="{{number_format_without_comma(@$propertyd->pivot->monthly_rate)}}">
                                    </div>
                                    @else
                                    <input type="hidden" class="form-control" name="monthly_rate[]">
                                    @endif
                                </td>
                                @if($property->is_long_term && $season->allow_monthly_rates==1)
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" class="form-control" name="deposit[]" value="{{number_format_without_comma(@$propertyd->pivot->deposit)}}">
                                    </div>
                                </td>
                                @else
                                <td>
                                    <input type="hidden" class="form-control" name="deposit[]">
                                </td>
                                @endif
                                
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div> <!-- #Rates -->


<div class="modal fade" id="Amenities-{{$key}}">
    <div class="modal-dialog" style="width: 769px;">
        <div class="modal-content">
            <form action="{{ url('admin/property/update-property-amenities') }}/{{ $property->id }}" method="POST" role="form">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Amenities</h4>
                </div>
                <div class="modal-body">
                    <?php $AmenitiesCount = $property->amenities->count(); ?>
                    @if ($AmenitiesCount > 0)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Amenities</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($property->amenities as $amenity)
                            <tr>
                                <td>{{ $amenity->title }}
                                    <input type="hidden" name="amenity_id[]" value="{{ $amenity->id }}">
                                </td>
                                <td>
                                    @if($amenity->type=="InputText")
                                    <input type="text" name="value-{{ $amenity->id}}" id="input" class="form-control" value="{{ $amenity->pivot->value }}">
                                    @endif

                                    @if($amenity->type=="CheckBox")
                                    <input type="checkbox" name="value-{{ $amenity->id}}"
                                        @if ($amenity->pivot->value ==1)
                                    checked
                                    @endif
                                    value="1">
                                    @endif

                                    @if($amenity->type=="Dropdown")
                                    <select name="value-{{ $amenity->id}}" id="input" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($amenity->dropdownValues as $value)
                                        <option
                                            @if ($amenity->pivot->value == $value)
                                            selected
                                            @endif
                                            value="{{ $value->id }}">{{ $value->value }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <p> No Amenities attaced.</p>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    @if ( $AmenitiesCount > 0 )
                    <button type="submit" class="btn btn-primary">Update</button>
                    @endif
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div> <!-- #Amenities -->


<div class="modal fade" id="Pictures-{{$key}}">
    <div class="modal-dialog" style="width: 769px;">
        <div class="modal-content">
            <form id="form-edit-{{ $property->id }}" method="post" action="{{ url('/admin/property/update/pictures/' . $property->id) }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Pictures</h4>
                </div>
                <div class="modal-body">

                    {{--<ul class="list-inline">--}}
                    {{--<li>--}}
                    {{--@foreach ($property->pictures as $picture)--}}
                    {{--<img src="{{url('/uploads/properties/')}}/{{$picture->filename}}" width="100" height="80">--}}
                    {{--@endforeach--}}
                    {{--</li>--}}
                    {{--</ul>--}}

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div id="drop-id-{{ $property->id }}" class="dropzone  m-b-20"> </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="well uploadTable-container">
                                <table class="table table-hover uploadTable">
                                    <thead>
                                        <tr>
                                            <th>Preview</th>
                                            <th>Title</th>
                                            <th>Order</th>
                                            <th>Main</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div> <!-- #Pictures -->
@endforeach

@stop

@section('javascript')

<script src="{{ url('/plugins/dropzone/dropzone.js') }}"></script>
<style>
    .uploadTable-container {
        display: none;
    }
</style>

<script>
    var base_url = "{{url('/')}}";

    function handleTable(counter) {

        if (counter >= 2) {
            $('.uploadTable-container').show();
        } else {
            $('.uploadTable-container').hide();
        };

    }

    Dropzone.autoDiscover = false;
    $uploadTable = $('.uploadTable');
    var counter = 1;

    function deleteFileFromServer(id) {
        $.ajax({
            url: "delete/file/" + id,
            type: "GET",
            success: function(response) {
                console.log('Delete', response.message);
            }
        });
    }

    function putFileinTable(file, res) {
        var html = "<tr data-filename='" + file.name + "'>";
        html = html + "<td><div style='background: url(" + base_url + "/uploads/properties/" + res.attachment.filename + "); width: 32px;height: 32px;background-size: cover;'></div><input class='form-control' name='pictures[]' type='hidden' value='" + res.attachment.id + "' /></td>";
        html = html + "<td><input class='form-control' name='pic_title[]' type='text' value='" + res.attachment.title + "' /></td>";
        html = html + "<td><input style='max-width: 119px;' class='form-control' name='order[]' type='text' value='" + res.attachment.order + "'/></td>";

        if (res.attachment.main == 1) {
            var main = 'checked';
        } else {
            var main = '';
        };

        html = html + "<td><input class='' name='main' type='radio' value='" + res.attachment.id + "'  " + main + " /></td>";
        html = html + "</tr>";
        $uploadTable.find('tbody').append(html);
        counter++;
        handleTable(counter);
    }

    jQuery(document).ready(function($) {

        var options = {
            url: "{{url('/')}}/admin/upload-files",
            paramName: 'file',

            maxFilesize: 5,
            addRemoveLinks: true,
            dictResponseError: 'Server not Configured',
            acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg",
            dictDuplicateFile: "Duplicate Files Cannot Be Uploaded",
            preventDuplicates: true,
            thumbnailWidth: "300",
            thumbnailHeight: "300",

            init: function() {
                var self = this;
                // config
                self.options.addRemoveLinks = true;
                self.options.dictRemoveFile = "Delete";
                //New file added
                self.on("addedfile", function(file, dataUrl) {
                    console.log('new file added ', file.name);

                });
                // Send file starts
                self.on("sending", function(file, xhr, formData) {
                    formData.append("_token", $('meta[name="csrf-token"]').attr('content'));

                    console.log('upload started', file);
                    $('.meter').show();
                });

                // self.on('thumbnail', function(file, dataUrl) {
                //       console.log('dataUrl');
                //       console.log(dataUrl);
                //   });

                self.on('success', function(file, res) {
                    putFileinTable(file, res);
                    console.log('success');
                    console.log(res);
                });

                self.on('error', function(file) {
                    // alert('hi');
                    // $('.uploadTable').find('tbody tr').each(function(index, el) {

                    //  var el = $(el)
                    //   if (el.data('filename')==file.name) {
                    //      el.remove();
                    //   };
                    // });

                    //  console.log('error'+file);
                });


                // File upload Progress
                self.on("totaluploadprogress", function(progress) {
                    console.log("progress ", progress);
                    $('.roller').width(progress + '%');
                });

                self.on("queuecomplete", function(file, progress) {


                    $('.meter').delay(999).slideUp(999);
                });

                // On removing file
                self.on("removedfile", function(file) {

                    $('.uploadTable').find('tbody tr').each(function(index, el) {

                        var el = $(el)
                        if (el.data('filename') == file.name) {
                            el.remove();
                        };
                    });
                    counter--;
                    handleTable(counter);

                    deleteFileFromServer(file.obj.attachment.id);
                    console.log(file);
                });
            }
        };

        jQuery(document).on('click', '.edit-pics-button', function() {
            $uploadTable.find('tbody').html('');

            // Create the mock file:
            var mockFiles = [];
            var uploaded_pics = $(this).attr('data-pics');
            // var data_property = $(this).attr('data-property');
            var id = $(this).attr('data-id');
            $.each(JSON.parse(uploaded_pics), function(index, obj) {
                mockFiles.push({
                    name: obj.filename,
                    size: obj.file_size,
                    url: obj.file_url,
                    obj: {
                        'attachment': obj
                    }
                });
            });

            var myDropzone = $("#drop-id-" + id)[0].dropzone;
            if (!myDropzone) {
                myDropzone = new Dropzone("#drop-id-" + id, options);
                $.each(mockFiles, function(index, obj) {

                    // Call the default addedfile event handler
                    myDropzone.emit("addedfile", obj);

                    // And optionally show the thumbnail of the file:
                    myDropzone.emit("thumbnail", obj, obj.url);

                    putFileinTable(obj, obj.obj);
                });
            } else {
                $.each(mockFiles, function(index, obj) {

                    putFileinTable(obj, obj.obj);
                });
            }
        });
    });
</script>
@stop