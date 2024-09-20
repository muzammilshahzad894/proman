<!-- Third Parties Modal -->
<div class="modal fade" id="third_parties_popup" role="dialog" aria-labelledby="third_parties_popupLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3>POS & 3rd Party Settings</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label><strong>Enable Sentry</strong></label><br>
                            <input type="radio" id="sentry_enabled" name="config[is_sentry_enabled]" value="1" {{ @$config['is_sentry_enabled'] == '1' ? 'checked' : '' }} >
                            <label for="sentry_enabled">Yes</label>
                            <input type="radio" id="sentry_not_enabled" name="config[is_sentry_enabled]" value="0" {{ @$config['is_sentry_enabled'] == '0' ? 'checked' : '' }}>
                            <label for="sentry_not_enabled">No</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group"
                            id="sentryInput" style="display: none">
                            <label for="sentry_key">Sentry DSN</label>
                            <input type="password" name="config[sentry_key]" class="form-control" value="{{ @$config['sentry_key'] }}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="third_parties_popup_update_btn" class="btn btn-primary">Update Settings</button>
            </div>
        </div>
    </div>
</div>
