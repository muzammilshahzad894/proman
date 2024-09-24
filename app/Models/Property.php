<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Log;
use \App\Models\Reservation;
use \App\Models\Base;

use App\Http\Controllers\Traits\SeasonRateCalculator as SeasonRateCalculator;

class Property extends Base
{

    use SeasonRateCalculator;

    // public function getCategoryIdAttribute($value)
    // {
    // 	if($value==1)
    // 		return "Home";
    // 	else
    // 		return "Cabin";
    // }

    /*public function getIsVacationAttribute($value)
	{
		if($value==1)
			return "Vacation";
		else
			return "";
	}

	public function getIsLongTermAttribute($value)
	{
		if($value==1)
			return "Longterm";
		else
			return "";
	}*/

    public function seasons()
    {
        return $this->belongsToMany('App\Models\Season', 'seasons_rates', 'property_id', 'season_id')
            ->withPivot('daily_rate', 'weekly_rate', 'monthly_rate', 'deposit')
            ->withTimestamps();
    }

    public function amenities()
    {

        return $this->belongsToMany('\App\Models\Amenity', 'property_amenities', 'property_id', 'amenity_id')
            ->withPivot('value')
            ->withTimestamps();
    }


    public function property_owner()
    {
        return $this->belongsTo('\App\Models\Owner', 'owner', 'id');
    }


