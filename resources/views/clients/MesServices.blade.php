@extends('Layouts.app')

@section('content')

    {{-- Messages Flash --}}
    @foreach (['success', 'error', 'warning', 'info'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show" role="alert">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
    @endforeach

    <div class="page-header text-center bg-light py-5 shadow-sm rounded-4 mb-4">
        <div class="container">
            @php
                $client = Session::get('user');
            @endphp
            <h1 class="page-title mb-0">Bienvenue <strong class="text-primary">{{ $client['nom'] }}</strong></h1>
            <p class="text-muted">Mes Abonnements</p>
        </div>
    </div>

    <div class="container pt-2 pb-5">
        <div class="row justify-content-center">
            @include('clients.navigation')

            @forelse($abonnement['abonnements'] as $item)
                @php
                    $date_debut = \Carbon\Carbon::parse($item['date_debut']);
                    $date_fin = \Carbon\Carbon::parse($item['date_fin']);
                    $total_days = $date_debut->diffInDays($date_fin);
                    $remaining_days = now()->diffInDays($date_fin, false);
                    $elapsed_days = $total_days - $remaining_days;
                    $progress = ($total_days > 0) ? round(($elapsed_days / $total_days) * 100) : 0;

                    // Définir classes de style selon statut
                    $cardBorder = 'border-secondary';
                    $progressBarClass = 'bg-secondary';
                    $btnClass = 'btn-secondary';
                    $btnLabel = 'Voir';

                    if ($item['statut_abonnement'] === 'en_attente') {
                        $cardBorder = 'border-warning';
                        $progressBarClass = 'bg-warning';
                        $btnClass = 'btn-warning';
                        $btnLabel = 'Payer';
                    } elseif ($item['statut_abonnement'] === 'expire') {
                        $cardBorder = 'border-danger';
                        $progressBarClass = 'bg-danger';
                        $btnClass = 'btn-danger';
                        $btnLabel = 'Renouveler';
                    } elseif ($item['statut_abonnement'] === 'actif') {
                        $cardBorder = 'border-success';
                        $progressBarClass = 'bg-success';
                        $btnClass = 'btn-success';
                        $btnLabel = 'Actif';
                    }
                @endphp

                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm rounded-4 {{ $cardBorder }}">
                        <img src="{{ asset('assets/images/home/bg.jpg') }}" class="card-img-top rounded-top-4" alt="Service image">
                        <div class="card-body text-center p-3 d-flex flex-column">
                            <h5 class="card-title mb-2">{{ $item['Nom_Service'] }}</h5>
                            <p class="card-text small">{{ $item['designation'] }}</p>

                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar {{ $progressBarClass }}" role="progressbar" style="width: {{ $progress }}%;"
                                    aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted mb-2">
                                {{ $date_debut->format('d/m/Y') }} ➜ {{ $date_fin->format('d/m/Y') }}<br>
                                <strong>
                                    @if($item['statut_abonnement'] === 'en_attente')
                                        En attente de paiement
                                    @elseif($remaining_days > 0)
                                        {{ $remaining_days }} j restants
                                    @else
                                        Expiré
                                    @endif
                                </strong>
                            </small>

                            {{-- Bouton selon statut --}}
                            @if($item['statut_abonnement'] === 'en_attente' || $item['statut_abonnement'] === 'expire')
                                <a href="{{ route('stripe.checkout', [
                                    'client_id' => $item['client_id'],
                                    'plan_id' => $item['id_plan'],
                                    'abonnement_id' => $item['id_abonn'],
                                    'service_id' => $item['id_service'],
                                    'prix' => $item['prix'],
                                    'email' => $item['email'],
                                ]) }}" class="btn {{ $btnClass }} mt-auto">{{ $btnLabel }}</a>
                            @else
                                <button class="btn btn-outline-secondary mt-auto" data-bs-toggle="modal"
                                    data-bs-target="#detailsModal{{ $loop->index }}">Voir Détails</button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Détails -->
                <div class="modal fade" id="detailsModal{{ $loop->index }}" tabindex="-1"
                    aria-labelledby="detailsModalLabel{{ $loop->index }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content rounded-4">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailsModalLabel{{ $loop->index }}">Détails du Service</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <img src="{{ asset('assets/images/home/bg.jpg') }}" class="img-fluid rounded-3" alt="Image">
                                    </div>
                                    <div class="col-md-7">
                                        <h6>{{ $item['Nom_Service'] }}</h6>
                                        <p>{{ $item['description'] ?? 'Pas de description' }}</p>
                                        <ul class="list-unstyled">
                                            <li><strong>Client_Id :</strong> {{ $item['client_id'] }}</li>
                                            <li><strong>Email :</strong> {{ $item['email'] }}</li>
                                            <li><strong>Service_Id :</strong> {{ $item['id_service'] }}</li>
                                            <li><strong>Abonnement_Id :</strong> {{ $item['id_abonn'] }}</li>
                                            <li><strong>Prix :</strong> {{ $item['prix'] }}</li>
                                            <li><strong>Date début :</strong> {{ $date_debut->format('d/m/Y') }}</li>
                                            <li><strong>Date fin :</strong> {{ $date_fin->format('d/m/Y') }}</li>
                                            <li><strong>Progression :</strong> {{ $progress }}%</li>
                                            <li><strong>Statut :</strong> {{ $item['statut_abonnement'] }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>

                                @if($item['statut_abonnement'] === 'expire')
                                    <a href="{{ route('stripe.checkout', [
                                        'client_id' => $item['client_id'],
                                        'plan_id' => $item['id_plan'],
                                        'abonnement_id' => $item['id_abonn'],
                                        'service_id' => $item['id_service'],
                                        'prix' => $item['prix'],
                                        'email' => $item['email'],
                                    ]) }}" class="btn btn-primary">Renouveler</a>
                                @elseif($item['statut_abonnement'] === 'en_attente')
                                    <a href="{{ route('stripe.checkout', [
                                        'client_id' => $item['client_id'],
                                        'plan_id' => $item['id_plan'],
                                        'abonnement_id' => $item['id_abonn'],
                                        'service_id' => $item['id_service'],
                                        'prix' => $item['prix'],
                                        'email' => $item['email'],
                                    ]) }}" class="btn btn-warning">Payer</a>
                                    {{-- <a href="{{ route('abonnement.annuler', $item['id_abonn']) }}" class="btn btn-outline-danger ms-2">Annuler</a> --}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-warning">Aucun abonnement disponible.</div>
                </div>
            @endforelse
        </div>
    </div>

@endsection
