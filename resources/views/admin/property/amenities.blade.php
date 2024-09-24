<div class="modal fade" id="ammenties">
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
              <td>{{ $amenity->title }} <input type="hidden" name="amenity_id[]" value="{{ $amenity->id }}"></td>
              <td>
                @if($amenity->type=="InputText")
                <input type="text" name="value[]" id="input" class="form-control">
                @endif

                @if($amenity->type=="CheckBox")
                <input type="checkbox" name="value[]" value="1">
                @endif
                
                @if($amenity->type=="Dropdown")
                <?php
                $options = str_replace('"', "", $amenity->option); // remove quotes
                $options = explode('\r\n', $options); // convert it to array
                $options = (object) $options; // convert array to object
                ?>

                <select name="value[]" id="input" class="form-control">
                  <option value="" selected disabled>Select</option>
                  @foreach ($options as $key => $v)
                  <option value="{{ $v }}">{{ $v }}</option>
                  @endforeach
                </select>
                @endif
              </td>
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