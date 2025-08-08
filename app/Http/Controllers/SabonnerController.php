<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Carbon\Carbon;
use App\Mail\AbonnementConfirmationMail;

class SabonnerController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    public function checkout($plan_id, $abonnement_id, $service_id, $prix, $interval, $entreprise_id)
    {
        $client = Session::get('user');

        if (!$client || empty($client['ID_']) || empty($client['email'])) {
            return redirect('/logReg')->with('error', 'Veuillez vous connecter.');
        }

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $checkoutSession = StripeSession::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Abonnement au service #' . $service_id,
                        ],
                        'unit_amount' => intval(floatval($prix) * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('abonnements.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => url('/'),
                'customer_email' => $client['email'],
                'metadata' => [
                    'client_id' => $client['ID_'],
                    'plan_id' => $plan_id,
                    'abonnement_id' => $abonnement_id,
                    'service_id' => $service_id,
                    'prix' => $prix,
                    'interval' => $interval,
                    'entreprise_id' => $entreprise_id
                ]
            ]);

            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur Stripe : ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session_id = $request->get('session_id');

        if (!$session_id) {
            return redirect('/')->with('error', 'Session Stripe manquante.');
        }

        try {
            $session = StripeSession::retrieve($session_id);

            // Si paiement non réussi, on affiche erreur sans redirection
            if (!$session || $session->payment_status !== 'paid') {
                return view('paiement_error', ['message' => 'Paiement non validé.']);
            }

            $metadata = $session->metadata;

            $client_id = $metadata->client_id;
            $plan_id = $metadata->plan_id;
            $abonnement_id = $metadata->abonnement_id;
            $service_id = $metadata->service_id;
            $prix = $metadata->prix;
            $interval = $metadata->interval;
            $entreprise_id = $metadata->entreprise_id;

            $date_debut = Carbon::now()->format('Y-m-d');
            $date_fin = Carbon::now()->addDays($interval)->format('Y-m-d');

            $abonnementData = [
                'idclient' => $client_id,
                'id_plan' => $plan_id,
                'prix' => $prix,
                'date_debut' => $date_debut,
                'date_fin' => $date_fin,
                'statut' => 'actif',
                'id' => $abonnement_id
            ];

            $planData = [
                'id_plan' => $plan_id,
                'idservice' => $service_id,
            ];

            // Enregistrement de l’abonnement via API
            $abonnementResponse = Http::post($this->base_url . '/api/abonnements.php?entreprise_id=' . $entreprise_id, [
                'abonnement' => $abonnementData,
                'plan' => $planData,
            ]);

            if (!$abonnementResponse->successful()) {
                return view('paiement_error', ['message' => 'Erreur lors de l’enregistrement de l’abonnement : ' . $abonnementResponse->body()]);
            }

            $abonnementJson = $abonnementResponse->json();
            $abonnement_id = $abonnementJson['id'] ?? $abonnementData['id'];

            if (!$abonnement_id) {
                return view('paiement_error', ['message' => 'ID d’abonnement manquant après enregistrement.']);
            }

            // Envoi du mail de confirmation
            $user = Session::get('user');
            Mail::to($user['email'])->send(new AbonnementConfirmationMail(
                $user['nom'] ?? 'Client',
                $abonnementData['id_plan'],
                $abonnementData['prix'],
                $abonnementData['date_debut'],
                $abonnementData['date_fin'],
                $abonnementData['statut']
            ));

            // Enregistrement du paiement via API
            $paiementData = [
                'id_commande' => 0,
                'id_abonnement' => $abonnement_id,
                'date_paiement' => now()->format('Y-m-d H:i:s'),
                'montant' => $prix,
                'type_paiement' => 'Stripe',
                'transaction_id' => $session->payment_intent ?? $session->id,
                'statut' => $session->payment_status,
            ];

            $paiementResponse = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->base_url . '/api/controller/paiement/stripe_paiement.php', $paiementData);

            dd([
                'paiementData' => $paiementData,
                'status_code' => $paiementResponse->status(),
                'body' => $paiementResponse->body(),
                'json' => $paiementResponse->json(),
            ]);


            if (!$paiementResponse->successful()) {
                return view('paiement_error', ['message' => 'Erreur lors de l’enregistrement du paiement.']);
            }

            // Affiche la vue finale, pas de redirection !
            return view('paiement_success');
        } catch (\Exception $e) {
            return view('paiement_error', ['message' => 'Erreur Stripe : ' . $e->getMessage()]);
        }
    }
}
