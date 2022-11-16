<?php

namespace App\Mail\Admissions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForReservationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $information;
    public $activeSem;
    public function __construct($information, $activeSem = null)
    {
        $this->information = $information;
        $this->activeSem = $activeSem;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admissions.acceptance_letter')
                    ->from('markanthony.villudo@gmail.com', 'iACADEMY Cebu Portal')
                    ->subject('iACADEMY Admissions: Online Application For Reservation - ' . $this->information->first_name . ' ' . $this->information->last_name)
                    ->replyTo('admissionscebu@iacademy.edu.ph', 'iACADEMY Cebu Portal');
    }
}