    public function pictures()
    {
        return $this->hasMany('\App\Models\Attachment', 'property_id', 'id');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function sleep()
    {
        return $this->belongsTo('\App\Models\Sleep', 'sleep_id', 'id');
    }

    public function bedroom()
    {
        return $this->belongsTo(Bedroom::class);
    }

    public function bathroom()
    {
        return $this->belongsTo(Bathroom::class);
    }


    public function mainpictures()
    {
        $results = $this->hasMany('\App\Models\Attachment', 'property_id', 'id')->where('main', '=', 1);

        if ($results->count() > 0) {

            return $results;
        } else {

            return $this->hasMany('\App\Models\Attachment', 'property_id', 'id');
        }
    }

    public function reservations()
    {
        return $this->hasMany('\App\Models\Reservation', 'property_id', 'id');
    }

    public function types()
    {
        return $this->belongsTo('\App\Models\Type', 'category_id', 'id');
    }




    public function isAvailable($from, $to)
    {
        return $this->reservations()
            ->where(function ($query) use ($from) {
                $query->where('arrival', '<=', $from)
                    ->where('departure', '>=', $from);
            })
            ->orWhere(function ($query) use ($to) {
                $query->where('arrival', '<=', $to)
                    ->where('departure', '>=', $to);
            })
            ->count() < 1;
    }


    public function isPropertyAvailable($from, $to, $edit_reservation = 0)
    {
        $from_date = $from;
        $to_date = $to;

        $property_id = $this->id;

        $reserved_arr = true;
        $reserved_dep = true;

        //$from_date = strtotime($from_date. ' +1 day');
        $from_date = strtotime($from_date);
        $from_date = date('Y-m-d', $from_date);

        $to_date = strtotime($to_date);
        $to_date = date('Y-m-d', $to_date);

        // $sql = "property_id = $property_id AND ( arrival >= '$from_date' OR arrival <= '$to_date' ) AND ( departure >= '$from_date' AND departure <= '$to_date' )";

        // arrival date of new reservaiton could not be equal to exsisting reservaiton

        $sql_arrivar = "property_id = $property_id";
        info('skip reservation: ' . $edit_reservation);
        if ($edit_reservation > 0) {

            $reservation_arrivar = Reservation::whereNot('id', $edit_reservation)->whereRaw($sql_arrivar)->where('cancelled', 0);
        } else {
            $reservation_arrivar = Reservation::whereRaw($sql_arrivar)->where('cancelled', 0)
                ->get();
        }
        foreach ($reservation_arrivar as $reservation_arriva) {

            $arrival = \Carbon\Carbon::createFromTimestamp(strtotime($reservation_arriva->arrival));
            $departure = \Carbon\Carbon::createFromTimestamp(strtotime($reservation_arriva->departure));
            $from_date = \Carbon\Carbon::createFromTimestamp(strtotime($from_date));
            $to_date = \Carbon\Carbon::createFromTimestamp(strtotime($to_date));

            // Log::info('start =====================================');
            // Log::info( $arrival );
            // Log::info( $arrival->gte($from_date) );

            // Log::info( $departure );

            // Log::info( $from_date->lt($departure) );
            // Log::info( $from_date );

            /*Before fix of book on arrival date*/
            /*
            if ( $arrival->eq($from_date) ||  $from_date->gt($arrival)  && $from_date->lt($departure)  ) {

                 $reserved_arr = false ;

            }


            // equil to departure date and between arrival and departure , greater than arrival
            if ( $departure->eq($to_date) ||  $to_date->gt($arrival)  && $to_date->lt($departure)  ) {

                 $reserved_arr = false ;

            }
            */
            /*Before fix of book on arrival date END*/

            // equil to arrival date and between arrival and departure , less than departure
            if ($arrival->eq($from_date) ||  $from_date->gte($arrival)  && $from_date->lte($departure)) {

                $reserved_arr = false;
            }


            // equil to departure date and between arrival and departure , greater than arrival
            if ($departure->eq($to_date) ||  $to_date->gte($arrival)  && $to_date->lte($departure)) {

                $reserved_arr = false;
            }

            // less to departure date and departure greater than to date
            if ($from_date->lte($arrival)  && $to_date->gte($departure)) {
                $reserved_arr = false;
            }


            // Log::info( $reserved_arr );
            // Log::info('edn =====================================');

        }


        return  $reserved_arr;



        // if($reservation_arrivar->count() > 0) {

        //     Log::info( $reservation_arrivar );

        //     $reserved_arr = false ;

        // } else {

        //      Log::info( 'no reservation is there can reserve' );

        //      $reserved_arr = true ;
        // }

        // return  $reserved_arr;

        // if($reservation_departure->count() > 0) {

        //     Log::info( 'reservation exist can not reserve this property' );

        //     $reserved_dep = false ;

        // } else {

        //      Log::info( 'no reservation is there can reserve' );

        //      $reserved_dep = true ;
        // }

        // return  $reserved_dep;

        // if ($reserved_arr && $reserved_dep ) {

        // 	return false;

        // } else {

        // 	return true;
        // }




    }

    public function propertyCheck($from, $to, $edit_reservation = 0)
    {
        $from_date = $from;
        $to_date = $to;
        $property_id = $this->id;
        $reserved_arr = true;
        $reserved_dep = true;
        $from_date = strtotime($from_date);
        $from_date = date('Y-m-d', $from_date);
        $to_date = strtotime($to_date);
        $to_date = date('Y-m-d', $to_date);

        // arrival date of new reservaiton could not be equal to exsisting reservaiton
        $sql_arrivar = "property_id = $property_id";
        info('skip reservation: ' . $edit_reservation);
        if ($edit_reservation > 0) {

            $reservation_arrivar = Reservation::whereNot('id', $edit_reservation)->where('cancelled', 0)->whereRaw($sql_arrivar);
        } else {
            $reservation_arrivar = Reservation::whereRaw($sql_arrivar)->where('cancelled', 0)
                ->get();
        }

        foreach ($reservation_arrivar as $reservation_arriva) {

            $arrival = \Carbon\Carbon::createFromTimestamp(strtotime($reservation_arriva->arrival));
            $departure = \Carbon\Carbon::createFromTimestamp(strtotime($reservation_arriva->departure));
            $from_date = \Carbon\Carbon::createFromTimestamp(strtotime($from_date));
            $to_date = \Carbon\Carbon::createFromTimestamp(strtotime($to_date));


            /*Before fix of book on arrival date END*/
            // equil to arrival date and between arrival and departure , less than departure
            if ($from_date == $arrival  && $from_date < $departure) {
                $reserved_arr = false;
            }


            // equil to departure date and between arrival and departure , greater than arrival
            if ($to_date > $arrival  && $to_date < $departure) {
                $reserved_arr = false;
            }
        }

        return  $reserved_arr;
    }

    // this is not using in ajax
    public function getPropertyRate($from_date, $to_date)
    {

        //$season = getCurrentSeason($from_date, $to_date);


        $request = new \Illuminate\Http\Request();

        $request->request->add(
            [
                'from_date' => $from_date,
                'to_date' => $to_date,
                'property_id' => $this->id
            ]
        );


        $number_of_days = numberOfDays($from_date,  $to_date);

        $getRate  =   $this->getRate($request);

        $rate =  $getRate['rate'];

        $lodging_amount =  $getRate['daily_rate'];
        $season =  $getRate['season'];

        $response = [
            'status' => 'success',
            'rate' => $rate,
            'season' => $season,
            'lodging_amount' => number_format($lodging_amount, 2)
        ];

        return (object) $response;
    }

    public function calculateBookingPrice($from, $to, $pets = 0)
    {
        $from_date = $from;
        $to_date = $to;
        $property_id = $this->id;

        $getpropertyrate = $this->getPropertyRate($from, $to);

        $season = $getpropertyrate->season;

        $number_of_days = numberOfDays($from_date,  $to_date);
        $days_in_arrival = numberOfDays(\Carbon\Carbon::now(),  $to_date);

        $lodging_amount_base = $getpropertyrate->rate;

        $lodging_amount = $getpropertyrate->rate;
        $lodging_amount_static = $lodging_amount;
        // add pet fee

        $pet_fee = 0;

        if ($this->pet_fee_active == 1 && $pets > 0) {
            $pet_fee = $this->pet_fee;
        }

        $lodging_amount = $lodging_amount + $pet_fee;

        // add cleaning fee

        $cleaing_fee = 0;

        if ($this->clearing_fee_active == 1) {
            $cleaing_fee = $this->clearing_fee;
        }

        $lodging_amount = $lodging_amount + $cleaing_fee;

        // add cleaning fee

        $lodgers_tax = 0;

        if ($this->lodger_tax_active == 1 && $this->lodger_tax != 0) {
            $lodgers_tax = $this->lodger_tax * $lodging_amount_base / 100;
        }

        $lodging_amount = $lodging_amount + $lodgers_tax;


        // add sales tax
        $sales_tax = 0;

        if ($this->sales_tax_active == 1 && $this->sales_tax != 0) {
            $sales_tax = $this->sales_tax * $lodging_amount_base / 100;;
        }



        $lodging_amount = $lodging_amount + $sales_tax;


        $line_items = \App\Models\LineItem::all();
        $line_items_total = 0;
        if ($line_items->count() > 0) {

            foreach ($line_items as $line_item) {

                $line_item_amount =  $line_item->lineItemAmount($lodging_amount_base);

                $line_items_total = $line_items_total + $line_item_amount;
            }
        }


        $lodging_amount = $lodging_amount + $line_items_total;
        $lodging_amount = $lodging_amount;

        $payable_today  = $lodging_amount;

        $half_payment = false;


        if ($season) {

            if ($days_in_arrival > $season->balance_payment_days) {
                $half_payment = true;
                $payable_today  = $lodging_amount / 2;
            }
        }


        $response = [

            'lodging_amount_base' => $lodging_amount_base,
            'line_items_total' => $line_items_total,
            'pet_fee' => $pet_fee,
            'cleaing_fee' => $cleaing_fee,
            'lodgers_tax' => $lodgers_tax,
            'sales_tax' => $sales_tax,
            'number_of_days' => $number_of_days,
            'days_in_arrival' => $days_in_arrival,
            'total' => $lodging_amount,
            'lodging_amount' => $lodging_amount_static,
            'payable_today' => $payable_today,
            'half_payment' => true,
        ];

        return (object) $response;
    }


    public function hottub()
    {
        return $this->belongsTo('App\Models\HotTub');
    }
}
