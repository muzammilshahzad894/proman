<?php

namespace App\Http\Controllers\Admin;

use App\helpers\Calendar;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Requests\ReservationRequest;
use App\Models\Housekeeper;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Reservation;
use App\Models\EmailTemplate;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Log;
use DB;
use Mail;
use Session;
use App\Models\Owner;
use App\Models\Refund;
use App\Models\sentemail;
use App\Helpers\ResponseHelper;
use Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $t = date_default_timezone_set("America/Denver");
        $t = date("Y-m-d");
        $keyword = @$_GET['q'];
        
        $reservations  = Reservation::select('reservations.*')->where('departure', '>', $t)
            ->join('users', 'reservations.customer_id', '=', 'users.id')
            ->where(function ($query) use ($keyword, $request) {
                // $query->where(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'LIKE', "%$keyword%");
                $query->where('users.name', 'LIKE', "%$keyword%");
                if ($request->status == 'cancelled') {
                    $query->where('cancelled', 1);
                } else {
                    $query->where('cancelled', 0);
                }
            })
            ->orderBy('arrival', 'desc')
            ->paginate(config('pagination.per_page') ?? 10);

        return view('admin.reservation.index')
            ->with('reservations', $reservations);
    }

    public function step1()
    {
        try {
            $properties = Property::where('status', '=', 1)->orderBY('display_order', 'ASC')->paginate(config('pagination.per_page') ?? 10);
            return view('admin.reservation.step1')
                ->with('properties', $properties);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($property_id)
    {
        try {
            $property = Property::find($property_id);
            $house_keepers = Housekeeper::all();
            $customers = User::whereType('customer')->get();
            $reservation = Reservation::where('property_id', '=', $property->id)->where('cancelled', 0)->get();
            $calendar = Calendar::RenderCalendar($property);
            
            return view('admin.reservation.create')
                ->with('property', $property)
                ->with('house_keepers', $house_keepers)
                ->with('customers', $customers)
                ->with('calendar', $calendar);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReservationRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $input = $request->all();
            $reservation = new Reservation();
            $reservation->property_id = $request->get('property_id');
            $property = Property::find($request->get('property_id'));

            $available = $property->propertyCheck($request->get('arrival'), $request->get('departure'));

            if (!$available) {
                return ResponseHelper::jsonResponse('error', 'Property is not available for selected dates.', null, 500);
            }

            $gateway_result = [];
            $customer_profile = "";
            $customer_payment_profile = "";
            $payment_card_last_four = "";
            if ($request->payment_mode == 'credit card') {
                $request->merge([
                    'address_line_1' => $request->address,
                ]);
                $gateway_result = authorized_payment($request, $request, $request->get('amount_deposited'), $charge_customer = 0);
                if ($gateway_result['status'] != true) {
                    return ResponseHelper::jsonResponse('error', $gateway_result['message'], null, 500);
                    // $request->session()->flash('error', $gateway_result['message']);
                    // return redirect()->back()->withInput();
                } else {
                    $customer_profile = $gateway_result['customer_profile'];
                    $customer_payment_profile = $gateway_result['customer_payment_profile'];
                    $payment_card_last_four = $gateway_result['card_last_four'];
                    $payment_transaction_id = $gateway_result['transaction_id'];
                }
            }

            $reservation->customer_id = $this->getCustomerId($request);
            $reservation->address = $request->get('address');
            $reservation->city = $request->get('city');
            $reservation->state = $request->get('state');
            $reservation->zip = $request->get('zip');
            $reservation->phone = $request->get('phone');
            $reservation->adults = $request->get('adults');
            $reservation->children = $request->get('children') ? $request->get('children') : 0;
            $reservation->pets = ($request->get('pets')) ? $request->get('pets') : '0';
            $reservation->arrival = Carbon::parse($request->get('arrival'));
            $reservation->departure = Carbon::parse($request->get('departure'));
            $reservation->special_rate = $request->special_rate ? 1 : 0;
            $reservation->is_non_profit_reservation = $request->is_non_profit_reservation ? 1 : 0;
            $reservation->is_add_pet_fee = $request->is_add_pet_fee ? 1 : 0;

            $reservation->total_amount = $request->get('total_amount');
            $reservation->notes = $request->get('notes');

            $reservation->housekeeper_id = $request->get('housekeeper_id');
            $reservation->status = isset($request->status) ? ($request->get('status') ? 1 : 0) : 0;

            $reservation->customer_profile = $customer_profile;
            $reservation->customer_payment_profile = $customer_payment_profile;
            $reservation->customer_card = $payment_card_last_four;
            $reservation->from_admin = Auth::user()->type == 'admin' ? 1 : 0;
            /*saving reservations totals in reservation table instead of payments*/
            $reservation->lodging_amount = $request->get('lodging_amount');
            $reservation->total_amount = $request->get('total_amount');
            $reservation->cleaning_fee = $request->get('clearing_fee');
            // $payment->line_items_total = $rates->line_items_total;
            if ($request->has('pet_fee')) {
                $reservation->pet_fee = $request->get('pet_fee');
            }
            $reservation->lodgers_tax = $request->get('lodgers_tax');
            $reservation->sales_tax = $request->get('sales_tax');
            /*end saving reservations totals in reservation table instead of payments*/
            $reservation->save();

            //return $this->authorized_payment($request, $reservation->id, $request->get('total_amount'));
            $this->addPayment($request, $reservation->id, $reservation->customer_id, $gateway_result);
            
            // send emails
            if (!isset($input['dont_send_email'])) {
                try {
                    if (config('site.admin_reservation_customer')) {
                        info('sending admin reservation email to customer');
                        EmailController::sendCustomerEmail($reservation);
                    }
                } catch (Exception $e) {
                    Log::info('sendCustomerEmail dont work');
                    Log::info($e);
                }

                try {
                    if ($reservation->housekeeper_id != null) {
                        if (config('site.admin_reservation_housekeeper')) {
                            info('sending admin reservation email to Housekeeper');
                            EmailController::sendHouseKeeperEmail($reservation);
                        }
                    }
                } catch (Exception $e) {
                    Log::info('sendHouseKeeperEmail dont work');
                    Log::info($e);
                }
            }
            
            try {
                if (config('site.admin_reservation_admin')) {
                    info('sending admin reservation email to admin');
                    EmailController::sendAdminEmail($reservation);
                }
            } catch (Exception $e) {
                Log::info('sendAdminEmail dont work');
                Log::info($e);
            }
            
            // Commit the transaction after all operations
            DB::commit();
            
            return ResponseHelper::jsonResponse('success', 'Reservation added successfully.', route('admin.reservations.index'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }

    private function getCustomerId($request)
    {
        $input = $request->all();
        if (isset($input['returning_customer_checkbox']) && $input['returning_customer_checkbox'] == '1') {
            return $input['customer_id'];
        } else {
            return $this->addCustomer($request);
        }
    }

    private function updateCustomer($request, $customer_id)
    {
        $input = $request->all();

        $user  = User::find($customer_id);

        $user->first_name = $request->get('first_name');
        $user->last_name  = $request->get('last_name');
        $user->email      = $request->get('email');
        $user->update();

        return $user->id;
    }


    private function addCustomer($request)
    {
        $user = new User();
        $user->name = $request->get('first_name') . ' ' . $request->get('last_name');
        $user->email = $request->get('email');
        $user->type = 'customer';
        $user->save();
        return $user->id;
    }

    private function addPayment($request, $reservation_id, $customer_id, $gateway_result = [])
    {
        $payment = new Payment();
        $payment->reservation_id = $reservation_id;
        $payment->lodging_amount = $request->get('lodging_amount');
        $payment->total = $request->get('total_amount');
        $payment->cleaning_fee = $request->get('clearing_fee');
        // $payment->line_items_total = $rates->line_items_total;

        if ($request->has('pet_fee')) {
            $payment->pet_fee = $request->get('pet_fee');
        }

        $payment->lodgers_tax = $request->get('lodgers_tax');
        $payment->sales_tax = $request->get('sales_tax');
        $payment->amount_deposited = $request->get('amount_deposited');
        $payment->payment_mode = $request->get('payment_mode');
        if (!empty($gateway_result)) {
            $payment->transaction_id = $gateway_result['transaction_id'];
            $payment->card_last_four = $gateway_result['card_last_four'];
        }
        $payment->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::find($id);

        $property = Property::find($reservation->property_id);

        $house_keepers = Housekeeper::all();

        $customers = User::whereType('customer')->get();

        $calendar = Calendar::RenderCalendar($property);

        if ($property == null) {
            dd('no property with that id exists');
        }

        return view('admin.reservation.show')
            ->with('reservation', $reservation)
            ->with('property', $property)
            ->with('house_keepers', $house_keepers)
            ->with('customers', $customers)
            ->with('calendar', $calendar);;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $reservation = Reservation::find($id);

        $property = Property::find($reservation->property_id);

        $house_keepers = Housekeeper::all();

        $ownerdata = Owner::where('id', $property->owner)->first();

        $customers = User::whereType('customer')->get();

        $calendar = Calendar::RenderCalendar($property);

        if ($property == null) {
            dd('no property with that id exists');
        }

        return view('admin.reservation.edit')
            ->with('reservation', $reservation)
            ->with('property', $property)
            ->with('house_keepers', $house_keepers)
            ->with('customers', $customers)
            ->with('ownerdata',  $ownerdata)
            ->with('calendar', $calendar);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $input = $request->all();

        $reservation   = Reservation::find($id);

        $property = Property::find($reservation->property_id);

        $available = $property->isPropertyAvailable($request->get('arrival'), $request->get('departure'));

        // if (!$available) {

        //     $request->session()->flash('error', 'Property is not available for selected dates.');
        //     return redirect()->back();

        // }

        if (isset($input['owner_id']) && $input['owner_id'] == 1) {

            $reservation->is_an_owner_reservation = 1;
            $reservation->customer_id   = $property->owner;
        } else {
            $reservation->is_an_owner_reservation = 0;
            $reservation->customer_id = $this->updateCustomer($request, $reservation->customer_id);
        }


        $reservation->address = $request->get('address');
        $reservation->city    = $request->get('city');
        $reservation->state   = $request->get('state');
        $reservation->zip     = $request->get('zip');
        $reservation->phone     = $request->get('phone');


        $reservation->adults    = $request->get('adults');
        $reservation->children  = $request->get('children');
        $reservation->pets      =  ($request->get('pets')) ? $request->get('pets') : '0';
        $reservation->arrival   = Carbon::parse($request->get('arrival'));
        $reservation->departure = Carbon::parse($request->get('departure'));

        $reservation->special_rate              = $request->special_rate ? 1 : 0;
        $reservation->special_rate_note              = $request->special_rates_notes;
        $reservation->is_non_profit_reservation              = $request->non_profit ? 1 : 0;
        $reservation->is_add_pet_fee              = $request->is_add_pet_fee ? 1 : 0;


        $reservation->total_amount = $request->get('total_amount');
        $reservation->notes        = $request->get('notes');

        $reservation->housekeeper_id = $request->get('housekeeper_id');
        $reservation->status         = nl2zero('status');
        $reservation->edited         = 1;
        /*saving reservations totals in reservation table instead of payments*/
        $reservation->lodging_amount = $request->get('lodging_amount');
        $reservation->total_amount = $request->get('total_amount');
        $reservation->cleaning_fee = $request->get('clearing_fee');
        // $payment->line_items_total = $rates->line_items_total;
        if ($request->has('pet_fee')) {
            $reservation->pet_fee = $request->get('pet_fee');
        }
        $reservation->lodgers_tax = $request->get('lodgers_tax');
        $reservation->sales_tax = $request->get('sales_tax');
        /*end saving reservations totals in reservation table instead of payments*/
        $reservation->update();


        // charge card
        if ($request->get('payment_mode') == 'card') {
            # code...
        }

        // or send check payment detail

        if ($request->get('payment_mode') == 'check') {
            // check payment is 50 or 100

            $message = '';

            // send email

        }


        // if owner send owner email
        if (!isset($input['dont_send_email'])) {

            if (isset($input['owner_id'])) {
                // send email to house keeper
                try {
                    EmailController::sendOwnerEmail($reservation);
                } catch (Exception $e) {
                    Log::info('sendOwnerEmail dont work');
                    Log::info($e);
                }
            } else {
                // to customers
                try {
                    if ($reservation->is_an_owner_reservation != 1) // this is a customer reservation
                    {
                        EmailController::sendCustomerEmail($reservation);
                    }
                } catch (Exception $e) {
                    Log::info('sendCustomerEmail dont work');
                    Log::info($e);
                }
            }

            // send email to house keeper

            try {
                if ($reservation->housekeeper_id != null) {
                    EmailController::sendHouseKeeperEmail($reservation);
                }
            } catch (Exception $e) {
                Log::info('sendHouseKeeperEmail dont work');
                Log::info($e);
            }

            // send email to hot tub
            try {
                // send email to hot tub
                if ($reservation->property->hottub != null) {
                    EmailController::sendHottubEmail($reservation);
                }
            } catch (Exception $e) {
                Log::info('sendHottubEmail dont work');
                Log::info($e);
            }
        } //end if send email 

        // to admin
        try {
            // send email to admin
            EmailController::sendAdminEmail($reservation);
        } catch (Exception $e) {
            Log::info('sendAdminEmail dont work');
            Log::info($e);
        }




        \Session::flash('success', 'Reservaiton Updated.');

        $url = 'admin/reservation/' . $id . '/edit';

        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        Reservation::destroy($id);
        return response()->json('success');
    }

    public function calendar($property_id)
    {
        $property = Property::find($property_id);
        $calendar = Calendar::RenderCalendar($property, 'seprate');
        if ($property == null) {
            dd('no property with that id exists');
        }
        return $calendar;
    }


    public function process_payment(Request $request, $reservation_id)
    {
        $this->validate($request, array(
            'amount_to_pay' => 'numeric|min:0.1',
            'first_name'    => 'required_without:process_saved_card',
            'last_name'     => 'required_without:process_saved_card',
            'address_1'     => 'required_without:process_saved_card',
            'city'          => 'required_without:process_saved_card',
            'state'         => 'required_without:process_saved_card',
            'zip'           => 'required_without:process_saved_card',
            'credit_card'   => 'required_without:process_saved_card',
            'cvv'           => 'required_without:process_saved_card',
        ));

        $amount             = $request->amount_to_pay;
        $process_saved_card = $request->process_saved_card ? 1 : 0;

        $reservation    = Reservation::find($request->reservation_id);
        $request->merge([
            'email' => $reservation->customer->email,
            'address_line_1' => $request->address_1,
            'customer_profile' => $reservation->customer_profile,
            'customer_payment_profile' => $reservation->customer_payment_profile,
        ]);
        $gateway_result = authorized_payment($request, $request, $request->amount_to_pay, $process_saved_card);
        if ($gateway_result['status'] == true) {
            $customer_profile                      = $gateway_result['customer_profile'];
            $customer_payment_profile              = $gateway_result['customer_payment_profile'];
            $payment_card_last_four                = $gateway_result['card_last_four'];
            $payment_transaction_id                = $gateway_result['transaction_id'];
            $reservation->customer_profile         = $customer_profile;
            $reservation->customer_payment_profile = $customer_payment_profile;
            $reservation->customer_card            = $payment_card_last_four;
            $reservation->save();
            try {

                $email_template_file = "payment_process";
                $template            = EmailTemplate::find(13);
                Mail::send(['html' => 'emails.' . $email_template_file], compact('reservation', 'template', 'amount'), function ($message) use ($reservation, $template) {

                    $adminEmails   = config('general')['additional_emails'];
                    $adminEmails[] = config('general')['default_email'];

                    //Log::debug($adminEmails);

                    $message->to($reservation->customer->email, $reservation->customer->first_name)
                        ->subject($template->subject)->bcc($adminEmails);
                });



                /*Mail::send(['html' => 'emails.' . $email_template_file], compact('reservation', 'template', 'amount'), function ($message) use ($reservation, $template) {

                    $adminEmails   = config('general')['additional_emails'];
                    $adminEmails[] = config('general')['default_email'];

                    //Log::debug($adminEmails);

                    $message->to(config('general')['default_email'], $reservation->customer->first_name)
                        ->subject($template->subject)->bcc($adminEmails);

                });*/


                /*$reservation->email_sent = 1;
                $reservation->save();
                $emailView                         = View::make('emails.' . $email_template_file, compact('reservation', 'template', 'amount'));
                $contents                          = (string) $emailView;
                $reservation_email                 = new ReservationEmail;
                $reservation_email->role           = "customer";
                $reservation_email->from           = "-";
                $reservation_email->from_name      = "-";
                $reservation_email->subject        = $template->subject;
                $reservation_email->to             = $reservation->email;
                $reservation_email->reservation_id = $reservation->id;
                $reservation_email->name           = $reservation->full_name();
                $reservation_email->content        = $contents;
                $reservation_email->save();*/
            } catch (\Exception $e) {
                info("error  in sending payment process email " . $e->getMessage());
            }
        } else {
            return response([$gateway_result['message']], 422);
        }

        $resrevation_payment                       = new Payment;
        $resrevation_payment->reservation_id       = $reservation_id;
        $resrevation_payment->amount_deposited               = $request->amount_to_pay;
        $resrevation_payment->payment_mode       = "credit card";
        $resrevation_payment->card_last_four       = $payment_card_last_four;
        $resrevation_payment->transaction_id       = $payment_transaction_id;
        $resrevation_payment->save();
        Session::flash('success', "Payment Processed successfully!");
        //return back();

    }
    public function make_refund(Request $request, $reservation_id)
    {
        $this->validate($request, [
            'payment_id' => 'required',
            'refund_amount' => 'required|numeric',
        ], [
            'payment_id.required' => 'Please select a transaction for refund.',
        ]);
        $reservation = Reservation::find($reservation_id);
        $payment = Payment::find($request->payment_id);
        if (Carbon::parse(Carbon::now())->diffInHours($payment->created_at) < 24) {
            return response(['transaction is not settled'], 422);
        }
        $card = substr($payment->card_last_four, -4);
        $transaction_id =  $payment->transaction_id;
        $response = refundTransaction($transaction_id, $request->refund_amount, $card);
        info("refund response: " . print_r($response, 1));
        if ($response['message'] != 'Successful.') {
            return response([$response['message']], 422);
        } else {
            $refund = new Refund();
            $refund->amount = $request->refund_amount;
            $refund->refund_id = $payment->transaction_id;
            $refund->payment_id = $payment->id;
            $refund->reservation_id = $reservation->id;
            $refund->type = strtoupper('refund');
            $refund->save();
            try {

                $email_template_file = "refund_payment";
                Mail::send(['html' => 'emails.' . $email_template_file], compact('reservation', 'refund'), function ($message) use ($reservation) {
                    $message->to($reservation->customer->email, $reservation->customer->first_name)
                        ->subject("Refund Issued");
                });
                $html_email = view('emails.' . $email_template_file, ['reservation' => $reservation, 'refund' => $refund])->render();
                $sendemails = new sentemail();
                $sendemails->sentto     = 'customer';
                $sendemails->gemail     = $reservation->customer->email;
                $sendemails->guest      = $reservation->customer->first_name . ' ' . $reservation->customer->last_name;
                $sendemails->subject    = "Refund Issued";
                $sendemails->status     = 'sent';
                $sendemails->attahment  = '';
                $sendemails->body       = $html_email;
                $sendemails->eemail     = json_encode($reservation->customer->email);
                $sendemails->reservation_id = $reservation->id;
                $sendemails->save();
            } catch (\Exception $e) {
                info("error  in sending refund email " . $e->getMessage());
            }
        }
        $request->session()->flash('success', 'Successfully Refund');
        // $this->email_service->sendRefundEmail($reservation, $refund->amount);
        return "done";
    }

    public function void_payment(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        $request->merge([
            'payment_id' => $request->payment_id_for_void,
        ]);
        $this->validate($request, [
            'payment_id' => 'required',
        ], [
            'payment_id.required' => 'Please select a transaction for void.',
        ]);
        $payment = Payment::find($request->payment_id);
        $response = authorizedVoidTransaction($payment->transaction_id);
        if ($response['message'] != 'SUCCESS') {
            return response([$response->message], 422);
        } else {
            $refund = new Refund();
            $refund->amount = $payment->amount_deposited;
            $refund->refund_id = $payment->transaction_id;
            $refund->payment_id = $payment->id;
            $refund->reservation_id = $reservation->id;
            $refund->type = strtoupper('void');
            $refund->save();
            try {

                $email_template_file = "refund_payment";
                Mail::send(['html' => 'emails.' . $email_template_file], compact('reservation', 'refund'), function ($message) use ($reservation) {
                    $message->to($reservation->customer->email, $reservation->customer->first_name)
                        ->subject("Refund Issued");
                });
                $html_email = view('emails.' . $email_template_file, ['reservation' => $reservation, 'refund' => $refund])->render();
                $sendemails = new sentemail();
                $sendemails->sentto     = 'customer';
                $sendemails->gemail     = $reservation->customer->email;
                $sendemails->guest      = $reservation->customer->first_name . ' ' . $reservation->customer->last_name;
                $sendemails->subject    = "Refund Issued";
                $sendemails->status     = 'sent';
                $sendemails->attahment  = '';
                $sendemails->body       = $html_email;
                $sendemails->eemail     = json_encode($reservation->customer->email);
                $sendemails->reservation_id = $reservation->id;
                $sendemails->save();
            } catch (\Exception $e) {
                info("error  in sending refund email " . $e->getMessage());
            }
        }
        Session::flash('success', "Transaction VOIDED successful");
        return 'done';
    }
    public function archeived()
    {
        try{
            $t = date_default_timezone_set("America/Denver");
            $t =  date("Y-m-d");
            $keyword = @$_GET['q'];
            $properties  = Property::all();
            $reservations = Reservation::select('reservations.*')->where('departure', '<', $t)
                ->join('users', 'reservations.customer_id', '=', 'users.id')
                ->where('cancelled', 0)
                ->where(function ($query) use ($keyword) {
                    $query->where('users.name', 'LIKE', "%$keyword%");
                })
                ->orderBy('arrival', 'desc')
                ->paginate(config('pagination.per_page') ?? 10);
                
            return view('admin.reservation.archeived')->with('reservations', $reservations)->with('properties', $properties);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back();
        }
    }
    public function reservation_cancel($id)
    {

        //$request->send_cancel_email?"send":"not send";
        $reservation = Reservation::find($id);
        $reservation->cancelled = 1;
        $reservation->save();
        // if owner send owner email
        if ($reservation->is_an_owner_reservation) {
            // send email to house keeper
            try {


                if (config('site.admin_reservation_owner')) {
                    info('sending admin reservation email to owner');
                    return EmailController::sendOwnerCancelledEmail($reservation);
                } else {
                    info('sending admin reservation email to owner TURNED OFF');
                }

                // from here ..
            } catch (Exception $e) {
                Log::info('sendOwnerCancelledEmail dont work');
                Log::info($e);
            }
        } else {
            try {
                if ($reservation->is_an_owner_reservation != 1) // this is a customer reservation
                {

                    if (config('site.admin_reservation_customer')) {
                        info('sending admin reservation email to customer');
                        EmailController::sendCustomerCancelledEmail($reservation);
                    }
                    // from here ..
                }
            } catch (Exception $e) {
                Log::info('sendCustomerEmail dont work');
                Log::info($e);
            }
        }

        // send email to house keeper
        try {
            if ($reservation->housekeeper_id != null) {
                if (config('site.admin_reservation_housekeeper')) {
                    info('sending admin reservation email to Housekeeper');
                    EmailController::sendHouseKeeperCancelledEmail($reservation);
                }
                // from here ..
            }
        } catch (Exception $e) {
            Log::info('sendHouseKeeperCancelledEmail dont work');
            Log::info($e);
        }
        // send email to hot tub
        try {
            // send email to hot tub
            if ($reservation->property->hottub != null) {
                EmailController::sendHottubEmail($reservation);
                // from here ..
            }
        } catch (Exception $e) {
            Log::info('sendHottubEmail dont work');
            Log::info($e);
        }


        // to admin
        try {
            // send email to admin
            if (config('site.admin_reservation_admin')) {
                info('sending admin reservation email to admin');
                EmailController::sendAdminCancelledEmail($reservation);
            }
            // from here ..
        } catch (Exception $e) {
            Log::info('sendAdminEmail dont work');
            Log::info($e);
        }
        Session::flash('success', 'Reservation Cancel successfully.');
        return redirect()->back();
    }
}
