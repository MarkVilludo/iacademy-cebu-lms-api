<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\Admissions\{NotifiyForIForReservationMailnterviewMail};
use Carbon\Carbon;
use App\User;
use Mail;

class CronForRervationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'for-reservation:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return Command::SUCCESS;
        //save sent email date para dun magbbased yung days 3.
         //checking of all records - for interview send email every 24hrs from last updated_at
        $applications = AdmissionStudentInformation::where('status', 'For Reservation')
                                                    ->get();

        foreach($applications as $application) {
            

            $start = $application->updated_at 
            $end = date('Y-m-d');

            $diff = abs(strtotime($start) - strtotime($end));

            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $dayIntvl = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

            if ($daysIntval == 3) {
                Mail::to($application->email)->send(
                    new ForReservationMail($application)
                );
            }
        }

    }
}
