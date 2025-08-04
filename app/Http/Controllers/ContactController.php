<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ContactController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }
    public function contactEts($id)
    {
        $response = Http::get($this->base_url . "/api/controller/avantage/avantageController.php?plan_id=" . $id);

        if ($response->successful() && $response->json()) {
            $contact = $response->json();

            return view('details', $contact);
        }

        abort(404, 'Avantage introuvable.');
    }
}
