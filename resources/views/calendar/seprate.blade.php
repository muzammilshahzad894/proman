@extends('layouts.calendar')

@section('content')

<?php
// Set your timezone!!
//date_default_timezone_set('Asia/Karachi');

// Get prev & next month
if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
} else {
    // This month
    $ym = date('Y-m');
}



// Check format
$timestamp = strtotime($ym . '-01');
if ($timestamp === false) {
    $timestamp = time();
}

// Today
$today = date('Y-m-j', time());

// For H3 title
$html_title = date('M Y', $timestamp);
$html_title1 = date('M Y', strtotime("+1 month", $timestamp));
$html_title2 = date('M Y', strtotime("+2 month", $timestamp));
$html_title3 = date('M Y', strtotime("+3 month", $timestamp));

// Create prev & next month link     mktime(hour,minute,second,month,day,year)
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) - 1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) + 1, 1, date('Y', $timestamp)));

// Number of days in the month
$day_count = date('t', $timestamp);
$day_count1 = date('t', strtotime("+1 month", $timestamp));
$day_count2 = date('t', strtotime("+2 month", $timestamp));
$day_count3 = date('t', strtotime("+3 month", $timestamp));

// place all the variable that are coming from DB
$reservation = $reservation;
$pending_dates = $pending_dates;
$reservation_dates = $reservation_dates;
$reservation_arrival_dates   = $reservation_arrival_dates;
$reservation_departure_dates = $reservation_departure_dates;

// dd($reservation_departure_dates);

///////////////////////////// 1st Calendar /////////////////////////////////////

// 0:Sun 1:Mon 2:Tue ...
$str = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));

// Create Calendar!!
$weeks = array();
$week = '';

// Add empty cell
$week .= str_repeat('<td></td>', $str);

for ($day = 1; $day <= $day_count; $day++, $str++) {

    $date = $ym . '-' . sprintf("%02d", $day);

    if (in_array($date, $pending_dates)) {
        $week .= '<td class="pending"><span>' . $day;
    } elseif (in_array($date, $reservation_dates)) {
        if (in_array($date, $reservation_arrival_dates)) {
            if (in_array($date, $reservation_departure_dates)) {
                $week .= '<td class="reserve_arrival reserve_departure"><span>' . $day;
            } else {
                $week .= '<td class="reserve_arrival asdf"><span>' . $day;
            }
        } elseif (in_array($date, $reservation_departure_dates)) {
            $week .= '<td class="reserve_departure"><span>' . $day;
        } else {
            $week .= '<td class="reserve"><span>' . $day;
        }
    } else {
        $week .= '</span><td>' . $day;
    }
    $week .= '</span></td>';

    // End of the week OR End of the month
    if ($str % 7 == 6 || $day == $day_count) {
        if ($day == $day_count) {
            // Add empty cell
            $week .= str_repeat('<td></td>', 6 - ($str % 7));
        }
        $weeks[] = '<tr>' . $week . '</tr>';
        // Prepare for new week
        $week = '';
    }
}


///////////////////////////// 2nd Calendar /////////////////////////////////////

// 0:Sun 1:Mon 2:Tue ...
//$str1 = date('w', mktime(0, 0, 0, date('m', strtotime("+1 month", $timestamp)), 1, date('Y', $timestamp)));
$str1 = date('w', mktime(0, 0, 0, date('m', strtotime("+1 month", $timestamp)), 1, date('Y', strtotime("+1 month", $timestamp))));


// Create Calendar!!
$weeks1 = array();
$week1 = '';

// Add empty cell
$week1 .= str_repeat('<td></td>', $str1);

