@extends('Layouts.app')

@section('content')
<div class="container text-center pt-5 pb-5">
    <img src="{{ asset('assets/images/logo-footer.png') }}" alt="SubSure" width="150" class="mb-4">

    <div class="alert alert-success shadow p-4 rounded" role="alert">
        <h1 class="display-5"> Paiement Réussi !</h1>
        <p class="lead mt-3">.
        </p>
    </div>

    <a href="{{ route('abonnement') }}" class="btn btn-primary mt-4">
        Voir mes abonnements
    </a>
</div>
@endsection
