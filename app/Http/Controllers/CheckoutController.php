<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
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

        $session = StripeSession::create([
            'customer_email' => $email,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => intval($amount),
                    'product_data' => [
                        'name' => 'Entreprise - SubSure',
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

        Session::put('stripe_session_id', $session->id);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $abonnementId = $request->input('abonnement_id');
        $prix = $request->input('prix');
        $email = $request->input('email');
        $clientId = $request->input('client_id');
        $planId = $request->input('plan_id');
        $serviceId = $request->input('service_id');

        if (!$abonnementId || !$prix || !$email || !$clientId || !$planId || !$serviceId) {
            return view('paiement_stripe/checkout_error')->with('error', 'Données manquantes pour finaliser le paiement.');
        }

        // ID transaction unique
        $transactionId = 'trans_' . uniqid();

        $payload = [
            'abonnement' => [
                'id' => (int) $abonnementId,
                'prix' => (float) $prix,
            ],
            'paiement' => [
                'type_paiement' => 'stripe',
                'transaction_id' => $transactionId,
            ],
        ];

        // 1️⃣ Confirmation du paiement auprès de l’API
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$this->base_url}/api/Mobile/paiement.php", $payload);

        if (!$response->successful()) {
            return view('paiement_stripe/checkout_error')->with('error', 'Paiement réussi mais échec du renouvellement côté serveur.');
        }

        $client = Session::get('user');
// dd($client);
        // 2️⃣ Récupérer le service par son ID
        $serviceResponse = Http::get($this->base_url . "/api/controller/service/serviceById.php?id=" . $serviceId);
        if (!$serviceResponse->successful() || empty($serviceResponse->json())) {
            abort(404, 'Service introuvable.');
        }

        $serviceData = $serviceResponse->json();

        // Vérification que entreprise_id existe
        if (!isset($serviceData[0]['entreprise_id'])) {
            return view('paiement_stripe/checkout_error')->with('error', 'Impossible de récupérer l’entreprise du service.');
        }

        $entrepriseId = $serviceData[0]['entreprise_id'];

        // 3️⃣ Notification API (id_destinataire = entreprise_id)
        $notifResponse = Http::post($this->base_url . '/api/controller/notification/notificationController.php', [
            'titre'           => 'Paiement d’abonnement confirmé',
            'message'         => 'Le client ' . $client['nom'] . ' a confirmé son abonnement au service : ' . $serviceData[0]['designation'],
            'type'            => 'paiement en ligne via Stripe',
            'statut'          => 'non_lu',
            'created_by'      => $clientId,
            'id_destinataire' => $entrepriseId,
            'date_creation'   => now()->format('Y-m-d H:i:s')
        ]);

        if (!$notifResponse->successful()) {
            return view('paiement_stripe/checkout_success')
                ->with('message', 'Paiement confirmé mais la notification n’a pas pu être envoyée.');
        }

        // 4️⃣ Envoi email avec gestion d'erreur
        try {
            Mail::send('emails.payment_confirmation', [
                'abonnementId' => $abonnementId,
                'prix' => $prix,
            ], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Confirmation de paiement - SubSure');
            });
        } catch (\Exception $e) {
            return view('paiement_stripe/checkout_success')
                ->with('message', 'Paiement confirmé, notification envoyée mais échec lors de l’envoi de l’email.');
        }

        // 5️⃣ Tout est OK
        return view('paiement_stripe/checkout_success')
            ->with('message', 'Paiement réussi, abonnement renouvelé et email envoyé avec succès.');
    }



    public function cancel()
    {
        return view('paiement_stripe/checkout_cancel')->with('error', 'Paiement annulé.');
    }
}
