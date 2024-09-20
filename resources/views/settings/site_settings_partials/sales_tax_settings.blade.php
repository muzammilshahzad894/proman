<div class="modal fade" id="saleTax_Settings" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalCenterTitle">Sale Tax Settings</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('config.enable_sales_tax') ? 'has-error' : '' }}">
                            <input id="enable_sales_tax_hidden" type="hidden" name="config[enable_sales_tax]" value="0" />
                            <input type="checkbox" name="config[enable_sales_tax]" id="enable_sales_tax" {{ old('config.enable_sales_tax', @$config->enable_sales_tax) ? 'checked' : '' }} value="1">
                            <label for="enable_sales_tax">Enable Sales Tax</label>
                            @foreach ($errors->get('config.enable_sales_tax') as $message)
                                <span class="help-block text-danger">{{ $message }}</span>
                            @endforeach
                        </div>
                        <div class="form-group {{ $errors->has('config.sales_tax_price') ? 'has-error' : '' }}"
                            style="max-width: 130px;">
                            <label><strong>{{ config('site.bts_dual_taxes') ? 'NV ' : '' }}Sales Tax
                                    %</strong></label><br>
                            <div class="input-group">
                                <input type="number" autocomplete="off" class="form-control" id="sales_tax_price"
                                    @if (@$config->enable_sales_tax != true) readonly @endif placeholder="0"
                                    name="config[sales_tax_price]" value="{{ @$config->sales_tax_price }}">
                                <span class="input-group-addon">%</span>
                            </div>
                            @foreach ($errors->get('config.sales_tax_price') as $message)
                                <span class="help-block text-danger">{{ $message }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Settings</button>
            </div>
        </div>
    </div>
</div>
