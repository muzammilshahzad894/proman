<div id="pending_payments" class="modal fade" role="dialog">
						  <div class="modal-dialog modal-lg">

						    <!-- Modal content-->
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Pending Payments</h4>
						      </div>
						      <div class="modal-body">
						        <table class="table table-condensed">
							<tr>
								<th>Name</th>
								<th>Tour</th>
								<th class="text-center">Added From</th>
								<th>Created At</th>
								<th>Reservation Date</th>
								
								<th>Run Date</th>
								<th>Due Amount</th>
								<th>Total Amount</th>
							</tr>
							@foreach($pending_payments as $pending_payment)
							<tr>
								<td>{{$pending_payment->full_name()}}</td>
								<td>{{@$pending_payment->tour->title}}</td>
								<td class="text-center">@if($pending_payment->from_admin) <span class="label label-primary">Admin</span>@else <span class="label label-success">Frontend</span> @endif</td>
								<td>{{to_date($pending_payment->created_at,1)}}</td>
								<td>{{to_date($pending_payment->reservation_date)}}</td>
								<td>{{to_date($pending_payment->payment_due_date())}}</td>
								<td>{{to_currency($pending_payment->due_amount())}}</td>
								<td>{{to_currency($pending_payment->total_price())}}</td>
							</tr>
							@endforeach
						</table>
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						      </div>
						    </div>

						  </div>
						</div>