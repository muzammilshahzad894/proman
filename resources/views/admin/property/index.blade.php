@extends('layouts.default')

@section('title')
Properties
@parent
@stop

@section('css')
<link rel="stylesheet" href="{{url('/')}}/plugins/dropzone/dropzone.css">
<link rel="stylesheet" href="{{url('/')}}/plugins/dropzone/basic.css">
<link rel="stylesheet" href="{{url('/')}}/css/property/list.css">
<style>
.icon-btn-group-left {
    padding: 8px 12px;
    background-color: #ebeff3;
    border-right: 1px solid #c0d9fd;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    transition: background-color 0.3s ease;
    cursor: pointer;
}
.icon-btn-group-center {
    padding: 8px 12px;
    background-color: #ebeff3;
    border-right: 1px solid #c0d9fd;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    transition: background-color 0.3s ease;
    cursor: pointer;
}
.icon-btn-group-right {
    padding: 8px 12px;
    background-color: #ebeff3;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    transition: background-color 0.3s ease;
    cursor: pointer;
}
</style>
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
                            <div class="col-md-12 d-flex justify-content-between">
                                <h3 class="box-title">View Properties</h3>
                                <a href="{{ url('admin/property/create') }}" class="btn btn-success">Add Property</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <form action="{{ url('admin/properties') }}" method="GET" class="form-inline" role="form">
                                    <div class="form-group">
                                        <label>Search: </label>
                                        <input 
                                            type="text" 
                                            name="search" 
                                            class="form-control input-full" 
                                            placeholder="Search by Property name" 
                                            value={{ isset($search) ? $search : '' }}
                                        >
                                    </div>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                                <select name="type" class="form-control" style="width: 140px; margin-left: 15px;" onchange="redirectToURL(this)">
                                    <option 
                                        value="{{ url('admin/properties') }}"
                                    >
                                        All
                                    </option>
                                    <option 
                                        value="{{ request()->fullUrlWithQuery(['type' => 'vacationRental']) }}"
                                        @if($type=='vacationRental') selected @endif
                                    >
                                        Vacation Rental
                                    </option>
                                    <option 
                                        value="{{ request()->fullUrlWithQuery(['type' => 'longTerm']) }}"
                                        @if($type=='longTerm') selected @endif
                                    >
                                        Long Term
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class=""></div>
                        @foreach($properties as $key => $property)
                        <div class="row" data-delete="{{ url('admin/property/delete') }}/{{$property->id}}">
                            <!-- Property List -->
                            <div class="col-lg-12">
                                <div class="property-item">
                                    <div class="property-content d-flex flex-nowrap align-items-center">
                                        <div class="property-img-sec">
                                            @if ($property->pictures->count())
                                                <img
                                                    src="{{url('/uploads/properties/')}}/{{$property->mainpictures->last()->filename}}"
                                                    alt="Avatar"
                                                    title="{{$property->mainpictures->last()->title}}"
                                                    height="60"
                                                    width="60"
                                                >
                                            @else
                                                <img
                                                    src="{{url('/uploads/properties/default.png')}}"
                                                    alt="Avatar"
                                                    title="Default Image"
                                                    height="60"
                                                    width="60"
                                                >
                                            @endif
                                        </div>
                                        <div class="property-name-sec">
                                            <h6 class="font-weight-bold m-0">Name:</h6>
                                            {{ $property->title }}
                                        </div>
                                        <div class="property-rating-sec">
                                            <div>
                                                <h6 class="font-weight-bold m-0">Rating</h6>
                                                <span class="property-rating">
                                                    <span class="fa fa-star-o"></span>
                                                    <span class="fa fa-star-o"></span>
                                                    <span class="fa fa-star-o"></span>
                                                    <span class="fa fa-star-o"></span>
                                                    <span class="fa fa-star-o"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="type-unit-sec">
                                            <div>
                                                <h6 class="font-weight-bold m-0">Type/Unit</h6>
                                                <!-- {{ $property->category_id }}<br> -->
                                                @if($property->bedroom_id>0) (Beds {{$property->bedroom_id}})
                                                @endif

                                                @if($property->bathrrom_id>0) (Bath {{$property->bathroom_id}})
                                                @endif

                                                @if($property->is_vacation>0) (Vacation)
                                                @endif

                                                @if($property->is_long_term>0) (Longterm)
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="property-item-actions text-right btn-group d-flex property-action-sec" style="font-size: 0.878vw; display: none;" role="group" aria-label="Actions">
                                            <a
                                                data-toggle="modal"
                                                href='#Pictures-{{$key}}'
                                                class="edit-pics-button icon-btn-group-left"
                                                data-id="{{ $property->id }}"
                                                data-pics="{{ json_encode($property->pictures) }}"
                                                title="Manage Images"
                                                data-placement="top"
                                            >
                                                <i class="fa fa-image"></i>
                                            </a>
                                            <a 
                                                class="icon-btn-group-center" 
                                                data-toggle="modal" 
                                                href='#Rates-{{$key}}' 
                                                title="Manage Rates" 
                                                data-placement="top"
                                            >
                                                <i class="fa fa-usd"></i>
                                            </a>

                                            <a 
                                                class="icon-btn-group-center" 
                                                data-toggle="modal" 
                                                href='#Amenities-{{$key}}'
                                                title="Manage Amenities"
                                                data-placement="top"
                                            >
                                                <i class="fa fa-list"></i>
                                            </a>
                                            <a
                                                class="icon-btn-group-center"
                                                data-toggle="modal" 
                                                href='#'
                                                title="Add Reservation"
                                                data-placement="top"
                                            >
                                                <i class="fa fa-calendar-plus-o"></i>
                                            </a>
                                            <!-- <a href="{{ url('admin/property/reservation-calendar') }}/{{ $property->id }}" class="btn btn-sm btn-default">Calender</a> -->
                                            <a 
                                                class="icon-btn-group-center"
                                                href='#'
                                                title="Calendar"
                                                data-placement="top"
                                            >
                                                <i class="fa fa-calendar"></i>
                                            </a>
                                            <!-- <a href="{{ url('admin/property/'. $property->id)  }}" class="btn btn-sm btn-default view-reservations">
                                                View Reservations
                                                <span class="badge badge-light">3</span>
                                            </a> -->
                                            <a 
                                                class="icon-btn-group-center"
                                                href='#'
                                                title="View Reservations"
                                                data-placement="top"
                                            >
                                                <i class="fa fa-calendar-check-o"></i>
                                            </a>
                                            <!-- // create one ... icon and when click on it then show the dropdown with the following options edit and delete -->
                                            <div class="dropdown">
                                                <a 
                                                    class="icon-btn-group-right dropdown-toggle" 
                                                    href="#" 
                                                    id="dropdownMenuLink" 
                                                    data-toggle="dropdown" 
                                                    title="More" 
                                                    data-placement="top"
                                                >
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <li>
                                                        <a href="{{ url('admin/property') }}/{{ $property->id }}/edit">Edit</a>
                                                    </li>
                                                    <li>
                                                        <a data-toggle="tooltip" title="Delete" data-delete-trigger href="#">Delete</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
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

