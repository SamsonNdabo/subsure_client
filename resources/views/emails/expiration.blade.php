{{-- resources/views/emails/expiration.blade.php --}}
<div class="header"
    style="background-color: #2a9d8f; padding: 30px 20px 20px 20px; text-align: center; color: #fff; font-size: 28px; font-weight: 700; letter-spacing: 1px;">
    <img src="http://localhost/subsure.com/assets/images/logo_message.png" alt="Logo SubSure"
        style="max-width: 120px; margin-bottom: 15px;" />
    En attente de paiement de l'abonnement
</div>
@php
    $dateFin = \Carbon\Carbon::parse($abonnement['date_fin']);
    $dateDebut = \Carbon\Carbon::parse($abonnement['date_debut']);
    $now = \Carbon\Carbon::now();
    $estExpire = $abonnement['statut'] === 'expire' && $now->gt($dateFin);
@endphp

@component('mail::message')
# {{ $estExpire ? 'Votre abonnement a expiré' : 'Rappel d\'expiration de votre abonnement' }}

Bonjour {{ $abonnement['Nom_Client'] ?? 'Client' }},

@if($estExpire)
    Votre abonnement au service **{{ $abonnement['Nom_Service'] }}** a expiré depuis le **{{ $dateFin->format('d/m/Y') }}**.
@else
    Nous vous rappelons que votre abonnement au service **{{ $abonnement['Nom_Service'] }}** arrive à expiration
    **{{ $joursRestants == 0 ? "aujourd’hui" : "dans $joursRestants jours" }}** (le {{ $dateFin->format('d/m/Y') }}).
@endif

@component('mail::panel')
**Statut actuel :** {{ $abonnement['statut'] }}
**Service :** {{ $abonnement['Nom_Service'] }}
**Date de début :** {{ $dateDebut->format('d/m/Y') }}
**Date de fin :** {{ $dateFin->format('d/m/Y') }}
@endcomponent

@if($estExpire)
    @component('mail::button', [
        'url' => route('stripe.checkout', [
            'client_id' => $abonnement['idclient'],
            'plan_id' => $abonnement['id_plan'],
            'abonnement_id' => $abonnement['id'],
            'service_id' => $abonnement['id_service'],
            'prix' => $abonnement['prix'],
            'email' => $abonnement['email'],
        ])
    ])
    🔁 Renouveler maintenant
    @endcomponent
@endif

Merci de votre confiance.
L’équipe **SubSure**

---

Pour toute question, vous pouvez nous contacter à
[**support@subsure.com**](mailto:support@subsure.com)
ou visiter notre site : [www.subsure.com](http://localhost/subsure.com/)

@slot('subcopy')
En renouvelant votre abonnement, vous acceptez nos [Conditions générales](http://localhost/subsure.com/).
@endslot

@endcomponent