@extends('Layouts.app')

@section('content')

{{-- En-tête --}}
<div class="page-header text-center bg-light py-5 shadow-sm rounded-4 mb-4">
    <div class="container">
        @php $client = Session::get('user'); @endphp
        <h1 class="page-title mb-0">
            Bienvenue <strong class="text-primary">{{ $client['nom'] }}</strong>
        </h1>
        <p class="text-muted">Mes Abonnements</p>
    </div>
</div>

<div class="container pt-2 pb-5">
    <div class="row justify-content-center">

        {{-- Menu client --}}
        @include('clients.navigation')

        {{-- Messages flash --}}
        @foreach (['success', 'error', 'warning', 'info'] as $msg)
            @if(session($msg))
                <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show">
                    {{ session($msg) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif
        @endforeach

        {{-- Abonnements --}}
        @forelse($abonnements as $index => $abn)
            @php
                $date_debut = \Carbon\Carbon::parse($abn['date_debut']);
                $date_fin   = \Carbon\Carbon::parse($abn['date_fin']);
                $total_days = $date_debut->diffInDays($date_fin);
                $remaining_days = now()->diffInDays($date_fin, false);
                $elapsed_days = $total_days - $remaining_days;
                $progress = ($total_days > 0) ? round(($elapsed_days / $total_days) * 100) : 0;

                $statusStyles = [
                    'en_attente' => ['border-warning', 'bg-warning'],
                    'expire'     => ['border-danger', 'bg-danger'],
                    'actif'      => ['border-success', 'bg-success'],
                    'annule'     => ['border-dark', 'bg-dark'],
                    'contractualise' => ['border-info', 'bg-info'],
                ];
                [$cardBorder, $progressBarClass] = $statusStyles[$abn['statut_abonnement']] ?? ['border-secondary', 'bg-secondary'];

                $entreprise = $entreprises[$abn['entreprise_id']] ?? null;
            @endphp

            {{-- Carte abonnement --}}
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4 d-flex justify-content-center">
                <div class="card h-100 shadow-sm rounded-4 {{ $cardBorder }}" style="width: 100%;">
                    <img src="{{ env('API_BASE_URL') . '/service_image/' . $abn['image'] }}" alt="Image du Service" class="img-fluid rounded-top-4">
                    <div class="card-body d-flex flex-column text-center">
                        <h5 class="card-title">{{ $abn['Nom_Service'] }}</h5>
                        <p class="text-muted small mb-2">{{ $abn['designation'] ?? '' }}</p>

                        @if(!in_array($abn['statut_abonnement'], ['en_attente', 'annule']))
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar {{ $progressBarClass }}" style="width: {{ $progress }}%;"></div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                <strong>Du :</strong> {{ $date_debut->format('d/m/Y') }} <br>
                                <strong>Au :</strong> {{ $date_fin->format('d/m/Y') }} <br>
                                <strong>Status :</strong> 
                                @if($abn['statut_abonnement'] === 'en_attente') En attente de paiement
                                @elseif($abn['statut_abonnement'] === 'annule') Annulé
                                @elseif($remaining_days > 0) {{ $remaining_days }} j restants
                                @else Expiré @endif
                            </small>
                        </div>

                        {{-- Bouton centré --}}
                        <div class="mt-auto d-flex justify-content-center">
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $index }}">
                                <i class="icon-info-circle"></i> Voir Détails
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal abonnement --}}
            <div class="modal fade" id="detailsModal{{ $index }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content rounded-4">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="icon-info-circle"></i> Détails du Service & Entreprise</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-center">
                            <div class="row justify-content-center">
                                <div class="col-md-5 mb-3 mb-md-0">
                                    <img src="{{ env('API_BASE_URL') . '/service_image/' . $abn['image'] }}" class="img-fluid rounded-3">
                                </div>
                                <div class="col-md-7 text-start">
                                    <h6>{{ $abn['Nom_Service'] }}</h6>
                                    <p class="small text-muted">{{ $abn['description'] ?? 'Pas de description' }}</p>

                                    <ul class="list-unstyled small">
                                        <li><strong>Client :</strong> {{ $abn['client_id'] }}</li>
                                        <li><strong>Email Client :</strong> {{ $abn['email'] }}</li>
                                        <li><strong>Service :</strong> {{ $abn['id_service'] }}</li>
                                        <li><strong>Abonnement :</strong> {{ $abn['id_abonn'] }}</li>
                                        <li><strong>Prix :</strong> {{ $abn['prix'] }}</li>
                                        <li><strong>Date début :</strong> {{ $date_debut->format('d/m/Y') }}</li>
                                        <li><strong>Date fin :</strong> {{ $date_fin->format('d/m/Y') }}</li>
                                        <li><strong>Statut :</strong> {{ ucfirst($abn['statut_abonnement']) }}</li>

                                        @if($entreprise && count($entreprise) > 0)
                                            <li><strong>Entreprise :</strong> {{ $entreprise[0]['nom_entreprise'] ?? '-' }}</li>
                                            <li><strong>Email :</strong> {{ $entreprise[0]['email'] ?? '-' }}</li>
                                            <li><strong>Adresse :</strong> {{ $entreprise[0]['adresse'] ?? '-' }}</li>
                                        @else
                                            <li><strong>Entreprise :</strong> Non disponible</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Footer avec boutons --}}
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="icon-close"></i> Fermer</button>

                            @if($abn['statut_abonnement'] === 'expire')
                                <a href="{{ route('stripe.checkout', [
                                    'client_id' => $abn['client_id'],
                                    'plan_id' => $abn['id_plan'],
                                    'abonnement_id' => $abn['id_abonn'],
                                    'service_id' => $abn['id_service'],
                                    'prix' => $abn['prix'],
                                    'email' => $abn['email'],
                                ]) }}" class="btn btn-danger"><i class="icon-refresh"></i> Renouveler</a>

                            @elseif($abn['statut_abonnement'] === 'en_attente')
                                <a href="{{ route('stripe.checkout', [
                                    'client_id' => $abn['client_id'],
                                    'plan_id' => $abn['id_plan'],
                                    'abonnement_id' => $abn['id_abonn'],
                                    'service_id' => $abn['id_service'],
                                    'prix' => $abn['prix'],
                                    'email' => $abn['email'],
                                ]) }}" class="btn btn-warning"><i class="icon-credit-card"></i> Payer</a>

                                <form action="{{ route('abonnement.annuler') }}" method="POST" class="d-inline ms-2">
                                    @csrf
                                    <input type="hidden" name="numAbonnement" value="{{ $abn['numAbonnement'] }}">
                                    <input type="hidden" name="Nom_Service" value="{{ $abn['Nom_Service'] }}">
                                    <input type="hidden" name="entreprise_id" value="{{ $abn['entreprise_id'] }}">
                                    <button type="submit" class="btn btn-outline-danger"
                                        onclick="return confirm('Envoi d\'une demande d\'annulation')"><i class="icon-ban"></i> Annuler</button>
                                </form>

                            @elseif($abn['statut_abonnement'] === 'annule')
                                <form action="{{ route('abonnement.active') }}" method="POST" class="d-inline ms-2">
                                    @csrf
                                    <input type="hidden" name="numAbonnement" value="{{ $abn['numAbonnement'] }}">
                                    <input type="hidden" name="Nom_Service" value="{{ $abn['Nom_Service'] }}">
                                    <input type="hidden" name="entreprise_id" value="{{ $abn['entreprise_id'] }}">
                                    <button type="submit" class="btn btn-outline-primary"
                                        onclick="return confirm('Envoi d\'une demande de réactivation')"><i class="icon-check"></i> Réactiver</button>
                                </form>
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