@foreach($properties as $key => $property )
<div class="modal fade" id="Rates-{{$key}}">
    <div class="modal-dialog" style="width: 769px;">
        <div class="modal-content">
            <form action="{{ url('admin/property') }}/{{ $property->id }}" method="POST" role="form" class="ajax-submission">
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
            <form action="{{ url('admin/property/update-property-amenities') }}/{{ $property->id }}" method="POST" role="form" class="ajax-submission">
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
                                    <input type="checkbox" name="value-{{ $amenity->id}}" @if ($amenity->pivot->value ==1) checked @endif value="1">
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
                    @if ($AmenitiesCount > 0 )
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
            <form id="form-edit-{{ $property->id }}" method="post" action="{{ url('/admin/property/update/pictures/' . $property->id) }}" class="ajax-submission">
                @csrf
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
                        <div class="col-md-12">
                            <div id="drop-id-{{ $property->id }}" class="dropzone m-b-20"> </div>
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
@include('layouts.js.ajax-form-submission')
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
        var encodedFilename = encodeURIComponent(res.attachment.filename);
        var html = "<tr data-filename='" + file.name + "'>";
        html = html + "<td><div style='background: url(" + base_url + "/uploads/properties/" + encodedFilename + "); width: 32px;height: 32px;background-size: cover;'></div><input class='form-control' name='pictures[]' type='hidden' value='" + res.attachment.id + "' /></td>";
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
                    var formId = file.previewElement.closest('form').id;
                    var propertyId = formId.split('-').pop();
                    formData.append("property_id", propertyId);

                    console.log('upload started', file);
                    $('.meter').show();
                });

                self.on('success', function(file, res) {
                    putFileinTable(file, res);
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
    
    function redirectToURL(selectElement) {
        var url = selectElement.value;
        if (url) {
            window.location.href = url;
        }
    }
</script>
@stop