<div id="emai-sent-settings" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Emails will be sent to: </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-condensed">
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center">Customer</th>
                                <th class="text-center">ADMIN</th>
                                <th class="text-center">House Keeper</th>
                            </tr>
                            <tr>
                                <td><b>Web Reservation</b></td>
                                <td class="text-center"><input @if(@$config->web_reservation_customer) checked="" @endif type="checkbox" name="config[web_reservation_customer]"></td>
                                <td class="text-center"><input @if(@$config->web_reservation_admin) checked="" @endif type="checkbox" name="config[web_reservation_admin]"></td>
                                <td class="text-center"><input @if(@$config->web_reservation_housekeeper) checked="" @endif type="checkbox" name="config[web_reservation_housekeeper]"></td>
                            </tr>
                            <tr>
                                <td><b>Admin Reservation</b></td>
                                <td class="text-center"><input @if(@$config->admin_reservation_customer) checked="" @endif type="checkbox" name="config[admin_reservation_customer]"></td>
                                <td class="text-center"><input @if(@$config->admin_reservation_admin) checked="" @endif type="checkbox" name="config[admin_reservation_admin]"></td>
                                <td class="text-center"><input @if(@$config->admin_reservation_housekeeper) checked="" @endif type="checkbox" name="config[admin_reservation_housekeeper]"></td>
                            </tr>

                            <tr style="display: none">
                                <td><b>Payment Request</b></td>
                                <td class="text-center"><input @if(@$config->request_payment_customer) checked="" @endif type="checkbox" name="config[request_payment_customer]"></td>
                                <td class="text-center"><input @if(@$config->request_payment_admin) checked="" @endif type="checkbox" name="config[request_payment_admin]"></td>
                                <td class="text-center"><input @if(@$config->request_payment_housekeeper) checked="" @endif type="checkbox" name="config[request_payment_housekeeper]"></td>
                            </tr>

                            <tr>
                                <td><b>Automated Payment</b></td>
                                <td class="text-center"><input @if(@$config->auto_payment_customer) checked="" @endif type="checkbox" name="config[auto_payment_customer]"></td>
                                <td class="text-center"><input @if(@$config->auto_payment_admin) checked="" @endif type="checkbox" name="config[auto_payment_admin]"></td>
                                <td class="text-center"><input @if(@$config->auto_payment_housekeeper) checked="" @endif type="checkbox" name="config[auto_payment_housekeeper]"></td>
                            </tr>

                            <tr style="display: none">
                                <td><b>Final Payment</b></td>
                                <td class="text-center"><input @if(@$config->final_payment_customer) checked="" @endif type="checkbox" name="config[final_payment_customer]"></td>
                                <td class="text-center"><input @if(@$config->final_payment_admin) checked="" @endif type="checkbox" name="config[final_payment_admin]"></td>
                                <td class="text-center"><input @if(@$config->final_payment_housekeeper) checked="" @endif type="checkbox" name="config[final_payment_housekeeper]"></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><label><input type="checkbox" id="checkAll" name=""> Select All</label></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>

    </div>
</div>