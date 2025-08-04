<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExpirationReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $abonnement;
    public $joursRestants;

    public function __construct($abonnement, $joursRestants)
    {
        $this->abonnement = $abonnement;
        $this->joursRestants = $joursRestants;
    }

    public function build()
    {
        return $this->subject('Votre abonnement expire bientôt')
                    ->markdown('emails.expiration');
    }
}
