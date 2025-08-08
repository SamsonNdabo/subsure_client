@extends('Layouts.app')

@section('content')
    {{-- En-tête de page --}}
    <div class="page-header text-center bg-light py-5 shadow-sm rounded-4">
        <div class="container">
            @php
                $client = Session::get('user');
            @endphp
            <h1 class="page-title mb-0">Bienvenue <strong class="text-primary">{{ $client['nom'] }}</strong></h1>
            <p class="text-muted">Mes Transactions</p>
        </div>
    </div>

    <div class="container pt-5 pb-5">
        {{-- Menu latéral --}}
        @include('clients.navigation')

        <div class="row">
            @forelse ($paiement as $index => $item)
                <div class="col-md-6">
                    <div class="card mb-4 shadow rounded-4 border-0">
                        <div class="card-body">
                            {{-- Référence transaction --}}
                            <h5 class="card-title fw-bold text-dark mb-3">
                                Transaction #{{ $item['transaction_id'] }}
                            </h5>

                            {{-- Montant & statut --}}
                            <div class="mb-3">
                                <span class="fs-5 fw-semibold text-success">
                                    {{ number_format($item['montant'], 2) }} $
                                </span>
                                <span class="badge rounded-pill bg-{{ $item['statut'] === 'success' ? 'success' : 'danger' }} ms-2">
                                    <i class="bi {{ $item['statut'] === 'success' ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                                    {{ ucfirst($item['statut']) }}
                                </span>
                            </div>

                            {{-- Infos principales --}}
                            <p class="mb-1"><strong>Date :</strong> {{ \Carbon\Carbon::parse($item['date_paiement'])->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><strong>Méthode :</strong> {{ ucfirst($item['type_paiement']) }}</p>

                            {{-- Boutons --}}
                            <div class="d-flex justify-content-between mt-4">
                                <button class="btn btn-outline-primary btn-sm px-3" data-bs-toggle="modal"
                                    data-bs-target="#paiementModal{{ $index }}">
                                    Voir détails
                                </button>
                                <a href="{{ route('paiement.facture', $item['id_paiement']) }}" target="_blank"
                                    class="btn btn-primary btn-sm px-3">
                                    Télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Détails --}}
                <div class="modal fade" id="paiementModal{{ $index }}" tabindex="-1"
                    aria-labelledby="paiementModalLabel{{ $index }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content rounded-4 shadow-sm border-0">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold" id="paiementModalLabel{{ $index }}">
                                    Détails de la Transaction
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Nom :</strong> {{ $item['nom'] }}</li>
                                    <li class="list-group-item"><strong>Email :</strong> {{ $item['email'] }}</li>
                                    <li class="list-group-item"><strong>Téléphone :</strong> {{ $item['telephone'] }}</li>
                                    <li class="list-group-item"><strong>Adresse :</strong> {{ $item['adresse'] }}</li>
                                    <li class="list-group-item"><strong>ID Abonnement :</strong> {{ $item['id_abonnement'] }}</li>
                                    <li class="list-group-item"><strong>Montant :</strong> {{ number_format($item['montant'], 2) }} $</li>
                                    <li class="list-group-item"><strong>Date :</strong> {{ \Carbon\Carbon::parse($item['date_paiement'])->format('d/m/Y H:i') }}</li>
                                    <li class="list-group-item"><strong>Type :</strong> {{ ucfirst($item['type_paiement']) }}</li>
                                    <li class="list-group-item"><strong>Transaction ID :</strong> {{ $item['transaction_id'] }}</li>
                                    <li class="list-group-item"><strong>Statut :</strong> {{ ucfirst($item['statut']) }}</li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('paiement.facture', $item['id_paiement']) }}" target="_blank"
                                    class="btn btn-primary">
                                    Télécharger Facture
                                </a>
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-warning rounded-4 shadow-sm">
                        Aucune transaction disponible.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
