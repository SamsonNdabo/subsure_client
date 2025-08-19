@extends('Layouts.app')

@section('content')
<div class="container text-center pt-5 pb-5">
    <img src="{{ asset('assets/images/logo-footer.png') }}" alt="SubSure" width="150" class="mb-4">

    <div class="alert alert-danger shadow p-4 rounded" role="alert">
        <h1 class="display-5">❌ Paiement Annulé</h1>
        <p class="lead mt-3">
            Vous avez annulé le processus de paiement. Aucun abonnement n’a été activé.
        </p>
    </div>

    <a href="{{ url('clients/MesServices') }}" class="btn btn-outline-primary mt-4">
        Revenir aux services
    </a>
</div>
@endsection
