<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContratController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    public function listcontrat($id)
    {
        $data['contrat'] = [];

        try {
            $response = Http::timeout(5)
                ->get("{$this->base_url}/api/controller/contrat/get_contrat_by_client.php?id_client={$id}");

            if ($response->successful() && $response->json()) {
                $data['contrat'] = $response->json();
            } else {
                Log::warning("API contrat inaccessible pour client {$id}, status: " . $response->status());
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("Connexion API contrat échouée pour client {$id} : " . $e->getMessage());
            return abort(500, 'Service des contrats indisponible. Veuillez réessayer plus tard.');
        } catch (\Exception $e) {
            Log::error("Erreur récupération contrat pour client {$id} : " . $e->getMessage());
            return abort(500, 'Une erreur interne est survenue.');
        }

        return view('clients/MesContrat', $data);
    }

    public function telechargerPDF($id)
    {
        try {
            $response = Http::timeout(5)
                ->get("{$this->base_url}/api/controller/contrat/get_contrat_by_client.php?id_client={$id}");
            // dd($response->json());
            if ($response->successful() && $response->json()) {
                $contrat = $response->json();

                // Génére le PDF depuis la vue dédiée
                $pdf = Pdf::loadView('clients/contrat', ['contrat' => $contrat]);

                return $pdf->download('contrat' . $id . '.pdf');
            } else {
                Log::warning("Contrat non trouvé pour client {$id}, status: " . $response->status());
                return redirect()->back()->with('error', 'Contrat introuvable.');
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("Connexion API contrat échouée pour téléchargement PDF client {$id} : " . $e->getMessage());
            return redirect()->back()->with('error', 'Impossible de se connecter au service des contrats.');
        } catch (\Exception $e) {
            Log::error("Erreur lors de la génération du PDF du contrat {$id} : " . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur interne est survenue.');
        }
    }
}
