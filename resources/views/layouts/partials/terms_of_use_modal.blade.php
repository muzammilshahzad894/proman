
<!-- Modal -->
<div id="terms-of-use-accepted" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      </div>
      <div class="modal-body">
        {!!term_of_use_page_content()!!}
        <div class="form-group text-right">
          <label class="label label-success">You accepted terms of use on {{terms_accept_date()}}.</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>