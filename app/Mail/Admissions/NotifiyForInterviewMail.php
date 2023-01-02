<?php

namespace App\Mail\Admissions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifiyForInterviewMail extends Mailable
{
    use Queueable, SerializesModels;

    public $information;
    public function __construct($information)
    {
        $this->information = $information;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admissions.notifiy_for_interview')
                    ->from('inquirecebu@iacademy.edu.ph', 'iACADEMY Cebu Portal')
                    ->subject('iACADEMY Admissions: Online Application For Interview - ' . $this->information->first_name . ' ' . $this->information->last_name)
                    ->replyTo('admissionscebu@iacademy.edu.ph', 'iACADEMY Cebu Portal');
    }
}
