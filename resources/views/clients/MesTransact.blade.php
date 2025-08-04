@extends('Layouts.app')

@section('content')
    <div class="page-header text-center">
        <div class="container">
            <h1 class="page-title">Mes Transactions</h1>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            @forelse ($paiement as $index => $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card bg-white border-0 shadow-lg rounded-4 position-relative">
                        <div class="card-body p-4">
                            <h5 class="card-title text-dark fw-bold mb-3">
                                #{{ $item['transaction_id'] }}
                            </h5>
                            <p class="fs-5 mb-1 text-success"><strong> Montant :</strong> {{ $item['montant'] }} $</p>
                            <p class="mb-1"><strong> Date :</strong> {{ \Carbon\Carbon::parse($item['date_paiement'])->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><strong> Méthode :</strong> {{ ucfirst($item['type_paiement']) }}</p>
                            <p class="mb-2">
                                <strong> Statut :</strong>
                                <span class="badge rounded-pill bg-{{ $item['statut'] === 'success' ? 'success' : 'danger' }}">
                                    <i class="bi {{ $item['statut'] === 'success' ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                                    {{ ucfirst($item['statut']) }}
                                </span>
                            </p>

                            <div class="d-flex justify-content-between mt-4">
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#paiementModal{{ $index }}">
                                    🔍 Détails
                                </button>
                                <a href="{{ route('paiement.facture', $item['id_paiement']) }}" target="_blank" class="btn btn-primary btn-sm">
                                    🧾 Télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="paiementModal{{ $index }}" tabindex="-1" aria-labelledby="paiementModalLabel{{ $index }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content rounded-4">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paiementModalLabel{{ $index }}">Détails de la Transaction</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Nom :</strong> {{ $item['nom'] }}</li>
                                    <li class="list-group-item"><strong>Email :</strong> {{ $item['email'] }}</li>
                                    <li class="list-group-item"><strong>Téléphone :</strong> {{ $item['telephone'] }}</li>
                                    <li class="list-group-item"><strong>Adresse :</strong> {{ $item['adresse'] }}</li>
                                    <li class="list-group-item"><strong>ID Abonnement :</strong> {{ $item['id_abonnement'] }}</li>
                                    <li class="list-group-item"><strong>Montant :</strong> {{ $item['montant'] }} $</li>
                                    <li class="list-group-item"><strong>Date :</strong> {{ $item['date_paiement'] }}</li>
                                    <li class="list-group-item"><strong>Type :</strong> {{ $item['type_paiement'] }}</li>
                                    <li class="list-group-item"><strong>Transaction ID :</strong> {{ $item['transaction_id'] }}</li>
                                    <li class="list-group-item"><strong>Statut :</strong> {{ ucfirst($item['statut']) }}</li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('paiement.facture', $item['id_paiement']) }}" target="_blank" class="btn btn-primary">
                                    Télécharger Facture
                                </a>
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-warning">Aucune transaction disponible.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
