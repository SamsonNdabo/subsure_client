<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }


    public function checkout($client_id, $plan_id, $abonnement_id, $service_id, $prix, $email)
    {
        $amount = $prix * 100; // en cents

        try {
            // Création de la session Stripe
            $session = StripeSession::create([
                'customer_email' => $email,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => intval($amount),
                        'product_data' => [
                            'name' => 'Paiement d’abonnement',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success', [
                    'client_id' => $client_id,
                    'plan_id' => $plan_id,
                    'abonnement_id' => $abonnement_id,
                    'service_id' => $service_id,
                    'prix' => $prix,
                    'email' => $email,
                ]),
                'cancel_url' => route('stripe.cancel'),
            ]);

            // Stocker l’ID de session Stripe
            Session::put('stripe_session_id', $session->id);

            return redirect($session->url);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Erreur réseau (problème internet / serveur inaccessible)
            Log::error("Stripe API Connection Error: " . $e->getMessage());
            return back()->with('error', 'Impossible de contacter le service de paiement. Veuillez vérifier votre connexion internet et réessayer.');
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Erreurs Stripe (clé invalide, compte suspendu, etc.)
            Log::error("Stripe API Error: " . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la communication avec le service de paiement. Merci de réessayer plus tard.');
        } catch (\Exception $e) {
            // Toute autre erreur inattendue
            Log::error("Stripe Checkout Error: " . $e->getMessage());
            return back()->with('error', 'Une erreur inattendue est survenue. Merci de réessayer.');
        }
    }


    public function success(Request $request)
    {
        $abonnementId = $request->input('abonnement_id');
        $prix         = $request->input('prix');
        $email        = $request->input('email');
        $clientId     = $request->input('client_id');
        $planId       = $request->input('plan_id');
        $serviceId    = $request->input('service_id');

        // ✅ Vérification des paramètres obligatoires
        if (!$abonnementId || !$prix || !$email || !$clientId || !$planId || !$serviceId) {
            return view('paiement_stripe/checkout_cancel')
                ->with('error', '❌ Données manquantes pour finaliser le paiement.');
        }

        // ID transaction unique
        $transactionId = 'trans_' . uniqid();

        $payload = [
            'abonnement' => [
                'id'   => (int) $abonnementId,
                'prix' => (float) $prix,
            ],
            'paiement' => [
                'type_paiement'  => 'stripe',
                'transaction_id' => $transactionId,
            ],
        ];

        try {
            // 1️⃣ Confirmation du paiement auprès de l’API
            $response = Http::timeout(30) // timeout 30s
                ->withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post("{$this->base_url}/api/Mobile/paiement.php", $payload);

            if (!$response->successful()) {
                return view('paiement_stripe/checkout_cancel')
                    ->with('error', ' Paiement Stripe confirmé, mais échec du renouvellement côté serveur distant.');
            }

            $client = Session::get('user');

            // 2️⃣ Récupération du service
            $serviceResponse = Http::timeout(30)->get(
                $this->base_url . "/api/controller/service/serviceById.php?id=" . $serviceId
            );

            if (!$serviceResponse->successful() || empty($serviceResponse->json())) {
                return view('paiement_stripe/checkout_cancel')
                    ->with('error', ' Service introuvable.');
            }

            $serviceData = $serviceResponse->json();
            if (!isset($serviceData[0]['entreprise_id'])) {
                return view('paiement_stripe/checkout_cancel')
                    ->with('error', ' Impossible de récupérer l’entreprise du service.');
            }

            $entrepriseId = $serviceData[0]['entreprise_id'];

            // 3️⃣ Notification API
            $notifResponse = Http::timeout(30)->post(
                $this->base_url . '/api/controller/notification/notificationController.php',
                [
                    'titre'           => 'Paiement d’abonnement confirmé',
                    'message'         => 'Le client ' . ($client['nom'] ?? '') .
                        ' a confirmé son abonnement au service : ' . ($serviceData[0]['designation'] ?? ''),
                    'type'            => 'paiement en ligne via Stripe',
                    'statut'          => 'non_lu',
                    'created_by'      => $clientId,
                    'id_destinataire' => $entrepriseId,
                    'date_creation'   => now()->format('Y-m-d H:i:s'),
                ]
            );

            if (!$notifResponse->successful()) {
                return view('paiement_stripe/checkout_success')
                    ->with('message', ' Paiement confirmé mais s la notification n’a pas pu être envoyée.');
            }

            // 4️⃣ Envoi de l’email
            try {
                Mail::send(
                    'emails.payment_confirmation',
                    [
                        'abonnementId' => $abonnementId,
                        'prix'         => $prix,
                    ],
                    function ($message) use ($email) {
                        $message->to($email)->subject('Confirmation de paiement - SubSure');
                    }
                );
            } catch (\Exception $e) {
                return view('paiement_stripe/checkout_success')
                    ->with('message', ' Paiement confirmé et notification envoyée, mais ❌ échec de l’envoi de l’email.');
            }


            return view('paiement_stripe/checkout_success')
                ->with('message', ' Paiement réussi, abonnement renouvelé et email envoyé avec succès.');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Erreur de connexion API (timeout, hôte introuvable, etc.)
            return view('paiement_stripe/checkout_cancel')
                ->with('error', ' Erreur de connexion au serveur distant. Merci de réessayer plus tard.');
        } catch (\Exception $e) {
            // Toute autre erreur
            return view('paiement_stripe/checkout_cancel')
                ->with('error', ' Une erreur inattendue est survenue : ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return view('paiement_stripe/checkout_cancel')
            ->with('error', ' Paiement annulé par l’utilisateur.');
    }
}
