<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    /**
     * Page d'accueil : liste tous les services
     */
    public function home()
    {
        try {
            $response = Http::timeout(5)->get($this->base_url . '/api/services.php');
            // dd($response->json());
            if ($response->failed()) {
                Log::error("API services.php inaccessible ou erreur : " . $response->status());
                return view('home_', ['services' => [], 'error' => 'Impossible de charger les services.']);
            }

            $services = $response->json();

            if (!is_array($services)) {
                Log::error("Réponse API services.php invalide : " . $response->body());
                $services = [];
            }
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'appel API services.php : " . $e->getMessage());
            return view('home_', ['services' => [], 'error' => 'Service API indisponible.']);
        }

        return view('home_', compact('services'));
    }

    /**
     * Détail d’un service
     */
    public function detailsService($id)
    {
        try {
            // 1️⃣ Récupérer le service
            $serviceResponse = Http::timeout(5)->get($this->base_url . "/api/controller/service/serviceById.php?id=" . $id);

            if ($serviceResponse->failed() || empty($serviceResponse->json())) {
                abort(404, 'Service introuvable.');
            }

            $serviceData = $serviceResponse->json();
            $service = is_array($serviceData) && isset($serviceData[0]) ? $serviceData[0] : $serviceData;

        } catch (\Exception $e) {
            Log::error("Erreur lors de la récupération du service ID {$id} : " . $e->getMessage());
            abort(500, 'Impossible de récupérer les données du service.');
        }

        // 2️⃣ Récupérer l'entreprise
        $entreprise = null;
        if (!empty($service['entreprise_id'])) {
            try {
                $entrepriseResponse = Http::timeout(5)->get($this->base_url . "/api/controller/service/entrepriseById.php?entreprise_id=" . $service['entreprise_id']);
                if ($entrepriseResponse->successful() && !empty($entrepriseResponse->json())) {
                    $entreprise = $entrepriseResponse->json();
                }
            } catch (\Exception $e) {
                Log::warning("Impossible de récupérer l'entreprise pour le service {$id} : " . $e->getMessage());
            }
        }

        // 3️⃣ Autres services de la même entreprise
        $servicesEntreprise = [];
        if (!empty($service['entreprise_id'])) {
            try {
                $servicesEntrepriseResponse = Http::timeout(5)->get($this->base_url . "/api/controller/service/serviceById.php?entreprise_id=" . $service['entreprise_id']);
                if ($servicesEntrepriseResponse->successful() && $servicesEntrepriseResponse->json()) {
                    $servicesEntreprise = array_filter($servicesEntrepriseResponse->json(), fn($s) => $s['id'] != $id);
                }
            } catch (\Exception $e) {
                Log::warning("Impossible de récupérer les autres services de l'entreprise : " . $e->getMessage());
            }
        }

        // 4️⃣ Articles liés
        $articlesService = [];
        if (!empty($service['id'])) {
            try {
                $articlesResponse = Http::timeout(5)->get($this->base_url . "/api/controller/article/articlecontroller.php?service_id=" . $service['id']);
                if ($articlesResponse->successful() && $articlesResponse->json()) {
                    $articlesService = $articlesResponse->json();
                }
            } catch (\Exception $e) {
                Log::warning("Impossible de récupérer les articles pour le service {$id} : " . $e->getMessage());
            }
        }

        // 5️⃣ Plans (critique : si fail → 500)
        try {
            $plansResponse = Http::timeout(5)->get($this->base_url . "/api/plan_controller.php");
            if ($plansResponse->failed() || empty($plansResponse->json())) {
                abort(500, 'Impossible de charger les plans.');
            }
            $allPlans = $plansResponse->json();
        } catch (\Exception $e) {
            Log::error("Erreur lors de la récupération des plans : " . $e->getMessage());
            abort(500, 'Impossible de charger les plans.');
        }

        $plansForService = array_values(array_filter($allPlans, fn($plan) => isset($plan['id_service']) && $plan['id_service'] == $id));

        // 6️⃣ Avantages
        $avantagesParPlan = [];
        try {
            $avantagesResponse = Http::timeout(5)->get($this->base_url . "/api/controller/avantage/avantageController.php");
            $avantages = $avantagesResponse->successful() ? $avantagesResponse->json() : [];
            foreach ($avantages as $av) {
                $planId = $av['plan_id'] ?? null;
                if ($planId) {
                    $avantagesParPlan[$planId][] = $av['avantage'];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Impossible de récupérer les avantages : " . $e->getMessage());
        }

        return view('details', compact(
            'service',
            'plansForService',
            'avantagesParPlan',
            'servicesEntreprise',
            'articlesService',
            'entreprise'
        ));
    }

    /**
     * Traitement POST lié à un service
     */
    public function handlePost(Request $request, $id)
    {
        return redirect()->route('details', ['id' => $id])
            ->with('success', 'Traitement effectué avec succès.');
    }
}
