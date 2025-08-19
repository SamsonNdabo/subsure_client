<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AvantageController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }
    public function listavantages($id)
    {
        try {
            $response = Http::timeout(5) // Limite le temps d'attente à 5 secondes
                ->get($this->base_url . "/api/controller/avantage/avantageController.php?plan_id=" . $id);

            // Vérifie si l'appel a réussi et que la réponse contient bien du JSON
            if ($response->successful() && $response->json()) {
                $avantage = $response->json();
                return view('details', $avantage);
            } else {
                // Si l'API répond mais avec un statut d'erreur (404, 500...)
                return abort(404, 'Impossible de récupérer les avantages pour ce plan.');
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Erreur de connexion à l’API (DNS, serveur down, etc.)
            return abort(500, 'Service API indisponible. Veuillez réessayer plus tard.');
        } catch (\Exception $e) {
            // Toute autre erreur inattendue
            return abort(500, 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
