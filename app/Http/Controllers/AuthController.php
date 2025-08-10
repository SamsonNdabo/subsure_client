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

                if (empty($client['email_verified_at'])) {
                    return redirect('/logReg')->with('error', 'Vous devez vérifier votre email avant de vous connecter.');
                }

                Session::put('user', $client);
                Session::flash('success', 'Connexion réussie !');
                return redirect('clients/dashboard')->with('success', 'Bienvenue !');
            }

            return redirect('/logReg')->with('error', $data['message'] ?? 'Identifiants incorrects.');
        }

        if ($response->clientError()) {
            return redirect('/logReg')->with('error', $data['message'] ?? 'Identifiants incorrects.');
        }

        if ($response->serverError()) {
            return redirect('/logReg')->with('error', 'Erreur interne du serveur distant.');
        }

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

            return back()->with('error', 'Impossible de récupérer les données du tableau de bord.');
        } catch (\Exception $e) {
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
            $entrepriseId = 0;

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
                // Corrigé ici: data est un tableau associatif, pas un indexé
                $user = $data['data'] ?? ['email' => $request->email, 'nom' => $request->nom, 'idclient' => null];

                $verificationUrl = URL::temporarySignedRoute(
                    'verification.verify',
                    now()->addMinutes(60),
                    ['id' => $user['idclient'], 'hash' => sha1($user['email'])]
                );

                Mail::to($user['email'])->send(new RegisterConfirmationMail($user, $verificationUrl));

                return back()->with('success', 'Inscription réussie ! Vérifiez votre email pour confirmer votre compte.');
            }

            return back()->with('error', 'Inscription échouée ! Email déjà utilisé.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Lien de vérification invalide ou expiré.');
        }

        $response = Http::asForm()->post($this->base_url . "/api/Mobile/verifyemail.php", [
            'idclient' => $id,
            'hash' => $hash,
        ]);

        Log::info('API verifyemail response:', $response->json());

        $data = $response->json();

        if ($response->successful() && isset($data['status']) && $data['status'] === 'success') {
            return redirect()->route('verification.success')->with('success', 'Email vérifié avec succès, vous pouvez maintenant vous connecter.');
        } else {
            return redirect('/logReg')->with('error', $data['message'] ?? 'Échec de la vérification de l\'email.');
        }
    }
public function showVerified()
{
    return view('emails.verified');
}


    // ... Méthodes forgotPassword et resetPassword inchangées ...
}
