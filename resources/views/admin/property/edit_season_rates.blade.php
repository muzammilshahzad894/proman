<div class="modal fade" id="rates-modal">
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
                                <th style="width: 150px;" class="daily">Daily</th>
                                <th style="width: 150px;" class="weekly">Weekly</th>
                                <th style="width: 150px;" class="monthly">Monthly</th>
                                <th style="width: 150px;" class="deposit">Deposit</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($seasons as $season)
                            <tr>
                                <td>{{ $season->title }} <input type="hidden" name="season_id[]" value="{{ $season->id }}"> </td>
                                <td>{{ $season->from_month }}/{{ $season->from_day }} - {{ $season->to_month }}/{{ $season->to_day }}</td>
                                
                                @foreach ($season->rates($property->id) as $propertyd)
                                @if ($propertyd->pivot->property_id != $property->id)
                                <?php continue; ?>
                                @endif
                                <td class="daily">
                                    <div class="input-group">
                                        <div class="input-group-addon">$ </div>
                                        <input type="text" class="form-control" name="daily_rate[]" value="{{number_format_without_comma(@$propertyd->pivot->daily_rate)}}">
                                    </div>
                                </td>
                                <td class="weekly">
                                    @if($season->allow_weekly_rates==1)
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" class="form-control" name="weekly_rate[]" value="{{number_format_without_comma(@$propertyd->pivot->weekly_rate)}}">
                                    </div>
                                    @else
                                    <input type="hidden" class="form-control" name="weekly_rate[]">
                                    @endif
                                </td>
                                <td class="monthly">
                                    @if($season->allow_monthly_rates==1)
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" class="form-control" name="monthly_rate[]" value="{{number_format_without_comma(@$propertyd->pivot->monthly_rate)}}">
                                    </div>
                                    @else
                                    <input type="hidden" class="form-control" name="monthly_rate[]">
                                    @endif
                                </td>

                                <td class="deposit" style="display:none;">
                                    @if($season->allow_monthly_rates==1)
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" class="form-control" name="deposit[]" value="{{number_format_without_comma(@$propertyd->pivot->deposit)}}">
                                    </div>
                                    @else
                                    <input type="hidden" class="form-control" name="deposit[]">
                                    @endif
                                </td>
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
</div>