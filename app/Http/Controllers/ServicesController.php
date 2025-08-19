<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServicesController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }


    public function services()
    {
        try {
            $response = Http::get($this->base_url . '/api/services.php');
            $services = $response->successful() ? $response->json() : [];

            return view('nos_services', ['services' => $services]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', 'API distante inaccessible. Vérifiez votre connexion internet.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur interne : ' . $e->getMessage());
        }
    }
    public function search(Request $request)
    {
        $query = $request->input('q');

        // Appel API distante pour récupérer les services correspondants
        $response = Http::get($this->base_url . "/controller/service/search.php", [
            'q' => $query
        ]);

        $services = $response->json();

        return view('services.search', [
            'services' => $services,
            'query' => $query
        ]);
    }
}