for ($day = 1; $day <= $day_count1; $day++, $str1++) {
    //$date = $ym.'-'.sprintf("%02d", $day);
    $date = sprintf("%02d", $day) . " $html_title1";

    $date = new DateTime($date);
    //$date->add(new DateInterval('P1M')); // add 1month
    $date = $date->format('Y-m-d');

    if (in_array($date, $pending_dates)) {
        $week1 .= '<td class="pending">' . $day;
    } elseif (in_array($date, $reservation_dates)) {
        if (in_array($date, $reservation_arrival_dates)) {

            if (in_array($date, $reservation_departure_dates)) {
                $week1 .= '<td class="reserve_arrival reserve_departure"><span>' . $day;
            } else {
                $week1 .= '<td class="reserve_arrival asdf"><span>' . $day;
            }
        } elseif (in_array($date, $reservation_departure_dates)) {
            $week1 .= '<td class="reserve_departure"><span>' . $day;
        } else {
            $week1 .= '<td class="reserve"><span>' . $day;
        }
    } else {
        $week1 .= '<td>' . $day;
    }
    $week1 .= '</td>';

    // End of the week OR End of the month
    if ($str1 % 7 == 6 || $day == $day_count1) {
        if ($day == $day_count1) {
            // Add empty cell
            $week1 .= str_repeat('<td></td>', 6 - ($str1 % 7));
        }
        $weeks1[] = '<tr>' . $week1 . '</tr>';
        // Prepare for new week
        $week1 = '';
    }
}


///////////////////////////// 3rd Calendar /////////////////////////////////////

// 0:Sun 1:Mon 2:Tue ...
//$str2 = date('w', mktime(0, 0, 0, date('m', strtotime("+2 month", $timestamp)), 1, date('Y', $timestamp)));
$str2 = date('w', mktime(0, 0, 0, date('m', strtotime("+2 month", $timestamp)), 1, date('Y', strtotime("+2 month", $timestamp))));


// Create Calendar!!
$weeks2 = array();
$week2 = '';

// Add empty cell
$week2 .= str_repeat('<td></td>', $str2);

for ($day = 1; $day <= $day_count2; $day++, $str2++) {
    //$date = $ym.'-'.sprintf("%02d", $day);
    $date = sprintf("%02d", $day) . " $html_title2";

    $date = new DateTime($date);
    //$date->add(new DateInterval('P2M')); // add 2 months
    $date = $date->format('Y-m-d');

    if (in_array($date, $pending_dates)) {
        $week2 .= '<td class="pending">' . $day;
    } elseif (in_array($date, $reservation_dates)) {
        if (in_array($date, $reservation_arrival_dates)) {
            if (in_array($date, $reservation_departure_dates)) {
                $week2 .= '<td class="reserve_arrival reserve_departure"><span>' . $day;
            } else {
                $week2 .= '<td class="reserve_arrival asdf"><span>' . $day;
            }
        } elseif (in_array($date, $reservation_departure_dates)) {
            $week2 .= '<td class="reserve_departure"><span>' . $day;
        } else {
            $week2 .= '<td class="reserve"><span>' . $day;
        }
    } else {
        $week2 .= '<td>' . $day;
    }
    $week2 .= '</td>';

    // End of the week OR End of the month
    if ($str2 % 7 == 6 || $day == $day_count2) {
        if ($day == $day_count2) {
            // Add empty cell
            $week2 .= str_repeat('<td></td>', 6 - ($str2 % 7));
        }
        $weeks2[] = '<tr>' . $week2 . '</tr>';
        // Prepare for new week
        $week2 = '';
    }
}


///////////////////////////// 4th Calendar /////////////////////////////////////


// 0:Sun 1:Mon 2:Tue ...
//$str3 = date('w', mktime(0, 0, 0, date('m', strtotime("+3 month", $timestamp)), 1, date('Y', $timestamp)));
$str3 = date('w', mktime(0, 0, 0, date('m', strtotime("+3 month", $timestamp)), 1, date('Y', strtotime("+3 month", $timestamp))));

// Create Calendar!!
$weeks3 = array();
$week3 = '';

// Add empty cell
$week3 .= str_repeat('<td></td>', $str3);

