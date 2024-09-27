<div class="modal fade" id="amenities-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Add Amenities</h4>
      </div>
      <div class="modal-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Amenities</th>
              <th></th>
            </tr>
          </thead>

          <tbody>
            @foreach($amenities as $amenity)
            <tr>
              <td>{{ $amenity->title }} </td>
              <td><input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"></td>
            </tr>
            @endforeach
          </tbody>


        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Add</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>
