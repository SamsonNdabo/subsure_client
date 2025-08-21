<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService {
    /**
     * Génère du contenu en se basant sur Gemini API pour SubSure
     * 
     * @param string $prompt La question ou requête de l'utilisateur
     * @return string Réponse générée ou message d'erreur
     */
    public function generatedContent($prompt) {
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return "❌ Erreur : clé API Gemini manquante dans .env (GEMINI_API_KEY).";
        }

        $lowerPrompt = strtolower($prompt);

        // Cas spécial : question directe sur SubSure
        if (stripos($lowerPrompt, 'subsure') !== false) {
            return "SubSure est une plateforme de gestion des abonnements, contrats, services et paiements en ligne. Elle permet aux entreprises et clients de gérer leurs abonnements, suivre leurs factures, effectuer des paiements via Stripe, et renouveler leurs services facilement.";
        }

        // Préparer le prompt pour Gemini avec fallback automatique
        $instruction = <<<EOT
Tu es l'assistant officiel de SubSure.
⚡ Contrainte : Répond toujours aux questions liées à SubSure, peu importe le sujet exact. 
Tu peux répondre sur :
- Abonnements, renouvellements, paiements Stripe, factures
- Contrats et services
- Comment s'abonner ou gérer son abonnement
- Problèmes de paiement ou assistance client
Si la question ne contient aucun mot-clé SubSure, répond quand même de manière informative dans le contexte SubSure.

Fournis des réponses claires, concises et adaptées à un utilisateur de SubSure.

Question : {$prompt}
EOT;

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $instruction]
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $json = $response->json();
                if (!empty($json['candidates'][0]['content']['parts'][0]['text'])) {
                    return $json['candidates'][0]['content']['parts'][0]['text'];
                }
                return "⚠️ Gemini n'a pas pu générer de réponse pour cette question.";
            }

            return "❌ Erreur API Gemini : {$response->status()} - {$response->body()}";

        } catch (\Exception $e) {
            return "❌ Impossible de contacter Gemini. Vérifiez votre connexion internet.";
        }
    }
}
