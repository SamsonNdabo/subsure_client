<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckSession
{
    /**
     * Gérer une requête entrante.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user')) {
            return redirect('/logReg')->withErrors([
                'access' => 'Vous devez être connecté pour accéder à cette page.'
            ]);
        }
        return $next($request);
    }
}
