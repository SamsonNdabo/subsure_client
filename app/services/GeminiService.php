<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService {
    // Sujets autorisés pour SubSure
    protected $allowedTopics = [
        'abonnement',
        'contrat',
        'service',
        'stripe',
        'paiement',
        'facture',
        'renouvellement'
    ];

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

        // Cas spécial : question sur SubSure
        if (stripos($lowerPrompt, 'qu\'est') !== false && stripos($lowerPrompt, 'subsure') !== false) {
            return "SubSure est une plateforme de gestion des abonnements, contrats, services et paiements en ligne. Elle permet aux entreprises et clients de gérer leurs abonnements, suivre leurs factures, effectuer des paiements via Stripe, et renouveler leurs services facilement.";
        }

        // Détecte automatiquement le sujet
        $foundTopic = null;
        foreach ($this->allowedTopics as $topic) {
            if (stripos($lowerPrompt, $topic) !== false) {
                $foundTopic = $topic;
                break;
            }
        }

        if (!$foundTopic) {
            return "⚠️ Je ne peux répondre qu’aux questions liées aux abonnements, contrats, services, paiements Stripe, factures ou renouvellements.";
        }

        // Préparation du prompt pour Gemini
        $instruction = <<<EOT
Tu es l'assistant de SubSure.
⚡ Contrainte : Ne répondre que sur les sujets liés à SubSure (abonnements, contrats, services, paiements Stripe, factures, renouvellements).
Si la question sort de ce cadre, réponds uniquement : "Je ne suis pas autorisé à répondre à cela."
Fournis des réponses claires, concises et adaptées à un utilisateur de SubSure.
Le sujet principal de cette question est : {$foundTopic}

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
            // return "❌ Exception lors de l'appel à Gemini : " . $e->getMessage();
        }
    }
}
