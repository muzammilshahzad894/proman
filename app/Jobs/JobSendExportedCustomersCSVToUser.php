<?php

namespace App\Jobs;

use App\Mail\MailSendExportedCustomersCSVToUser;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobSendExportedCustomersCSVToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $email;
    public $file_name;
    public $customers;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file_name,$email,$customers)
    {
        $this->email = $email;
        $this->file_name = $file_name;
        $this->customers = $customers;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // send mail with attachment to user
        $this->exportCustomerCsv($this->customers);
        info('JobSendExportedCustomersCSVToUser:handle()');
        $email = new MailSendExportedCustomersCSVToUser($this->file_name,$this->email);
        Mail::to($this->email)->send($email);
        info('Mail job Sent Successfully');
    }

    public function fail($exception = null)
    {
        info('JobSendExportedCustomersCSVToUser:fail()');
        // Send user notification of failure, etc...
    }

    public function exportCustomerCsv($customers)
    {
        info('Exporting Customers CSV');
        \Excel::create('customers_list'.Carbon::now(), function($excel) use ($customers) {
            $excel->sheet('mySheet', function($sheet) use ($customers)
            {
                $sheet->cell('A1', function($cell) {$cell->setValue('First Name');   });
                $sheet->cell('B1', function($cell) {$cell->setValue('Last Name');   });
                $sheet->cell('C1', function($cell) {$cell->setValue('Email');   });
                $sheet->cell('D1', function($cell) {$cell->setValue('Phone Number');   });
                $sheet->cell('E1', function($cell) {$cell->setValue('Reservations');   });
                $sheet->cell('F1', function($cell) {$cell->setValue('Reservations');   });
                if (!empty($customers)) {
                    foreach ($customers as $key => $value) {
                        $i= $key+2;
                        $sheet->cell('A'.$i, $value['first_name']);
                        $sheet->cell('B'.$i, $value['last_name']);
                        $sheet->cell('C'.$i, $value['email']);
                        $sheet->cell('D'.$i, $value['phone']);
                        $sheet->cell('E'.$i, count_reservations(@$value['email']));
                        $sheet->cell('F'.$i, to_date(@$value['created_at'],1));
                    }
                }
            });
        })->store('xlsx',public_path('customer/export'));
        info('Exported Customers CSV');
    }
}
