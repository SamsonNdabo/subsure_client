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
        $response = Http::get($this->base_url . '/api/services.php');
        $services = $response->successful() ? $response->json() : [];

        return view('nos_services', ['services' => $services]);
    }
}
