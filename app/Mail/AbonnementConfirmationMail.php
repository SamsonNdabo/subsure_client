<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbonnementConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

   public $nomClient;
public $planId;
public $prix;
public $dateDebut;
public $dateFin;
public $statut;

public function __construct($nomClient, $planId, $prix, $dateDebut, $dateFin, $statut)
{
    $this->nomClient = $nomClient;
    $this->planId = $planId;
    $this->prix = $prix;
    $this->dateDebut = $dateDebut;
    $this->dateFin = $dateFin;
    $this->statut = $statut;
}

public function build()
{
    return $this->subject('Confirmation de votre abonnement Subsure')
        ->view('emails.abonnement_confirm')
        ->with([
            'nomClient' => $this->nomClient,
            'planId' => $this->planId,
            'prix' => $this->prix,
            'dateDebut' => $this->dateDebut,
            'dateFin' => $this->dateFin,
            'statut' => $this->statut,
            'lienAbonnements' => route('dashboard')
        ]);
}
}