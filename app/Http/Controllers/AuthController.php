<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Mail\RegisterConfirmationMail;
use App\Mail\ResetLinkMail;
use App\Mail\ResetSuccessMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $base_url;

    public function __construct()
    {
        $this->base_url = env('API_BASE_URL');
    }

    /** -----------------------
     * Connexion utilisateur
     * ----------------------*/
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            $response = Http::timeout(5) // délai max 5 sec
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->base_url}/api/connexion/clientLog.php", $credentials);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] === 'success' && isset($data['data'][0])) {
                    $client = $data['data'][0];

                    if (empty($client['email_verified_at'])) {
                        return redirect('/logReg')->with('error', 'Vous devez vérifier votre email avant de vous connecter.');
                    }

                    Session::put('user', $client);
                    return redirect('clients/dashboard')->with('success', 'Bienvenue !');
                }

                return redirect('/logReg')->with('error', $data['message'] ?? 'Identifiants incorrects.');
            }

            if ($response->clientError()) {
                return redirect('/logReg')->with('error', 'Identifiants incorrects.');
            }

            if ($response->serverError()) {
                return redirect('/logReg')->with('error', 'Erreur interne du serveur distant.');
            }

            return redirect('/logReg')->with('error', 'Réponse inattendue du serveur.');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect('/logReg')->with('error', 'Impossible de contacter le serveur distant. Vérifiez votre connexion internet.');
        } catch (\Exception $e) {
            return redirect('/logReg')->with('error', 'Erreur interne : ' . $e->getMessage());
        }
    }

    /** -----------------------
     * Dashboard client
     * ----------------------*/
    public function dashboard()
    {
        $client = Session::get('user');

        if (!$client || !isset($client['ID_'])) {
            return redirect('/logReg')->with('warning', 'Session expirée. Veuillez vous reconnecter.');
        }

        try {
            $response = Http::timeout(5)
                ->get("{$this->base_url}/api/dahsboard.php?idclient=" . $client['ID_']);

            if ($response->successful()) {
                return view('clients/dashboard', [
                    'client' => $client,
                    'stats' => $response->json(),
                ]);
            }

            return back()->with('error', 'Impossible de récupérer les données du tableau de bord.');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', 'API distante inaccessible.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur API : ' . $e->getMessage());
        }
    }

    /** -----------------------
     * Formulaires simples
     * ----------------------*/
    public function showRegisterForm() { return view('register'); }
    public function showForgotForm() { return view('forgot'); }
    public function showResetForm($token) { return view('reset', compact('token')); }

    /** -----------------------
     * Inscription
     * ----------------------*/
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
            $entrepriseId = 0;

            $response = Http::timeout(5)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->base_url . "/api/Mobile/registerclient.php?entreprise_id=$entrepriseId", [
                    'nom' => $request->nom,
                    'email' => $request->email,
                    'telephone' => $request->telephone,
                    'adresse' => $request->adresse,
                    'password' => $request->password,
                ]);

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? '') === 'success') {
                $user = $data['data'] ?? ['email' => $request->email, 'nom' => $request->nom, 'idclient' => null];

                $verificationUrl = URL::temporarySignedRoute(
                    'verification.verify',
                    now()->addMinutes(60),
                    ['id' => $user['idclient'], 'hash' => sha1($user['email'])]
                );

                Mail::to($user['email'])->send(new RegisterConfirmationMail($user, $verificationUrl));

                return back()->with('success', 'Inscription réussie ! Vérifiez votre email.');
            }

            return back()->with('error', 'Inscription échouée ! Email déjà utilisé.');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', 'Impossible de contacter le serveur distant.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur serveur : ' . $e->getMessage());
        }
    }

    /** -----------------------
     * Vérification email
     * ----------------------*/
    public function verifyEmail(Request $request, $id, $hash)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Lien de vérification invalide ou expiré.');
        }

        try {
            $response = Http::timeout(5)
                ->asForm()
                ->post($this->base_url . "/api/Mobile/verifyemail.php", [
                    'idclient' => $id,
                    'hash' => $hash,
                ]);

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? '') === 'success') {
                return redirect()->route('verification.success')
                    ->with('success', 'Email vérifié avec succès.');
            }

            return redirect('/logReg')->with('error', $data['message'] ?? 'Échec de la vérification.');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect('/logReg')->with('error', 'API distante inaccessible.');
        } catch (\Exception $e) {
            return redirect('/logReg')->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function showVerified()
    {
        return view('emails.verified');
    }

    /** -----------------------
     * Mot de passe oublié
     * ----------------------*/
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $response = Http::timeout(10)
                ->post($this->base_url . "/api/Mobile/forgetpassword.php", [
                    'email' => $request->email
                ]);

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? '') === 'success') {
                $token = $data['data']['token'] ?? null;
                $email = $data['data']['email'] ?? $request->email;

                if (!$token) {
                    return back()->with('error', 'Token manquant.');
                }

                Mail::to($email)->send(new ResetLinkMail($token));

                return back()->with('success', 'Lien envoyé. Vérifiez vos emails.');
            }

            return back()->with('error', $data['message'] ?? 'Email introuvable.');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', 'Impossible de contacter l’API.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /** -----------------------
     * Réinitialisation mot de passe
     * ----------------------*/
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        try {
            $response = Http::timeout(5)
                ->post($this->base_url . "/api/Mobile/resetpassword.php", [
                    'token' => $request->token,
                    'password' => $request->password
                ]);

            $data = $response->json();
            // dd($data); // Debugging line to inspect the response

            if ($response->successful() && ($data['status'] ?? '') === 'success') {
                $email = $data['data']['email'] ?? null;

                if (!$email) {
                    return back()->withErrors(['error' => 'Email manquant dans la réponse serveur.']);
                }

                Mail::to($email)->send(new ResetSuccessMail());

                return redirect()->route('Login')->with('success', 'Mot de passe réinitialisé.');
            }

            return back()->withErrors(['token' => $data['message'] ?? 'Lien invalide ou expiré.']);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', 'API distante inaccessible.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}
