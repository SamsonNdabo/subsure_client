<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Mail\RegisterConfirmationMail;
use App\Mail\ResetLinkMail;
use App\Mail\ResetSuccessMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$this->base_url}/api/connexion/clientLog.php", $credentials);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['status']) && $data['status'] === 'success' && isset($data['data'][0])) {
                $client = $data['data'][0];

                Session::put('user', $client);
                Session::flash('success', 'Connexion réussie !');
                return redirect('clients/dashboard')->with('success', 'Bienvenue !');
            }

            // // Erreur retournée par l'API (ex: mauvais identifiants)
            // return redirect('/logReg')->with('error', $data['message'] ?? 'Identifiants incorrects.');
        }

        // Erreur côté client HTTP (ex: mauvaise requête)
        if ($response->clientError()) {
            return redirect('/logReg')->with('error', $data['message'] ?? 'Identifiants incorrects.');
        }

        // Erreur serveur distant
        if ($response->serverError()) {
            return redirect('/logReg')->with('error', 'Erreur interne du serveur distant.');
        }

        // Aucune réponse
        return redirect('/logReg')->with('error', 'Impossible de contacter le serveur distant.');
    }
    public function dashboard()
    {
        $client = Session::get('user');

        if (!$client || !isset($client['ID_'])) {
            return redirect('/logReg')->with('warning', 'Session expirée. Veuillez vous reconnecter.');
        }

        try {
            $response = Http::get("{$this->base_url}/api/dahsboard.php?idclient=" . $client['ID_']);

            if ($response->successful()) {
                $stats = $response->json();

                return view('clients/dashboard', [
                    'client' => $client,
                    'stats' => $stats,
                ]);
            }

            // API a renvoyé une réponse non exploitable
            return back()->with('error', 'Impossible de récupérer les données du tableau de bord.');
        } catch (\Exception $e) {
            // Erreur pendant la requête (timeout, DNS, etc.)
            return back()->with('error', 'Erreur API : ' . $e->getMessage());
        }
    }

    public function showRegisterForm()
    {
        return view('register');
    }
    public function showForgotForm()
    {
        return view('forgot');
    }
    public function showResetForm($token)
    {
        return view('reset', compact('token'));
    }

    /** Inscription **/
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        try {
            $entrepriseId = 0; // Ajuster si nécessaire

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->base_url . "/api/Mobile/registerclient.php?entreprise_id=$entrepriseId", [
                'nom' => $request->nom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'adresse' => $request->adresse,
                'password' => $request->password,
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] === 'success') {
                Mail::to($request->email)->send(new RegisterConfirmationMail($request->nom));
                return back()->with('success', 'Inscription réussie ! Vérifiez vos emails.');
            }

            return back()->withErrors(['error' => $data['message'] ?? 'Erreur lors de l’inscription.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }

    /** Mot de passe oublié **/
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $response = Http::post($this->base_url . "/api/Mobile/forgetpassword.php", [
                'email' => $request->email
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['success']) && $data['success']) {
                Mail::to($request->email)->send(new ResetLinkMail($data['token']));
                return redirect()->back()->with('success', 'Lien de réinitialisation envoyé.');
            }

            return back()->withErrors(['email' => $data['message'] ?? 'Email introuvable.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }

    /** Réinitialisation **/
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        try {
            $response = Http::post($this->base_url . "/api/Mobile/resetpassword.php", [
                'token' => $request->token,
                'password' => $request->password
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['success']) && $data['success']) {
                Mail::to($data['email'])->send(new ResetSuccessMail());
                return redirect()->route('Login')->with('success', 'Mot de passe réinitialisé.');
            }

            return back()->withErrors(['token' => $data['message'] ?? 'Lien invalide ou expiré.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }
}
