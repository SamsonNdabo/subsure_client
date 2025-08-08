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

    public function profil($id)
    {
        $id = Session::get('user');

        if (!$id || !isset($id['ID_'])) {
            return redirect('/logReg')->with('warning', 'Session expirée. Veuillez vous reconnecter.');
        }

        try {
            $response = Http::get("{$this->base_url}/api/clients.php?id_client=" . $id['ID_']);
             $data['profil'] = $response->successful() ? $response->json() : [];
        //  dd($data);
        return view('clients/MonProfil', $data);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la récupération du profil : ' . $e->getMessage());
        }
    }
}