for ($day = 1; $day <= $day_count3; $day++, $str3++) {
    //$date = $ym.'-'.sprintf("%02d", $day);
    $date = sprintf("%02d", $day) . " $html_title3";
    $date = new DateTime($date);
    //$date->add(new DateInterval('P3M')); // add 3 months
    $date = $date->format('Y-m-d');

    if (in_array($date, $pending_dates)) {
        $week3 .= '<td class="pending">' . $day;
    } elseif (in_array($date, $reservation_dates)) {
        if (in_array($date, $reservation_arrival_dates)) {

            if (in_array($date, $reservation_departure_dates)) {
                $week3 .= '<td class="reserve_arrival reserve_departure"><span>' . $day;
            } else {
                $week3 .= '<td class="reserve_arrival asdf"><span>' . $day;
            }
        } elseif (in_array($date, $reservation_departure_dates)) {
            $week3 .= '<td class="reserve_departure"><span>' . $day;
        } else {
            $week3 .= '<td class="reserve"><span>' . $day;
        }
    } else {
        $week3 .= '<td>' . $day;
    }
    $week3 .= '</td>';

    // End of the week OR End of the month
    if ($str3 % 7 == 6 || $day == $day_count3) {
        if ($day == $day_count3) {
            // Add empty cell
            $week3 .= str_repeat('<td></td>', 6 - ($str3 % 7));
        }
        $weeks3[] = '<tr>' . $week3 . '</tr>';
        // Prepare for new week
        $week3 = '';
    }
}

?>


<div class="table-container" style="    width: 590px;">
    <div class="row">

        <div class="col-md-12 col-lg-12">
            <a href="?ym=<?php echo $prev; ?>" class="prev btn btn-danger pull-left">Previous</a>
            <a href="?ym=<?php echo $next; ?>" class="next  btn btn-danger pull-right">Next</a>
        </div>

    </div>


    <div class="row">
        <div class="col-md-6 col-lg-6" style="    float: left;     width: 50%;">
            <h3 class="text-center calender-heading"><?php echo $html_title; ?></h3>
            <table class="table table-bordered property-calendar table-stripped table-hover">
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                </tr>
                <?php
                foreach ($weeks as $week) {
                    echo $week;
                }
                ?>
            </table>

        </div>

        <div class="col-md-6 col-lg-6" style="    float: left;     width: 50%;">
            <h3 class="text-center calender-heading"><?php echo $html_title1; ?></h3>
            <table class="table table-bordered property-calendar table-stripped table-hover">
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                </tr>
                <?php
                foreach ($weeks1 as $week) {
                    echo $week;
                }
                ?>
            </table>

        </div>


    </div>

    <br>
    <div class="row">
        <div class="col-md-6 col-lg-6" style="    float: left;     width: 50%;">
            <h3 class="text-center calender-heading"><?php echo $html_title2; ?></h3>
            <table class="table table-bordered property-calendar table-stripped table-hover">
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                </tr>
                <?php
                foreach ($weeks2 as $week) {
                    echo $week;
                }
                ?>
            </table>

        </div>

        <div class="col-md-6 col-lg-6" style="    float: left;     width: 50%;">
            <h3 class="text-center calender-heading"><?php echo $html_title3; ?></h3>
            <table class="table table-bordered property-calendar table-stripped table-hover">
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                </tr>
                <?php
                foreach ($weeks3 as $week) {
                    echo $week;
                }
                ?>
            </table>

        </div>


    </div>

    <div class="calendar-info-labels">
        <div class="labels">
            <span class="text">Booked</span>
            <span class="color-box booked"></span>
        </div>
        <div class="labels">
            <span class="text">pending</span>
            <span class="color-box pending"></span>
        </div>
        <div class="labels">
            <span class="text">available</span>
            <span class="color-box available"></span>
        </div>

    </div>
</div>



