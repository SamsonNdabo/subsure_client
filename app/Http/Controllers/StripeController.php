<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Http;

class StripeController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function simulerPaiement(Request $request)
{
    $validated = $request->validate([
        'client_id' => 'required|integer',
        'plan_id' => 'required|integer',
        'abonnement_id' => 'required|integer',
        'service_id' => 'required|integer',
        'prix' => 'required|numeric|min:1',
        'email' => 'required|email',
    ]);

    try {
        // 1. Créer le PaymentIntent en mode test + confirmer automatiquement
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => intval($validated['prix']) * 100,
            'currency' => 'usd',
            'payment_method' => 'pm_card_visa',
            'confirmation_method' => 'automatic',
            'confirm' => true,
            'receipt_email' => $validated['email'],
            'metadata' => [
                'client_id' => $validated['client_id'],
                'plan_id' => $validated['plan_id'],
                'abonnement_id' => $validated['abonnement_id'],
                'service_id' => $validated['service_id'],
            ],
        ]);

        if ($paymentIntent->status !== 'succeeded') {
            return response()->json([
                'status' => 'error',
                'message' => 'Le paiement a échoué ou est incomplet.',
            ]);
        }

        // 2. Enregistrer le paiement (simulateur de la procédure PHP distante)
        $response = Http::post("{$this->base_url}/api/Mobile/paiement.php", [
            'abonnement' => [
                'id' => $validated['abonnement_id'],
                'price' => $validated['prix'],
            ],
            'paiement' => [
                'type_paiement' => 'stripe',
                'transaction_id' => $paymentIntent->id,
            ]
        ]);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Paiement simulé avec succès et abonnement renouvelé.',
                'stripe_payment_id' => $paymentIntent->id
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Paiement réussi, mais échec lors de l’enregistrement distant.',
        ], 500);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Erreur : ' . $e->getMessage(),
        ], 500);
    }
}

}
