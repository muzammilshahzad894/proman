<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\SendSurveyTime;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ReservationSurvey::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        //$schedule->command('reservation:survey')->daily();
        $schedule->command('queue:work')->everyMinute();

        // $SendSurveyTime = SendSurveyTime::query()->first();
        // if($SendSurveyTime) {
        //     $schedule->command('reservation:survey')->dailyAt($SendSurveyTime->time);
        // }
        // else{
        //     $schedule->command('reservation:survey')->daily();
        // }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
