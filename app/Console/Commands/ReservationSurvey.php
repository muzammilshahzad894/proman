<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;

class ReservationSurvey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:survey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reservation Survey';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        (new EmailService())->reservationSurveyEmailCron();

        $this->info('Reservation survey link send successfully!');
    }
}
