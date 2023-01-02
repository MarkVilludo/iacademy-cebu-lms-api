<?php

namespace App\Mail\Admissions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\AdmissionStudentInformation;

class SendAcceptanceLetterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $information;

    public function __construct(AdmissionStudentInformation $information)
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
        $mail = $this->view('emails.admissions.acceptance_letter')
                    ->from('inquirecebu@iacademy.edu.ph', 'iACADEMY Cebu LMS')
                    ->subject('iACADEMY Cebu Admissions: Acceptance Letter')
                    ->replyTo('admissionscebu@iacademy.edu.ph', 'iACADEMY Cebu LMS');

        if (!empty($this->information->acceptanceAttachments)) {
            foreach ($this->information->acceptanceAttachments as $k => $v) {
                $mail = $mail->attach(
                    \Storage::path('public/acceptance_attachments/' . $v->filename . '.' . $v->filetype),
                    [
                        'as' => $v->orig_filename,
                    ]
                );
            }
        }
    }
}
