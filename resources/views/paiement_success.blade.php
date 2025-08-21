@extends('Layouts.app')

@section('content')
<div class="container text-center pt-5 pb-5">

    {{-- Logo SubSure --}}
    {{-- <img src="{{ asset('assets/images/logo-footer.png') }}" alt="SubSure" width="150" class="mb-4"> --}}

    {{-- Message d'alerte --}}
    @if (Session::has('success'))
        <div class="alert alert-success shadow p-4 rounded" role="alert">
            <h1 class="display-5 mb-3">🎉 En attente de paiement</h1>
            <p class="lead mb-2">Merci pour votre confiance !</p>
            <p>Votre abonnement a été enregistré avec succès.</p>
            <p>Vous pouvez désormais procéder au paiement ou annuler.</p>
        </div>
    @else
        <div class="alert alert-warning shadow p-4 rounded" role="alert">
            <h1 class="display-5 mb-3">⚠️ Paiement en attente</h1>
            <p class="lead mb-2">Votre abonnement est en attente de validation.</p>
            <p>Merci de finaliser votre paiement pour activer votre service.</p>
        </div>
    @endif
@php
    $client =Session::get('user');
@endphp
    {{-- Boutons --}}
    <div class="mt-4">
        <a href="{{ url('/clients/dashboard') }}" class="btn btn-outline-primary me-3 px-4 py-2">Tableau de Bord</a>
        <a href="{{ route('abonnement',$client['ID_']) }}" class="btn btn-primary px-4 py-2">Voir mes abonnements</a>
    </div>
</div>
@endsection
