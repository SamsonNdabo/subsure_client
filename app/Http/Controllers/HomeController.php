<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    public function home(){

        $response = Http::get($this->base_url . '/api/services.php');
        $data['services'] = $response->successful() ? $response->json() : [];
        return view('home_', $data);
    }
public function detailsService($id)
{
    $response = Http::get($this->base_url . "/api/controller/service/serviceById.php?id=".$id);

    if ($response->successful() && $response->json()) {
        $service = $response->json();

        $response = Http::get($this->base_url . "/api/plan_controller.php");
        if ($response->successful() && $response->json()) {
            $allPlans = $response->json();

            $plansForService = array_filter($allPlans, function($plan) use ($id) {
                return isset($plan['id_service']) && $plan['id_service'] == $id;
            });
            $plansForService = array_values($plansForService);

            // 🔹 Charger les avantages
            $avantagesResponse = Http::get($this->base_url . "/api/controller/avantage/avantageController.php");
            $avantages = $avantagesResponse->successful() ? $avantagesResponse->json() : [];

            // 🔹 Grouper les avantages par plan_id
            $avantagesParPlan = [];
            foreach ($avantages as $av) {
                $planId = $av['plan_id'];
                if (!isset($avantagesParPlan[$planId])) {
                    $avantagesParPlan[$planId] = [];
                }
                $avantagesParPlan[$planId][] = $av['avantage'];
            }

            return view('details', compact('service', 'plansForService', 'avantagesParPlan'));
        }
    }

    abort(404, 'Service introuvable.');
}

    }
