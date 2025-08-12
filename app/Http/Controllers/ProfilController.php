<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProfilController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ]);

        // Préparer la charge utile (payload) à envoyer à l'API
        $payload = [
            'idclient' => $id,
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
        ];

        // Envoi POST JSON à l'API updateclient.php
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($this->base_url . '/api/Mobile/updateclient.php', $payload);
// dd([
//     'status_code' => $response->status(),
//     'body' => $response->body(),
//     'json' => $response->json()
// ]);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['status']) && $data['status'] === 'success') {
                // Mettre à jour les données dans la session pour refléter les modifications immédiatement
                $client = session('user');
                $client['nom'] = $validated['nom'];
                $client['email'] = $validated['email'];
                $client['telephone'] = $validated['telephone'] ?? null;
                $client['adresse'] = $validated['adresse'] ?? null;
                session(['user' => $client]);

                return back()->with('success', $data['message'] ?? 'Profil mis à jour avec succès');
            } else {
                return back()->with('error', $data['message'] ?? 'Erreur lors de la mise à jour');
            }
        }

        return back()->with('error', 'Erreur serveur lors de la mise à jour');
    }
}
