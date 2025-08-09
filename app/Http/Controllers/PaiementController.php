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
        // dd($response->json());
        $data['paiement'] = $response->successful() ? $response->json() : [];
        return view('clients/MesTransact', $data);
    }

    public function facture($id)
    {
        $response = Http::get($this->base_url . "/api/controller/paiement/get_paiement_by_client.php?idclient=" . $id);
    
        if ($response->successful()) {
            $paiement = $response->json();
    
            if (empty($paiement)) {
                return redirect()->back()->with('error', "Aucun paiement trouvé pour ce client.");
            }
    
            $pdf = Pdf::loadView('clients.facture', ['paiement' => $paiement]);
            return $pdf->download('facture_' . $id . '.pdf');
        }
    
        return redirect()->back()->with('error', 'Impossible de générer la facture.');
    }
    
    
}