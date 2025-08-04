@extends('Layouts.app')

@section('content')
<div class="container py-5">
    <div class="alert alert-success text-center shadow rounded-4">
        <h2>Paiement réussi 🎉</h2>
        <p>Merci pour votre paiement.</p>

        @if(isset($message))
            <p>{{ $message }}</p>
        @endif

        <hr>

        <h4>Détails du paiement :</h4>
        <ul class="list-unstyled">
            <li><strong>Abonnement ID :</strong> {{ request()->input('abonnement_id') }}</li>
            <li><strong>Prix payé :</strong> ${{ number_format(request()->input('prix'), 2) }}</li>
            <li><strong>Email :</strong> {{ request()->input('email') }}</li>
        </ul>

        <a href="{{ url('clients/dashboard') }}" class="btn btn-primary mt-3">Retour au tableau de bord</a>
    </div>
</div>
@endsection
