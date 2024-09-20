@extends('layouts.default')
@section('title')
    Programmer Settings
    @parent
@stop

@section('content')
    
<div class="content-wrapper">
    <section class="content">
        <form id="programmer_settings_form" action="{{ route('admin.settings.programmer_settings', $programmer_settings) }}" method="post">
                <div class="row">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-3">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">{{ $programmer_settings->title }}
                                    <small>{{ $programmer_settings->description }}</small>
                                </h3>
                            </div>
                            <div class="box-body">
                                <button id="show_third_parties_popup" type="button" class="btn btn-primary"> <i class="fa fa-gear"></i> POS & 3rd Party Settings </button>
                                @include('settings\partials\programmer_settings_popup')
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update Settings</button>
            </form>
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        var is_sentry_enabledValue = $('input[name="config[is_sentry_enabled]"]:checked').val();
        if (is_sentry_enabledValue == 1) {
            // Show the Sentry input field
            $('#sentryInput').css('display', 'block');
        } else {
            // Hide the Sentry input field
            $('#sentryInput').css('display', 'none');
        }

        $(document).ready(function() {
            $('input[ name="config[is_sentry_enabled]"]').change(function() {
                var is_sentry_enabledValue = $(this).val();
                if (is_sentry_enabledValue == 1) {
                    // Hide the Sentry input field
                    $('#sentryInput').css('display', 'block');
                } else {
                    // Show the Sentry input field
                    $('#sentryInput').css('display', 'none');
                }
            });
        });

        $("#show_third_parties_popup").click(function(){
            // show Modal
            $('#third_parties_popup').modal('show');
        });

        $("#third_parties_popup_update_btn").click(function(){
            // Submit Form
            $('#programmer_settings_form').submit();
            $('#third_parties_popup').modal('hide');
        });

        //Submit Form
        $(document).ready(function() {
            $('#programmer_settings_form').on('submit', function(e) {
                e.preventDefault();
                $form = $(this);
                console.log($form.serialize())
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $form.serialize(),
                })
                .done(function() {
                    swal({
                        title: "Success",
                        text: "Programmer settings were updated successfully.",
                        type: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                })
            })
        })
    </script>
@stop
