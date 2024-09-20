<div id="abandon_carts" class="modal fade" role="dialog">
						  <div class="modal-dialog modal-lg">

						    <!-- Modal content-->
						    <div class="modal-content">
						    	<form action="{{url('tours/abandon_cart_email_send')}}">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Tour Abandon Carts</h4>
						      </div>
						      <div class="modal-body">
						        <table class="table table-condensed">
							<tr>
								
								<th>Name</th>
								<th>Tour</th>
								<th class="text-center">Added From</th>
								<th>Created At</th>
								<th>Reservation Date</th>
								
								
								<th>Total Amount</th>
							</tr>
							@foreach($incompleted_reservations as $incompleted_reservation)
							<tr>
								
								<td><label><input type="checkbox" name="ids[]" value="{{$incompleted_reservation->id}}"> {{$incompleted_reservation->full_name()}}</label></td>
								<td>{{@$incompleted_reservation->tour->title}}</td>
								<td class="text-center">@if($incompleted_reservation->from_admin) <span class="label label-primary">Admin</span>@else <span class="label label-success">Frontend</span> @endif</td>
								<td>{{to_date($incompleted_reservation->created_at)}}</td>
								<td>{{to_date($incompleted_reservation->reservation_date)}}</td>
								
								<td>{{to_currency($incompleted_reservation->total_price())}}</td>
							</tr>
							@endforeach
							<tfoot>
								<tr>
									<td><label><input id="checkAll" type="checkbox" name=""> Select All</label></td>
								</tr>
							</tfoot>
						</table>
						      </div>
						      <div class="modal-footer">
						        <button type="submit" class="btn btn-danger" >Send Email To Selected</button>
						        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						      </div>
						      </form>
						    </div>

						  </div>
						</div>
