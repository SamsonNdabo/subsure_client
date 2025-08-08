<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; 

class PaiementController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    public function listpaiement($id)
    {
        $response = Http::get($this->base_url . "/api/controller/paiement/get_paiement_by_client.php?idclient=" . $id);
        $data['paiement'] = $response->successful() ? $response->json() : [];
        return view('clients/MesTransact', $data);
    }

    public function facture($id)
    {
        $response = Http::get($this->base_url . "/api/controller/paiement/get_paiement_by_client.php?idclient=" . $id);

        if ($response->successful()) {
                $paiement = $ $response->json();
                $pdf = Pdf::loadView('clients/facture', ['contrat' => $paiement]);
                // Génére le PDF depuis la vue dédiée
                return $pdf->download('facture' . $id . '.pdf');
            }
        return redirect()->back()->with('error', 'Impossible de générer la facture.');
    }

}