<style>
    .calendar-block table {
        text-align: center;
        margin-bottom: 20px;
        width: 100%;
    }

    .calendar-block table tr,
    .calendar-block table td {
        border: 2px solid transparent;
    }

    .calendar-block table strong,
    .calendar-block table span {
        color: #000;
        display: block;
    }

    .calendar-block table strong span {
        color: #fff;
    }

    .booked {
        background: #ff0000;
        border: 1px solid #ccc;
    }

    .pending {
        background: #ffff00;
        border: 1px solid #ccc;
    }

    .owner {
        background: #999999;
        color: #fff;
        border: 1px solid #ccc;
    }

    .available {
        background: #fff;
        border: 1px solid #ccc;
    }

    .calendar-info-labels {
        text-align: center;
        line-height: 20px;
    }

    .calendar-info-labels .labels {
        display: inline-block;
        vertical-align: middle;
        margin: 0 5px;
    }

    .calendar-info-labels span {
        display: inline-block;
        vertical-align: middle;
        color: #fabc03;
        font-size: 10px;
        text-transform: capitalize;
    }

    .calendar-info-labels .color-box {
        height: 15px;
        width: 22px;
    }

    .next-prev-block {
        margin-bottom: 10px;
    }

    .reservation .form-group {
        width: 100%;
        margin-bottom: 10px;
    }

    .reservation .form-group label {
        width: 80px;
    }

    .table-container {
        width: 661px;
        margin-left: auto;
        margin-right: auto;
    }

    .table.property-calendar th {
        height: 30px;
        text-align: center;
        font-weight: 700;
        background-color: #989898;
        color: white !important;
    }

    .table.property-calendar .calender-heading {
        height: 30px;
        text-align: center;
        font-weight: 700;
        background-color: #989898;
        color: white !important;
        padding: 4px;
        margin-bottom: 3px;
    }

    .table.property-calendar td {
        height: 32px;
        width: 32px;
        text-align: center;
        position: relative;
        padding: 0;
        line-height: 3.128571;
    }

    .table.property-calendar td span {
        color: #2f0b0b;
        display: inline-block;
        font-weight: bold;
        height: 100%;
        line-height: 42px;
        position: relative;
        text-align: center;
        text-decoration: none;
        width: 100%;
        z-index: 0;
    }

    .table.property-calendar td span:before {
        border: 21.4px solid transparent;
        content: "";
        display: inline-block;
        height: 0;
        left: 0;
        position: absolute;
        top: 0;
        width: 0;
        z-index: -1;
    }

    .table.property-calendar .today {
        background: orange;
    }

    .table.property-calendar .owner {
        background: #999999;
    }

    .table.property-calendar .pending {
        color: black !important;
        background: #ffff00;
    }

    .table.property-calendar .owner_arrival {
        color: #fff !important;
    }

    .table.property-calendar .owner_arrival span:before {
        border-right-color: #999999;
        border-bottom-color: #999999;
    }

    .table.property-calendar .owner_departure {
        color: #fff !important;
    }

    .table.property-calendar .owner_departure span:before {
        border-left-color: #999999;
        border-top-color: #999999;
    }

    .table.property-calendar .reserve_arrival {
        color: #fff !important;
    }

    .table.property-calendar .reserve_arrival span:before {
        border-right-color: #ff0000;
        border-bottom-color: #ff0000;
    }

    .table.property-calendar .reserve_departure {
        color: #fff !important;
    }

    .table.property-calendar .reserve_departure span:before {
        border-left-color: #ff0000;
        border-top-color: #ff0000;
    }

    .table.property-calendar .reserve {
        background: #ff0000;
        color: #fff;
    }

    .table.property-calendar th:nth-of-type(7),
    .table.property-calendar td:nth-of-type(7) {
        color: blue;
    }

    .table.property-calendar th:nth-of-type(1),
    .table.property-calendar td:nth-of-type(1) {
        color: red;
    }
</style>
@stop