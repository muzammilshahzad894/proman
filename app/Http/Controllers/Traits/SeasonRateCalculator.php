<?php

namespace App\Http\Controllers\Traits;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Log;
use Session;
use Cache;
use Exception;
use App\Http\Requests\AddSeasonRateRequest;
use App\Http\Requests\EditSeasonRateRequest;
use App\Models\Season;
use App\Models\Property;
use App\Models\Reservation;
use Carbon\Carbon;

trait SeasonRateCalculator
{

    public function getRate(Request $request)
    {
        info('--------------------------------------------------');
        info('-----------------Getting Rates----------------');
        info('--------------------------------------------------');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        $property = Property::find($request->get('property_id'));

        $status = $property->propertyCheck($from_date, $to_date, $request->edit_reservation);

        if ($status == false) {

            return false;
        }

        $from_date_ = Carbon::parse($from_date);

        $to_date_ = Carbon::parse($to_date);

        $number_of_days = numberOfDays($from_date,  $to_date);

        Log::info('numberof days  ' . $number_of_days);

        $from_year = $from_date_->format('y');
        $to_year = $to_date_->format('y');

        $dates = [];

        $date = $from_date_;
        for ($date; $date->lte($to_date_); $date->addDay()) {

            $dates[] = $date->format('Y-m-d');
        }

        info("from_date_: $from_date_ ------------- to_date_: $to_date_");

        $seasons = Season::all();

        $totallodging = 0;
        $daily_rate = 0;
        $season_count = 0;

        $lastSeason =  null;
        foreach ($seasons as $season) {
            if ($season != null) {
                if ($season->minimum_stay == '' && $number_of_days < $season->minimum_stay) {
                    $response = [
                        'status' => 'error',
                        'error' => 'Should be  ' . $season->minimum_stay . ' minimum nights in this season.',
                    ];
                    return response()->json($response, 200);
                }
            }

            $seasonStartDate = $from_year . '-' . $season->from_month . '-' . $season->from_day;
            $seasonStartDate = Carbon::parse($seasonStartDate);

            $seasonEndDate = $from_year . '-' . $season->to_month . '-' . $season->to_day;
            $seasonEndDate = Carbon::parse($seasonEndDate);

            if ($season->from_month > $season->to_month) {
                $seasonEndDate = $from_year + 1 . '-' . $season->to_month . '-' . $season->to_day;
                $seasonEndDate = Carbon::parse($seasonEndDate);
            }
            // number of days matching in this season 
            $count = 0;
            info("Season ID $season->id. seasonStartDate: $seasonStartDate --- seasonEndDate: $seasonEndDate");
            // count days match for this season in reservation 
            for ($date = $seasonStartDate; $date->lte($seasonEndDate); $date->addDay()) {
                foreach ($dates as $booking_date) {
                    $booking_date = Carbon::parse($booking_date);
                    if ($date->format('m-d') == $booking_date->format('m-d')) {
                        $count = $count + 1;
                    }
                }
            }

            // if there are days mathcing in this season get rate and multiple 
            if ($count >= 1) {
                $season_count++;
                // minus one day for correct calculation
                if ($season_count == 1) {
                    $count = $count - 1;
                }
                $lastSeason =  $season;
                $daily_rate =  seasonRate($property, $season->id, $count);

                $totallodging +=  $count *  $daily_rate;
            }
        }

        $totallodging = number_format_without_comma($totallodging, 2);
        $data = [

            'rate' => $totallodging,
            'daily_rate' => $daily_rate,
            'season' => $lastSeason,
            'status' => $status,
        ];
        
        return $data;
    }
}
