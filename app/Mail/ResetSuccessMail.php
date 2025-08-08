<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('Mot de passe réinitialisé avec succès')
            ->view('emails.reset_success');
    }
}
