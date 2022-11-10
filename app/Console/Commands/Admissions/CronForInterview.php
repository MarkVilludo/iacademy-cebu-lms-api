<?php

namespace App\Console\Commands\Admissions;

use Illuminate\Console\Command;
use App\Models\{AdmissionStudentInformation, AdmissionInterviewSchedule};
use App\Mail\Admissions\{NotifiyForInterviewMail};
use Mail;


class CronForInterview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'for-interview:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Applicant is sent an email reminder every 24 hours if s/he hasn’t set a schedule.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return Command::SUCCESS;

        //checking of all records - for interview send email every 24hrs from last updated_at
        $applications = AdmissionStudentInformation::where('status', 'For Interview')
                                                    ->get();

        //7AM (24hrs) assuming every day at 7AM innotify
        foreach ($applications as $application) {
            $checkIfHasSched = AdmissionInterviewSchedule::where('student_information_id', $application->id)->first();

            if (!$checkIfHasSched) {
                Mail::to($application)->send(
                    new NotifiyForInterviewMail($application)
                );
            }
        }
    }
}
