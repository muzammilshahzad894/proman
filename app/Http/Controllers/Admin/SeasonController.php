<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Log;
use Session;
use App\Http\Requests\AddSeasonRateRequest;
use App\Http\Requests\EditSeasonRateRequest;
use App\Models\Season;
use App\Models\Property;
use App\Models\Reservation;
use Carbon\Carbon; 
use App\Http\Controllers\Traits\SeasonRateCalculator as SeasonRateCalculator ;

class SeasonController extends Controller
{
    use SeasonRateCalculator; 

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $season_rates = Season::orderby('display_order')->get();
            return view('admin.season_rate.index')->with('season_rates', $season_rates);
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
    public function create()
    {
        return view('admin.season_rate.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddSeasonRateRequest $request)
    {
        try {
            $season_rate = new Season();
            $season_rate->title = $request->get('title');
            $season_rate->from_month = $request->get('from_month');
            $season_rate->from_day = $request->get('from_day');
            $season_rate->to_month = $request->get('to_month');
            $season_rate->to_day = $request->get('to_day');
            $season_rate->type = $request->get('type');
            $season_rate->show_on_frontend = $request->get('show_on_frontend') ?? 0;
            $season_rate->allow_weekly_rates = $request->get('allow_weekly_rates') ?? 0;
            $season_rate->allow_monthly_rates = $request->get('allow_monthly_rates') ?? 0;
            $season_rate->display_order = $request->get('display_order') ?? 0;
            $season_rate->balance_payment_days = $request->get('balance_payment_days') ?? 0;
            $season_rate->minimum_nights = $request->get('minimum_nights') ?? 0;
            $season_rate->save();

            return ResponseHelper::jsonResponse('success', 'Rate added successfully.', route('admin.seasonrate.index'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $season_rate = Season::findOrFail($id);
            return view('admin.season_rate.edit')->with('season_rate', $season_rate);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddSeasonRateRequest $request, $id)
    {
        try {
            $season_rate = Season::findOrFail($id);
            $season_rate->title = $request->get('title');
            $season_rate->from_month = $request->get('from_month');
            $season_rate->from_day = $request->get('from_day');
            $season_rate->to_month = $request->get('to_month');
            $season_rate->to_day = $request->get('to_day');
            $season_rate->type = $request->get('type');
            $season_rate->show_on_frontend = $request->get('show_on_frontend') ?? 0;
            $season_rate->allow_weekly_rates = $request->get('allow_weekly_rates') ?? 0;
            $season_rate->display_order = $request->get('display_order') ?? 0;
            $season_rate->balance_payment_days = $request->get('balance_payment_days') ?? 0;
            $season_rate->minimum_nights = $request->get('minimum_nights') ?? 0;
            $season_rate->save();

            return ResponseHelper::jsonResponse('success', 'Season Rate updated successfully.', route('admin.seasonrate.index'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Season::destroy($id);
            return response()->json('success');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseHelper::jsonResponse('error', 'Something went wrong. Please try again.', null, 500);
        }
    }

    public function getDailyRate( Request $request )
    {
        $getRate =   $this->getRate( $request );
        if ($getRate['status'] == false) {
            $response = [
                'status' => 'error',
                'error' => 'This Property is not available for selected dates.',
            ];

            return response()->json($response,200);
        }

        return $getRate['rate'] ; 
    }
}
