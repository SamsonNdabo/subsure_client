<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\AbonnementConfirmationMail;
use Illuminate\Support\Facades\Log;

class SabonnerController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    public function creerAbonnement(Request $request)
    {
        $client = Session::get('user');

        // ✅ Vérif connexion utilisateur
        if (!$client || empty($client['ID_']) || empty($client['email'])) {
            return redirect('/logReg')->with('error', 'Veuillez vous connecter.');
        }

        // ✅ Validation des champs
        $request->validate([
            'plan_id'      => 'required|integer',
            'service_id'   => 'required|integer',
            'prix'         => 'required|numeric',
            'interval'     => 'required|integer',
            'entreprise_id'=> 'required|integer',
        ]);

        $plan_id      = $request->input('plan_id');
        $service_id   = $request->input('service_id');
        $prix         = $request->input('prix');
        $interval     = $request->input('interval');
        $entreprise_id= $request->input('entreprise_id');

        $date_debut = Carbon::now()->format('Y-m-d');
        $date_fin   = Carbon::now()->addDays($interval)->format('Y-m-d');

        // Données abonnement
        $abonnementData = [
            'idclient'   => $client['ID_'],
            'id_plan'    => $plan_id,
            'prix'       => $prix,
            'date_debut' => $date_debut,
            'date_fin'   => $date_fin,
            'statut'     => 'en_attente',
            'id'         => 0
        ];

        $planData = [
            'id_plan'    => $plan_id,
            'id_service' => $service_id,
        ];

        // 1️⃣ Création abonnement via API
        $abonnementResponse = Http::post(
            "{$this->base_url}/api/abonnements.php?entreprise_id={$entreprise_id}",
            [
                'abonnement' => $abonnementData,
                'plan'       => $planData,
            ]
        );

        if (!$abonnementResponse->successful()) {
            if ($abonnementResponse->status() === 409) {
                return back()->with('error', 'Vous avez déjà un abonnement actif ou en attente pour ce plan/service.');
            }
            return back()->with('error', 'Erreur lors de la création de l’abonnement : Vous avez déjà un abonnement actif, en attente ou expiré a un plan de ce service.');
        }

        // 2️⃣ Envoi notification API
        $notificationPayload = [
            'titre'           => 'Nouvel abonnement',
            'message'         => 'Le client ' . $client['nom'] . ' vient de souscrire à un abonnement au service ID : ' . $service_id,
            'type'            => 'abonnement',
            'statut'          => 'non_lu',
            'created_by'      => $client['ID_'],
            'id_destinataire' => $entreprise_id,
            'date_creation'   => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $notificationResponse = Http::post(
            "{$this->base_url}/api/controller/notification/notificationController.php",
            $notificationPayload
        );

        if (!$notificationResponse->successful()) {
            Log::warning("Échec notification abonnement : " . $notificationResponse->body());
        }

        // 3️⃣ Envoi email avec gestion d'échec
        try {
            Mail::to($client['email'])->send(
                new AbonnementConfirmationMail(
                    $client['nom'] ?? 'Client',
                    $plan_id,
                    $prix,
                    $date_debut,
                    $date_fin,
                    'en_attente'
                )
            );
        } catch (\Exception $e) {
            return back()->with('error', "Paiement confirmé mais l'envoi de l'email de confirmation a échoué.");
        }

        // 4️⃣ Succès total
        return redirect()->route('paiement_success')
            ->with('success', 'Votre abonnement a été créé avec succès. Un email de confirmation vous a été envoyé.');
    }   
}
