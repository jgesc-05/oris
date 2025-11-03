<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientLoginLink extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $url = route('paciente.login.verify', ['token' => $this->token]);

        return $this->subject('Accede a tu cuenta de paciente')
                    ->markdown('emails.patient-login', ['url' => $url]);
    }
}
