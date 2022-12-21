<?php

namespace App\Mail\Registrar;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\AdmissionStudentInformation;

class RegistrationNotificationMail extends Mailable
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
        return $this->view('emails.registrar.registration_notification')
                    ->from('markanthony.villudo@gmail.com', 'iACADEMY Cebu Portal')
                    ->subject('iACADEMY Registrar: Enrollment Notification - ' . $this->information->student->first_name . ' ' . $this->information->student->last_name)
                    ->replyTo('admissionscebu@iacademy.edu.ph', 'iACADEMY Cebu Portal');
    }
}
