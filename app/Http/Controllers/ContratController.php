<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContratController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    public function listcontrat($id)
    {
        $response = Http::get("{$this->base_url}/api/controller/contrat/get_contrat_by_user.php?id_client={$id}");
        $data['contrat'] = $response->successful() ? $response->json() : [];
        // dd($data);
        return view('clients/MesContrat', $data);
    }

    public function telechargerPDF($id)
{
    // Appel API distante
    $response = Http::get(env('API_BASE_URL') . '/api/contrat.php?where id='.$id);
//TODO//
    if ($response->successful() && $response->json()) {
        $contrat = $response->json();

        // Génére le PDF depuis la vue dédiée
        $pdf = Pdf::loadView('pdf.contrat', ['contrat' => $contrat]);

        return $pdf->download('contrat'.$id.'.pdf');
    }

    return redirect()->back()->with('error', 'Contrat introuvable.');
}
}
