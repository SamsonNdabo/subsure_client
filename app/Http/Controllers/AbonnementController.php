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

    // 🔹 Liste des abonnements avec infos entreprise dynamiques
    public function listabonnement($id)
    {
        try {
            $response = Http::timeout(10)->get($this->base_url . "/api/Mobile/abonnementSuscrit.php?ID_=" . $id);
// dd($response->json());
            if (!$response->successful()) {
                return back()->with('error', 'Impossible de récupérer vos abonnements.');
            }

            $abonnements = $response->json()['abonnements'] ?? [];

            // Récupérer les informations de toutes les entreprises distinctes liées aux abonnements
            $entreprises = [];
            foreach ($abonnements as $abn) {
                $entreprise_id = $abn['entreprise_id'] ?? null; // <-- CORRECTION ICI
                if ($entreprise_id && !isset($entreprises[$entreprise_id])) {
                    $entrepriseResponse = Http::timeout(10)->get($this->base_url . "/api/entreprise.php?entreprise_id=" . $entreprise_id);
                    if ($entrepriseResponse->successful()) {
                        $entreprises[$entreprise_id] = $entrepriseResponse->json();
                    } else {
                        $entreprises[$entreprise_id] = null;
                    }
                }
            }

            // s

            return view('clients.MesServices', [
                'abonnements' => $abonnements,
                'abonnement' => $abonnements,
                'entreprises' => $entreprises,
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', 'API distante inaccessible. Vérifiez votre connexion internet.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur interne : ' . $e->getMessage());
        }
    }

    // 🔹 Notification : annulation d'abonnement
    public function notificationAnnuler(Request $request)
    {
        $client = Session::get('user');
        if (!$client) return back()->with('error', 'Utilisateur non authentifié.');

        $numAbonnement = $request->input('numAbonnement');
        $nomService    = $request->input('Nom_Service');
        $entrepriseId  = $request->input('entreprise_id');
        // dd([$numAbonnement, $nomService, $entrepriseId]);
        if (!$numAbonnement || !$nomService || !$entrepriseId) {
            return back()->with('error', 'Informations manquantes pour l’envoi de la notification.');
        }

        $message = "Le client {$client['nom']} a demandé l'annulation de son abonnement (Num: {$numAbonnement}) au service : {$nomService}";

        $notifSent = $this->sendNotification(
            "Demande d'annulation d'abonnement",
            $message,
            $client['ID_'],
            $entrepriseId
        );

        return back()->with($notifSent ? 'success' : 'warning', $notifSent ? 'Demande d’annulation envoyée.' : 'Notification non envoyée.');
    }

    // 🔹 Notification : réactivation / paiement confirmé
    public function notificationActive(Request $request)
    {
        $client = Session::get('user');
        if (!$client) return back()->with('error', 'Utilisateur non authentifié.');

        $numAbonnement = $request->input('numAbonnement');
        $nomService    = $request->input('Nom_Service');
        $entrepriseId  = $request->input('entreprise_id');

        if (!$numAbonnement || !$nomService || !$entrepriseId) {
            return back()->with('error', 'Informations manquantes pour l’envoi de la notification.');
        }

        $message = "Le client {$client['nom']} a demandé la réactivation de son abonnement (Num: {$numAbonnement}) au service : {$nomService}";

        $notifSent = $this->sendNotification(
            "Demande de réactivation d'abonnement",
            $message,
            $client['ID_'],
            $entrepriseId
        );

        return back()->with($notifSent ? 'success' : 'warning', $notifSent ? 'Demande de réactivation envoyée.' : 'Notification non envoyée.');
    }

    // 🔹 Envoi notification
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

            return $notifResponse->successful();
        } catch (\Exception $e) {
            Log::error("Erreur envoi notification : " . $e->getMessage());
            return false;
        }
    }
}
