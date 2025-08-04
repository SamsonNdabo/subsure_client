@extends('Layouts.app')

@section('content')
<main class="main">
    <div class="page-content text-center pt-5 pb-5">
        <div class="container">
            <h2 class="text-danger">❌ Paiement annulé</h2>
            <p>Votre paiement a été annulé. Vous pouvez réessayer à tout moment.</p>
            {{-- <a href="{{ route('checkout.page') }}" class="btn btn-outline-secondary mt-3">Retour au paiement</a> --}}
        </div>
    </div>
</main>
@endsection