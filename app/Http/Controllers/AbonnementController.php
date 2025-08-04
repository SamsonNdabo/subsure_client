<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;


use Illuminate\Http\Request;

class AbonnementController extends Controller
{

    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }
    public function listabonnement($id)
    {
        $response = Http::get($this->base_url . "/api/Mobile/abonnementSuscrit.php?ID_=" . $id);
        $data['abonnement'] = $response->successful() ? $response->json() : [];
        // dd($data);
        return view('clients/MesServices', $data);
    } 
}
