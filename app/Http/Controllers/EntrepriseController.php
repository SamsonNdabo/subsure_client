<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    
    protected $base_url;

    public function _contruct(){
        $this->base_url =  env('API_BASE_URL');   
    }

    public function EntrepriseInfo($id)
    {
       
    }
}
