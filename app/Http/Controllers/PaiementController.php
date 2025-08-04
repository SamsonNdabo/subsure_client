<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // <-- Assure-toi que ce package est installé

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
        $response = Http::get($this->base_url . "/api/controller/paiement/get_facture_by_id.php?id_paiement=" . $id);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['paiement'][0])) {
                $paiement = $data['paiement'][0];
                $pdf = Pdf::loadView('pdf.facture', compact('paiement'));

                return $pdf->stream('facture_' . $paiement['transaction_id'] . '.pdf');
            }
        }

        return redirect()->back()->with('error', 'Impossible de générer la facture.');
    }
}
