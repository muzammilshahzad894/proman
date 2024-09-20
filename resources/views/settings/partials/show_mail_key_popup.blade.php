{{-- Show Transaction key Modal --}}
<div id="showTransactionModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <form action="{{url('/')}}/admin/settings/confirm_password" autocomplete="off" id="mail_password_form" method="POST" >
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" >
            <input type="hidden" name="maildriver" id="maildriver" value="{{$driver}}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="close_x_button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Show @if($driver =='mailgun') Config Key @else Password @endif</h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label  class="">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                                </div>
                                <div id="msg"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="password_button"  class="btn btn-primary">Show <i class="fa fa-eye"></i></button>
                            <button type="button" id="close_button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                </div>
            </div>
        </form>

    </div>
  </div>
{{-- Show Transaction key Modal --}}