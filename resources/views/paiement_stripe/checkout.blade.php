@extends('Layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Confirmation de paiement</h2>

    <div class="card p-4">
        <h4>Plan : {{ $abonnement->plan_name }}</h4>
        <p>Prix : <strong>{{ $abonnement->price }} FCFA</strong></p>

        <form action="{{ route('stripe.session') }}" method="">
            @csrf
            <input type="hidden" name="abonnement_id" value="">
            <button type="submit" class="btn btn-success mt-3">Payer avec Stripe</button>
        </form>
    </div>
</div>
@endsection