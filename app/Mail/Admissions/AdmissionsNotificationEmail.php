<?php

namespace App\Mail\Admissions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\AdmissionStudentInformation;

class AdmissionsNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        return $this->view('emails.admissions.admissions_notification')
                    ->from('inquirecebu@iacademy.edu.ph', 'iACADEMY Cebu Portal')
                    ->subject('iACADEMY Admissions: '.$this->information->subject.' - ' . $this->information->student->first_name . ' ' . $this->information->student->last_name)
                    ->replyTo('admissionscebu@iacademy.edu.ph', 'iACADEMY Cebu Portal');
    }
}
