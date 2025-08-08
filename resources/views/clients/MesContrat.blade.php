@extends('Layouts.app')

@section('content')
    <div class="page-header text-center bg-light py-5 shadow-sm rounded-4">
        <div class="container">
            @php
                $client = Session::get('user');
            @endphp
            <h1 class="page-title mb-0">Bienvenue <strong class="text-primary">{{ $client['nom'] }}</strong></h1>
            <p class="text-muted">Mes Contrats</p>
        </div>
    </div>
    <div class="container pt-5 pb-5">
        @include('clients.navigation')

        <div class="row">
            @foreach ($contrat as $item)
                <div class="col-md-6">
                    <div class="card mb-4 shadow rounded-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item['reference'] ?? 'Contrat' }}</h5>
                            <p class="card-text">
                                Client : <strong>{{ $item['id_client'] }}</strong><br>
                                Statut :
                                @if ($item['status'] === 'Actif')
                                    <span class="text-success">{{ $item['status'] }}</span>
                                @elseif ($item['status'] === 'Expiré')
                                    <span class="text-danger">{{ $item['status'] }}</span>
                                @else
                                    <span class="text-warning">{{ $item['status'] }}</span>
                                @endif
                                <br>
                                Début : {{ $item['date_debut'] }} <br>
                                Expiration : {{ $item['date_fin'] }}
                            </p>
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                data-bs-target="#contratModal{{ $item['id'] }}">
                                Voir Contrat
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="contratModal{{ $item['id'] }}" tabindex="-1"
                    aria-labelledby="contratModalLabel{{ $item['id'] }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content rounded-4">
                            <div class="modal-header">
                                <h5 class="modal-title" id="contratModalLabel{{ $item['id'] }}">Contrat
                                    #{{ $item['reference'] }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <h6>Détails du contrat</h6>
                                <p>
                                    <strong>Date de signature :</strong> {{ $item['date_signature'] }} <br>
                                    <strong>Date de début :</strong> {{ $item['date_debut'] }} <br>
                                    <strong>Date d’expiration :</strong> {{ $item['date_fin'] }} <br>

                                    @php
                                        $debut = Carbon\Carbon::parse($item['date_debut']);
                                        $fin = Carbon\Carbon::parse($item['date_fin']);
                                        $duree = $debut->diffInMonths($fin);
                                    @endphp

                                    <strong>Durée :</strong> {{ $duree }} mois<br>
                                    <strong>Montant :</strong> {{ $item['prix_contrat'] }}<br>
                                    <strong>Statut :</strong> {{ $item['status'] }}
                                </p>

                                <hr>

                                <h6>Conditions générales</h6>
                                <p>
                                    Ce contrat vous permet de bénéficier du service pendant {{ $duree }} mois.
                                    Le rappel de renouvellement est automatique une semaine avant échéance.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <a href="{{ route('contrat.pdf', $client['ID_']) }}" class="btn btn-primary" target="_blank">
                                    Télécharger PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    
@endsection