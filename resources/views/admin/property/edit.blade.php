@extends('layouts.default')

@section('title')
Edit Property
@parent
@stop

@section('css')
<link rel="stylesheet" href="{{url('/')}}/plugins/dropzone/dropzone.css">
<link rel="stylesheet" href="{{url('/')}}/plugins/dropzone/basic.css">
<link rel="stylesheet" href="{{url('/')}}/css/property/style.css">
@stop

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        @include('admin.property.partials._form', [
            'url' => route('admin.property.update', $property->id),
            'imageModal' => view('admin.property.partials.update_images_modal'),
            'property' => $property,
            'season_rates_modal' => view('admin.property.edit_season_rates', ['property' => $property, 'seasons' => $seasons]),
            'amenities_modal' => view('admin.property.edit_amenities_popup', ['property' => $property]),
            'submitBtnText' => 'Update',
        ])
    </section>
</div> <!-- content-wrapper -->

<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@stop

@section('javascript')
@include('layouts.js.ajax-form-submission')
@include('admin.property.partials._js')

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

    var property_id = '{{ $property->id }}';

    var uploaded_pics = {!! json_encode($property->pictures) !!}

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

                    console.log('upload started', file);
                    $('.meter').show();
                });

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

        $uploadTable.find('tbody').html('');

        // Create the mock file:
        var mockFiles = [];

        console.log(uploaded_pics);
        var id = $(this).attr('data-id');
        $.each(uploaded_pics, function(index, obj) {
            mockFiles.push({
                name: obj.filename,
                size: obj.file_size,
                url: obj.file_url,
                obj: {
                    'attachment': obj
                }
            });
        });

        var myDropzone = $("#drop-id")[0].dropzone;
        if (!myDropzone) {
            myDropzone = new Dropzone("#drop-id", options);
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

    $('[data-property_type]').on('change', function(event) {
        event.preventDefault();
        $('#Rates').modal('show');

        if ($(this).val() == 'is_long_term') {
            $('.daily').hide();
            $('.weekly').hide();
            $('.deposit').show();
        } else {
            $('.daily').show();
            $('.weekly').show();
            $('.deposit').hide();
        };
    });

    @if($property->is_long_term == '1')
    $('.daily').hide();
    $('.weekly').hide();
    $('.deposit').show();
    @else
    $('.daily').show();
    $('.weekly').show();
    $('.deposit').hide();
    @endif
</script>

<style>
  @if($property->is_long_term == '1'   )
        .daily {
          display: none; 
        }
         .weekly {
          display: none; 
        }
         .deposit {
          display: table-cell; 
        }
      
      @else

        .daily {
          display: table-cell; 
        }
         .weekly {
          display: table-cell; 
        }
         .deposit {
          display: none; 
        }
    
      @endif
  </style>
@stop