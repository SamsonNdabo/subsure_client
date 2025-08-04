<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServicesController extends Controller
{
    protected $base_url;

    public function _contruct(){
        $this->base_url =  env('API_BASE_URL');   
    }

    public function list(){
        $response = Http::get('http://localhost/gestion_abonnements_api/api/services.php');
        $data['services'] = $response->successful() ? $response->json() : [];

        return view('home_', $data);
    }
}
