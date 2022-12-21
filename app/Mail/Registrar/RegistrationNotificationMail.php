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
    public $message;
    public function __construct($information, $message)
    {
        $this->information = $information;        
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admissions.registration_notification')
                    ->from('markanthony.villudo@gmail.com', 'iACADEMY Cebu Portal')
                    ->subject('iACADEMY Admissions: Online Application For Interview - ' . $this->information->first_name . ' ' . $this->information->last_name)
                    ->replyTo('admissionscebu@iacademy.edu.ph', 'iACADEMY Cebu Portal');
    }
}
