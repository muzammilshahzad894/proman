<?php

namespace App\helpers;

use App\User;
use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\Property;
use App\Models\Reservation;
use App\Models\Housekeeper;

class Calendar
{
    public static function makeCalendar(Property $property, $location)
    {
        $reservation = Reservation::where('property_id', '=', $property->id)->where('cancelled', 0)->get();
        $reservation_dates = [];
        $pending_dates = [];

        $reservation_arrival_dates = Reservation::select('arrival')
            ->where('property_id', '=', $property->id)
            ->where('is_an_owner_reservation', '=', 0)
            ->where('cancelled', 0)
            ->pluck('arrival');

        $reservation_departure_dates = Reservation::select('departure')
            ->where('property_id', '=', $property->id)
            ->where('is_an_owner_reservation', '=', 0)
            ->where('cancelled', 0)
            ->pluck('departure');



        // convert timestamp to date
        $reservation_arrival_dates   = self::convertToDate($reservation_arrival_dates);
        $reservation_departure_dates = self::convertToDate($reservation_departure_dates);

        // get an array of all reservation dates
        foreach ($reservation as $res) {
            $begin     = new DateTime($res->arrival);
            $end       = new DateTime($res->departure . ' +1 day'); // so that we can get end date in array
            $interval  = new DateInterval('P1D'); // 1 Day
            $dateRange = new DatePeriod($begin, $interval, $end);

            foreach ($dateRange as $date) {
                array_push($reservation_dates, $date->format("Y-m-d"));
            }

            if ($res->status == 0) {
                foreach ($dateRange as $date) {
                    array_push($pending_dates, $date->format("Y-m-d"));
                }
            }
        }

        $reservation_dates       = array_unique($reservation_dates); // remove duplicates

        return view($location)
            ->with('property', $property)
            ->with('reservation', $reservation)
            ->with('pending_dates', $pending_dates)
            ->with('reservation_dates', $reservation_dates)
            ->with('reservation_arrival_dates', $reservation_arrival_dates)
            ->with('reservation_departure_dates', $reservation_departure_dates);
    }

    private static function convertToDate($timestampArray)
    {
        $dates = [];
        for ($i = 0; $i < sizeof($timestampArray); $i++) {
            array_push($dates, date("Y-m-d", strtotime($timestampArray[$i])));
        }

        return $dates;
    }

    public static function createReservationCalendar(Property $property, $location)
    {
        $house_keepers = Housekeeper::all();
        $customers = User::whereType('customer')->get();
        $reservation = Reservation::where('property_id', '=', $property->id)->where('cancelled', 0)->get();
        $reservation_dates = [];
        $pending_dates = [];

        $reservation_arrival_dates = Reservation::select('arrival')
            ->where('property_id', '=', $property->id)
            ->where('is_an_owner_reservation', '=', 0)
            ->where('cancelled', 0)
            ->pluck('arrival');

        $reservation_departure_dates = Reservation::select('departure')
            ->where('property_id', '=', $property->id)
            ->where('is_an_owner_reservation', '=', 0)
            ->where('cancelled', 0)
            ->pluck('departure');

        // convert timestamp to date
        $reservation_arrival_dates   = self::convertToDate($reservation_arrival_dates);
        $reservation_departure_dates = self::convertToDate($reservation_departure_dates);

        // get an array of all reservation dates
        foreach ($reservation as $res) {
            $begin     = new DateTime($res->arrival);
            $end       = new DateTime($res->departure . ' +1 day'); // so that we can get end date in array
            $interval  = new DateInterval('P1D'); // 1 Day
            $dateRange = new DatePeriod($begin, $interval, $end);

            foreach ($dateRange as $date) {
                array_push($reservation_dates, $date->format("Y-m-d"));
            }

            if ($res->status == 0) {
                foreach ($dateRange as $date) {
                    array_push($pending_dates, $date->format("Y-m-d"));
                }
            }
        }

        $reservation_dates       = array_unique($reservation_dates); // remove duplicates

        return view($location)
            ->with('property', $property)
            ->with('customers', $customers)
            ->with('house_keepers', $house_keepers)
            ->with('reservation', $reservation)
            ->with('pending_dates', $pending_dates)
            ->with('reservation_dates', $reservation_dates)
            ->with('reservation_arrival_dates', $reservation_arrival_dates)
            ->with('reservation_departure_dates', $reservation_departure_dates);
    }

