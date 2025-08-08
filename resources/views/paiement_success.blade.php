@extends('Layouts.app')

@section('content')
<div class="page-header text-center">
    <div class="container">
        <h1 class="page-title">Paiement <span>Succès</span></h1>
    </div>
</div>

<div class="container pt-5 pb-5">
    @if (Session::has('success'))
        <div class="alert alert-success text-center" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="text-center">
        <h2 class="mb-4">🎉 Merci pour votre confiance !</h2>
        <p class="lead">
            Votre abonnement a été enregistré avec succès.
        </p>
        <p>
            Vous pouvez désormais profiter de toutes les fonctionnalités liées à votre plan.
        </p>
        <a href="{{ url('/') }}" class="btn btn-outline-primary mt-4">Retour à l'accueil</a>
    </div>
</div>
@endsection
