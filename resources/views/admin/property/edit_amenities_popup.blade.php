<div class="modal fade" id="amenities-modal">
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
                    @if ($AmenitiesCount > 0)
                    <button type="submit" class="btn btn-primary">Update</button>
                    @endif
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>