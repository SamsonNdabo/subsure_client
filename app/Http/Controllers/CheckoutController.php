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

        if (!$abonnementId || !$prix || !$email) {
            return back()->with('error', 'Données manquantes pour finaliser le paiement.');
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

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$this->base_url}/api/Mobile/paiement.php", $payload);

        if ($response->successful()) {
            // Envoi mail avec vue blade
            Mail::send('emails.payment_confirmation', [
                'abonnementId' => $abonnementId,
                'prix' => $prix,
            ], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Confirmation de paiement - SubSure');
            });

            return view('paiement_stripe/checkout_success')->with('message', 'Paiement réussi et abonnement renouvelé. Un email de confirmation a été envoyé.');
        }

        return back()->with('error', 'Paiement réussi mais échec du renouvellement.');
    }


    public function cancel()
    {
        return view('paiement_stripe/checkout_cancel')->with('error', 'Paiement annulé.');
    }
}
