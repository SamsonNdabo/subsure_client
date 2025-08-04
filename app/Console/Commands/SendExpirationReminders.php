<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\ExpirationReminder;
use Illuminate\Support\Facades\Log;

class SendExpirationReminders extends Command
{
    protected $signature = 'app:send-expiration-reminders';
    protected $description = 'Envoie des rappels d’expiration d’abonnement';

    protected $base_url;

    public function __construct()
    {
        parent::__construct();
        $this->base_url = env('API_BASE_URL');
    }

    public function handle()
    {
        $this->info('Envoi des rappels d’expiration en cours...');

        $response = Http::get($this->base_url . '/api/Mobile/abonnements.php');

        if ($response->successful()) {
            $abonnements = $response->json();
            Log::info('Abonnements récupérés :', ['data' => $abonnements]);
        } else {
            Log::error('Erreur API : ' . $response->status());
            Log::error('Contenu brut : ' . $response->body());
            $this->error('Erreur de récupération des abonnements.');
            return;
        }

        foreach ($abonnements as $abonnement) {
            if (empty($abonnement['date_fin']) || empty($abonnement['email'])) continue;

            $dateFin = Carbon::parse($abonnement['date_fin']);
            $joursRestants = now()->diffInDays($dateFin, false);

            if (in_array($joursRestants, [7, 6, 5, 4, 3, 2, 1,0])) {
                Mail::to($abonnement['email'])->send(new ExpirationReminder($abonnement, $joursRestants));
                $this->info("Mail envoyé à {$abonnement['email']} pour expiration dans {$joursRestants} jour(s).");
            }
        }

        $this->info('Tous les mails de rappel ont été traités.');
    }
}
