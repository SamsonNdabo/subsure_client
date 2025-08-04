<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$this->base_url}/api/connexion/clientLog.php", $credentials);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['status']) && $data['status'] === 'success' && isset($data['data'][0])) {
                $client = $data['data'][0];

                Session::put('user', $client);
                Session::flash('success', 'Connexion réussie !');
                return redirect('clients/dashboard')->with('success', 'Bienvenue !');

            }

            // Erreur retournée par l'API (ex: mauvais identifiants)
            return redirect('/logReg')->with('error', $data['message'] ?? 'Identifiants incorrects.');
        }

        // Erreur côté client HTTP (ex: mauvaise requête)
        if ($response->clientError()) {
            return redirect('/logReg')->with('error', 'Erreur client lors de la requête à l’API.');
        }

        // Erreur serveur distant
        if ($response->serverError()) {
            return redirect('/logReg')->with('error', 'Erreur interne du serveur distant.');
        }

        // Aucune réponse
        return redirect('/logReg')->with('error', 'Impossible de contacter le serveur distant.');
    }
    public function dashboard()
    {
        $client = Session::get('user');

        if (!$client || !isset($client['ID_'])) {
            return redirect('/logReg')->with('warning', 'Session expirée. Veuillez vous reconnecter.');
        }

        try {
            $response = Http::get("{$this->base_url}/api/dahsboard.php?idclient=" . $client['ID_']);

            if ($response->successful()) {
                $stats = $response->json();

                return view('clients/dashboard', [
                    'client' => $client,
                    'stats' => $stats,
                ]);
            }

            // API a renvoyé une réponse non exploitable
            return back()->with('error', 'Impossible de récupérer les données du tableau de bord.');
        } catch (\Exception $e) {
            // Erreur pendant la requête (timeout, DNS, etc.)
            return back()->with('error', 'Erreur API : ' . $e->getMessage());
        }
    }
}