    public static function editReservationCalendar(Property $property, $location, Reservation $reservation)
    {
        $house_keepers = Housekeeper::all();
        $customers = User::whereType('customer')->get();
        $reservations = Reservation::where('property_id', '=', $property->id)->where('cancelled', 0)->get();
        $reservation_dates = [];
        $pending_dates = [];

        $reservation_arrival_dates = Reservation::select('arrival')
            ->where('property_id', '=', $property->id)
            ->where('is_an_owner_reservation', '=', 0)
            ->where('cancelled', 0)
            ->pluck('arrival');

        $reservation_departure_dates = Reservation::select('departure')
            ->where('property_id', '=', $property->id)
            ->where('is_an_owner_reservation', '=', 0)
            ->where('cancelled', 0)
            ->pluck('departure');

        // convert timestamp to date
        $reservation_arrival_dates   = self::convertToDate($reservation_arrival_dates);
        $reservation_departure_dates = self::convertToDate($reservation_departure_dates);

        // get an array of all reservation dates
        foreach ($reservations as $res) {
            $begin     = new DateTime($res->arrival);
            $end       = new DateTime($res->departure . ' +1 day'); // so that we can get end date in array
            $interval  = new DateInterval('P1D'); // 1 Day
            $dateRange = new DatePeriod($begin, $interval, $end);

            foreach ($dateRange as $date) {
                array_push($reservation_dates, $date->format("Y-m-d"));
            }

            if ($res->status == 0) {
                foreach ($dateRange as $date) {
                    array_push($pending_dates, $date->format("Y-m-d"));
                }
            }
        }

        $reservation_dates = array_unique($reservation_dates); // remove duplicates

        return view($location)
            ->with('property', $property)
            ->with('customers', $customers)
            ->with('house_keepers', $house_keepers)
            ->with('reservation', $reservation)
            ->with('reservations', $reservations)
            ->with('pending_dates', $pending_dates)
            ->with('reservation_dates', $reservation_dates)
            ->with('reservation_arrival_dates', $reservation_arrival_dates)
            ->with('reservation_departure_dates', $reservation_departure_dates);
    }


    public static function RenderCalendar(Property $property, $location = '')
    {
        $reservation = Reservation::where('property_id', '=', $property->id)->where('cancelled', 0)->get();
        $reservation_dates = [];
        $pending_dates = [];

        $reservation_arrival_dates = Reservation::select('arrival')
            ->where('property_id', '=', $property->id)
            ->where('cancelled', 0)
            ->where('is_an_owner_reservation', '=', 0)
            ->pluck('arrival');

        $reservation_departure_dates = Reservation::select('departure')
            ->where('property_id', '=', $property->id)
            ->where('is_an_owner_reservation', '=', 0)
            ->where('cancelled', 0)
            ->pluck('departure');

        // convert timestamp to date
        $reservation_arrival_dates = self::convertToDate($reservation_arrival_dates);
        $reservation_departure_dates = self::convertToDate($reservation_departure_dates);

        // get an array of all reservation dates
        foreach ($reservation as $res) {
            $begin = new DateTime($res->arrival);
            $end = new DateTime($res->departure . ' +1 day'); // so that we can get end date in array
            $interval = new DateInterval('P1D'); // 1 Day
            $dateRange = new DatePeriod($begin, $interval, $end);

            foreach ($dateRange as $date) {
                array_push($reservation_dates, $date->format("Y-m-d"));
            }

            if ($res->status == 0) {
                foreach ($dateRange as $date) {
                    array_push($pending_dates, $date->format("Y-m-d"));
                }
            }
        }

        $reservation_dates = array_unique($reservation_dates);

        if ($location == 'seprate') {
            $view = 'seprate';
        } else {
            $view = 'view';
        }

        $calendarVeiw = view('calendar.' . $view)
            ->with('property', $property)
            ->with('reservation', $reservation)
            ->with('pending_dates', $pending_dates)
            ->with('reservation_dates', $reservation_dates)
            ->with('reservation_arrival_dates', $reservation_arrival_dates)
            ->with('reservation_departure_dates', $reservation_departure_dates);

        return $calendarVeiw;
    }
}
