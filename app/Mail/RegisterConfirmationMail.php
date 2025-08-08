<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;

    public function __construct($name) { $this->name = $name; }

    public function build()
    {
        return $this->subject('Bienvenue sur SubSure')
            ->view('emails.register_confirmation');
    }
}
