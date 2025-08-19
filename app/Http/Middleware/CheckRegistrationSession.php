<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckRegistrationSession
{
    /**
     * Gérer une requête entrante.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('registration_user')) {
            return redirect('/register')->withErrors([
                'access' => 'Vous devez compléter l’inscription pour accéder à cette page.'
            ]);
        }
        return $next($request);
    }
}
