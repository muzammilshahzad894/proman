@extends('layouts.default')
@section('title')
    Site Settings
    @parent
@stop

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <form id="site_settings_form" action="{{ route('admin.settings.store.site') }}" method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">{{ $setting ? $setting->title : 'Site Setting' }}
                                    <small>{{ $setting ? $setting->description : 'Site Setting description' }}</small>
                                </h3>
                            </div>
                            <div class="box-body">
                                {{-- @include('app-partials.messages', ['errors' => $errors]) --}}

                                <hr style="margin-top: 0px; margin-bottom: 0px;">

                                {{ csrf_field() }}

                                <div class="row" style="padding: 10px;">
                                    <div class="col-md-6" style="padding: 5px;">
                                        <button type="button" class="btn btn-block btn-lg btn-primary" data-toggle="modal"
                                            data-target="#saleTax_Settings">
                                            <i class="fa fa-money" aria-hidden="true"></i> Sale Tax Settings
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('settings.site_settings_partials.sales_tax_settings')
            </form>
        </section>
    </div>
    
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#enable_sales_tax').change(function() {
                if ($(this).is(':checked')) {
                    $('#sales_tax_price').prop('readonly', false);
                } else {
                    $('#sales_tax_price').prop('readonly', true);
                }
            });

            $('.enable-sales-tax-label').click(function() {
                var checkbox = $('#enable_sales_tax');
                checkbox.prop('checked', !checkbox.is(':checked')).trigger('change');
            });
        });

        //Submit Form
        $(document).ready(function() {
            $('#site_settings_form').on('submit', function(e) {
                e.preventDefault();
                $form = $(this);
                console.log($form.serialize())
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $form.serialize(),
                })
                .done(function() {
                    $('#saleTax_Settings').modal('hide');
                    swal({
                        title: "Success",
                        text: "Site settings were updated successfully.",
                        type: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                })
            })
        })
    </script>
@endsection