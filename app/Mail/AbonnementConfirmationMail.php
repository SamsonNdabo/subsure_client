<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbonnementConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nom, $plan_id, $prix, $date_debut, $date_fin, $statut;

    public function __construct($nom, $plan_id, $prix, $date_debut, $date_fin, $statut)
    {
        $this->nom = $nom;
        $this->plan_id = $plan_id;
        $this->prix = $prix;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->statut = $statut;
    }

    public function build()
    {
        return $this->subject('Confirmation de votre abonnement')
            ->view('emails.abonnement_confirm');
    }
}
