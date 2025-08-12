<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\AbonnementConfirmationMail;

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

        if (!$client || empty($client['ID_']) || empty($client['email'])) {
            return redirect('/logReg')->with('error', 'Veuillez vous connecter.');
        }

        // Validation des champs reçus via formulaire POST
        $request->validate([
            'plan_id' => 'required|integer',
            'service_id' => 'required|integer',
            'prix' => 'required|numeric',
            'interval' => 'required|integer',
            'entreprise_id' => 'required|integer',
        ]);
        // dd($request);

        $plan_id = $request->input('plan_id');
        $service_id = $request->input('service_id');
        $prix = $request->input('prix');
        $interval = $request->input('interval');
        $entreprise_id = $request->input('entreprise_id');
        // 3. Préparer les données d’abonnement à envoyer à l’API
        $date_debut = Carbon::now()->format('Y-m-d');
        $date_fin = Carbon::now()->addDays($interval)->format('Y-m-d');

        // Générer un ID temporaire d'abonnement (exemple : timestamp) ou null si géré côté API
        $abonnement_id = 0;

        $abonnementData = [
            'idclient'   => $client['ID_'],
            'id_plan'    => $plan_id,
            'prix'       => $prix,
            'date_debut' => $date_debut,
            'date_fin'   => $date_fin,
            'statut'     => 'en_attente',
            'id'         => $abonnement_id
        ];

        $planData = [
            'id_plan'   => $plan_id,
            'id_service' => $service_id,
        ];


        // 4. Envoyer la requête POST à l’API pour créer l’abonnement
        // 4. Envoyer la requête POST à l’API pour créer l’abonnement
        $abonnementResponse = Http::post(
            $this->base_url . '/api/abonnements.php?entreprise_id=' . $entreprise_id,
            [
                'abonnement' => $abonnementData,
                'plan'       => $planData,
            ]
        );

        if (!$abonnementResponse->successful()) {
            if ($abonnementResponse->status() === 409) {
                return back()->with('error', 'Vous avez déjà un abonnement actif ou en attente pour ce plan/service.');
            }
            return back()->with('error', 'Erreur lors de la création de l’abonnement : Vous avez déjà un abonnement actif,en attente ou  expiré pour ce plan/service.');
        }

        // ✅ 4bis. Envoyer la notification seulement si abonnement créé avec succès
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
            $this->base_url . '/api/controller/notification/notificationController.php',
            $notificationPayload
        );

        if (!$notificationResponse->successful()) {
            // On ne bloque pas l'abonnement si la notif échoue, mais on log l'erreur
            // Log::warning('Échec d\'envoi de la notification : ' . $notificationResponse->body());
        }

        // 5. Envoyer un email de confirmation
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
            // On arrête tout et on renvoie une erreur claire
            return back()->withErrors([
                'email' => "L'envoi de l'email de confirmation a échoué. Veuillez réessayer."
            ]);
        }



        // 6. Rediriger vers dashboard avec succès
        return redirect()->route('paiement_success')
            ->with('success', 'Votre abonnement a été créé avec succès. Un email de confirmation vous a été envoyé.');
    }
    public function annulerAbonnement(Request $request, $id)
    {
        $client = Session::get('user');

        if (!$client || empty($client['ID_'])) {
            return redirect('/logReg')->with('error', 'Veuillez vous connecter.');
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json'
        ])->post($this->base_url . '/api/Mobile/annulerAbonnement.php', [
            'id' => $id,
            'statut' => 'annule'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data['success'])) {
                return back()->with('success', 'Abonnement annulé avec succès.');
            }
            return back()->with('error', $data['message'] ?? 'Erreur lors de l\'annulation.');
        }

        return back()->with('error', 'Erreur serveur lors de l\'annulation de l\'abonnement.');
    }
}
