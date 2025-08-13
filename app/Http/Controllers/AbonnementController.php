<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AbonnementController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    // 🔹 Liste des abonnements du client
    public function listabonnement($id)
    {
        $data['abonnement'] = [];

        try {
            $response = Http::timeout(5)->get("{$this->base_url}/api/Mobile/abonnementSuscrit.php?ID_={$id}");

            if ($response->successful() && !empty($response->json())) {
                $data['abonnement'] = $response->json();
            } else {
                Log::warning("API abonnements inaccessible pour client {$id}, status: " . $response->status());
            }
        } catch (\Exception $e) {
            Log::error("Erreur récupération abonnements client {$id} : " . $e->getMessage());
        }

        return view('clients/MesServices', $data);
    }

    // 🔹 Notification : annulation d'abonnement
    public function notificationAnnuler(Request $request)
    {
        $client = Session::get('user');
        if (!$client) {
            return back()->with('error', 'Utilisateur non authentifié.');
        }

        $numAbonnement = $request->input('numAbonnement');
        $nomService    = $request->input('Nom_Service');
        $entrepriseId  = $request->input('entreprise_id');

        if (!$numAbonnement || !$nomService || !$entrepriseId) {
            return back()->with('error', 'Informations manquantes pour l’envoi de la notification.');
        }

        $message = "Le client {$client['nom']} a demandé l'annulation de son abonnement (Num: {$numAbonnement}) au service : {$nomService}";

        $notifSent = $this->sendNotification(
            'Demande d\'annulation d\'abonnement',
            $message,
            $client['ID_'],
            $entrepriseId
        );

        $msg = $notifSent ? 'Demande d’annulation envoyée.' : 'Notification non envoyée.';
        return back()->with($notifSent ? 'success' : 'warning', $msg);
    }

    // 🔹 Notification : réactivation / paiement confirmé
    public function notificationActive(Request $request)
    {
        $client = Session::get('user');
        if (!$client) {
            return back()->with('error', 'Utilisateur non authentifié.');
        }

        $numAbonnement = $request->input('numAbonnement');
        $nomService    = $request->input('Nom_Service');
        $entrepriseId  = $request->input('entreprise_id');

        if (!$numAbonnement || !$nomService || !$entrepriseId) {
            return back()->with('error', 'Informations manquantes pour l’envoi de la notification.');
        }

        $message = "Le client {$client['nom']} a demandé la réactivation de son abonnement (Num: {$numAbonnement}) au service : {$nomService}";

        $notifSent = $this->sendNotification(
            'Demande de réactivation d\'abonnement',
            $message,
            $client['ID_'],
            $entrepriseId
        );

        $msg = $notifSent ? 'Demande de réactivation envoyée.' : 'Notification non envoyée.';
        return back()->with($notifSent ? 'success' : 'warning', $msg);
    }

    // 🔹 Méthode pour envoyer une notification à l'API
    private function sendNotification($titre, $message, $createdBy, $idDestinataire)
    {
        try {
            $notifResponse = Http::timeout(5)->post("{$this->base_url}/api/controller/notification/notificationController.php", [
                'titre'           => $titre,
                'message'         => $message,
                'type'            => 'Service Client',
                'statut'          => 'non_lu',
                'created_by'      => $createdBy,
                'id_destinataire' => $idDestinataire,
                'date_creation'   => now()->format('Y-m-d H:i:s')
            ]);

            if (!$notifResponse->successful()) {
                Log::warning("Notification non envoyée, status: " . $notifResponse->status());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Erreur envoi notification : " . $e->getMessage());
            return false;
        }
    }
}
