<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

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
        $response = Http::get($this->base_url . '/api/services.php');
        $services = $response->successful() ? $response->json() : [];

        return view('home_', ['services' => $services]);
    }

    /**
     * Détail d’un service
     */
    public function detailsService($id)
{
    // 1. Récupérer le service par son ID
    $serviceResponse = Http::get($this->base_url . "/api/controller/service/serviceById.php?id=" . $id);
    if (!$serviceResponse->successful() || empty($serviceResponse->json())) {
        abort(404, 'Service introuvable.');
    }
    $service = $serviceResponse->json();

    // 2. Récupérer les autres services de la même entreprise
    $servicesEntreprise = [];
    if (!empty($service['entreprise_id'])) {
        $entrepriseId = $service['entreprise_id'];
        $servicesEntrepriseResponse = Http::get($this->base_url . "/api/controller/service/serviceById.php?entreprise_id=" . $entrepriseId);

        if ($servicesEntrepriseResponse->successful() && $servicesEntrepriseResponse->json()) {
            // Exclure le service courant
            $servicesEntreprise = array_filter($servicesEntrepriseResponse->json(), function ($s) use ($id) {
                return $s['id'] != $id;
            });
        }
    }

    // 3. Récupérer les articles liés à ce service
    $articlesService = [];
    if (!empty($service['id'])) {
        $articlesResponse = Http::get($this->base_url . "/api/controller/article/articleByService.php?service_id=" . $service['id']);

        if ($articlesResponse->successful() && $articlesResponse->json()) {
            $articlesService = $articlesResponse->json();
        }
    }

    // 4. Récupérer tous les plans
    $plansResponse = Http::get($this->base_url . "/api/plan_controller.php");
    if (!$plansResponse->successful() || empty($plansResponse->json())) {
        abort(500, 'Impossible de charger les plans.');
    }
    $allPlans = $plansResponse->json();

    // 5. Filtrer les plans liés à ce service
    $plansForService = array_filter($allPlans, function ($plan) use ($id) {
        return isset($plan['id_service']) && $plan['id_service'] == $id;
    });
    $plansForService = array_values($plansForService);

    // 6. Récupérer les avantages
    $avantagesResponse = Http::get($this->base_url . "/api/controller/avantage/avantageController.php");
    $avantages = $avantagesResponse->successful() ? $avantagesResponse->json() : [];

    // 7. Grouper les avantages par plan_id
    $avantagesParPlan = [];
    foreach ($avantages as $av) {
        $planId = $av['plan_id'] ?? null;
        if ($planId) {
            $avantagesParPlan[$planId][] = $av['avantage'];
        }
    }

    // 8. Vue
    return view('details', compact(
        'service',
        'plansForService',
        'avantagesParPlan',
        'servicesEntreprise',
        'articlesService'
    ));
}


    /**
     * Traitement POST lié à un service
     */
    public function handlePost(Request $request, $id)
    {
        // Ici, on pourrait ajouter un traitement selon les besoins
        return redirect()->route('details', ['id' => $id])
                         ->with('success', 'Traitement effectué avec succès.');
    }
}
