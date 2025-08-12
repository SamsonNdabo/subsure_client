@extends('Layouts.app')

@section('content')
<div class="bg-dark position-relative mb-5" style="height: 380px; overflow: hidden;">
    <img src="{{ asset('assets/images/home/home_2.png') }}" 
         alt="Service Image" 
         class="w-100 h-100" 
         style="object-fit: contain; background-color: #000;">
         
    <!-- Overlay sombre -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.4);"></div>
    
    <!-- Contenu texte -->
    <div class="position-absolute top-50 start-50 translate-middle text-center text-white px-3" 
         style="text-white: 0 0 5px rgba(0, 0, 0, 0.8); max-width: 90%;">
        <h1 class="display-4 fw-bold text-white ">{{ $service['designation'] ?? $service[0]['designation'] ?? 'Service inconnu' }}</h1>
        <p class="fs-5 fst-italic text-white">Details du service</p>
    </div>
</div>


<div class="container">

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="alert alert-success rounded-2">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger rounded-2">
            {{ session('error') }}
        </div>
    @endif

    <div class="row gy-5">

        {{-- Colonne gauche : Description + Plans --}}
        <div class="col-lg-7">
            {{-- Description --}}
            <section>
                <h2 class="h4 text-primary mb-4">Description</h2>
                <div class="bg-white p-4 rounded shadow-sm">
                    <p class="mb-0" style="line-height:1.6;">
                        {{ $service['description'] ?? $service[0]['description'] ?? 'Pas de description disponible.' }}
                    </p>
                </div>
            </section>

            {{-- Plans --}}
            <section class="mt-5">
                <h2 class="h4 text-primary mb-4">Plans disponibles</h2>
                <div class="row g-4">
                    @foreach($plansForService as $plan)
                        @php $pid = $plan['id_plan'] ?? $plan['id']; @endphp
                        <div class="col-md-6">
                            <div class="card shadow-sm rounded-3 h-100">
                                <div class="card-body d-flex flex-column h-100">
                                    <h5 class="card-title fw-bold text-primary">{{ $plan['designation'] ?? $plan['nom'] ?? 'Plan inconnu' }}</h5>
                                    <p class="text-muted small fst-italic mb-3">
                                        {{ $plan['description'] ?? 'Description non disponible.' }}
                                    </p>
                            
                                    @if(isset($avantagesParPlan[$pid]) && count($avantagesParPlan[$pid]) > 0)
                                        <ul class="list-unstyled small mb-3 flex-grow-1">
                                            @foreach($avantagesParPlan[$pid] as $avantage)
                                                <li>✅ {{ $avantage }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted fst-italic mb-3 flex-grow-1">Aucun avantage spécifié.</p>
                                    @endif
                            
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fs-4 fw-semibold text-success">{{ number_format($plan['prix'], 2) }} $</span>
                                        <button class="btn btn-primary btn-sm rounded-pill"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-abonnement"
                                            data-plan-id="{{ $pid }}"
                                            data-prix="{{ $plan['prix'] }}"
                                            data-interval="{{ $plan['intervalle'] ?? 30 }}">
                                            S'abonner
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- Colonne droite : Entreprise + Services liés --}}
        <div class="col-lg-5">

            {{-- Informations entreprise --}}
            @if($entreprise)
            <section class="mb-5">
                <h2 class="h5 text-primary mb-3">Informations sur l'entreprise</h2>
                <div class="bg-white p-4 rounded shadow-sm">
                    <h5 class="fw-bold mb-2">{{ $entreprise['nom_entreprise'] ?? 'Nom non disponible' }}</h5>
                    <p class="mb-1"><strong>ID National:</strong> {{ $entreprise['id_national'] ?? '-' }}</p>
                    <p class="mb-1"><strong>Adresse:</strong> {{ $entreprise['adresse'] ?? '-' }}</p>
                    <p class="mb-1"><strong>Ville:</strong> {{ $entreprise['ville'] ?? '-' }}</p>
                    <p class="mb-1"><strong>Code Postal:</strong> {{ $entreprise['code_postal'] ?? '-' }}</p>
                    <p class="mb-1"><strong>Téléphone:</strong> {{ $entreprise['telephone'] ?? '-' }}</p>
                    <p class="mb-0"><strong>Email:</strong> {{ $entreprise['email'] ?? '-' }}</p>
                </div>
            </section>
            @endif

            {{-- Services liés --}}
            @if(!empty($servicesEntreprise))
            <section>
                <h2 class="h5 text-primary mb-3">Autres services de cette entreprise</h2>
                <div class="list-group shadow-sm rounded">
                    @foreach($servicesEntreprise as $s)
                        @php
                            $currentServiceId = $service['id'] ?? null;
                            $otherServiceId = $s['id'];
                        @endphp
                        @if($otherServiceId != $currentServiceId)
                            <a href="{{ route('details', ['id' => $otherServiceId, 'entreprise_id' => $s['entreprise_id']]) }}" class="list-group-item list-group-item-action rounded-2">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 text-primary">{{ $s['designation'] }}</h6>
                                </div>
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($s['description'], 80) }}</small>
                            </a>
                        @endif
                    @endforeach
                </div>
            </section>
            @endif

        </div>
    </div>

    {{-- Conditions Générales --}}
    <section class="mt-5">
        <h2 class="h5 text-primary mb-3">Conditions Générales</h2>
        <div class="accordion" id="accordionCGU">
            <div class="accordion-item rounded shadow-sm border-0">
                <h2 class="accordion-header" id="headingCGU">
                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCGU" aria-expanded="true" aria-controls="collapseCGU">
                        Lire les conditions générales du service
                    </button>
                </h2>
                <div id="collapseCGU" class="accordion-collapse collapse show" aria-labelledby="headingCGU" data-bs-parent="#accordionCGU">
                    <div class="accordion-body text-secondary" style="line-height: 1.6;">
                        En vous abonnant, vous acceptez nos conditions générales de service.
                        Celles-ci précisent vos droits et responsabilités en tant qu’utilisateur.
                        <br><br>
                        @if(!empty($articlesService))
                            @foreach($articlesService as $article)
                                <h6 class="fw-bold">{{ $article['titre'] }}</h6>
                                <p>{{ $article['contenu'] }}</p>
                            @endforeach
                        @else
                            <p class="text-muted">Aucune condition spécifique pour ce service.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Modal d’abonnement --}}
<div class="modal fade" id="modal-abonnement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Confirmation d’abonnement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form id="subscribeForm" method="POST" action="{{ route('abonnement.creer') }}" novalidate>
                    @csrf
                    <input type="hidden" id="modal-plan-id" name="plan_id" value="">
                    <input type="hidden" id="abonnement_id" name="abonnement_id" value="0">
                    <input type="hidden" id="service_id" name="service_id" value="{{ $service['id'] ?? $service[0]['id'] }}">
                    <input type="hidden" id="modal-prix" name="prix" value="">
                    <input type="hidden" id="modal-interval" name="interval" value="30">
                    <input type="hidden" id="entreprise_id" name="entreprise_id" value="{{ $service['entreprise_id'] ?? $service[0]['entreprise_id'] }}">

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="cguCheckbox" required>
                        <label class="form-check-label fw-semibold" for="cguCheckbox">
                            J'accepte les <a href="#collapseCGU" data-bs-toggle="collapse" style="color:#0d6efd; text-decoration: underline;">conditions générales</a>
                        </label>
                    </div>

                    <div class="modal-footer px-0 border-0">
                        <button type="submit" class="btn btn-success rounded-pill w-100 py-2 fs-5 fw-semibold">Confirmer et payer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script modal --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal-abonnement');

    modal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        document.getElementById('modal-plan-id').value = button.getAttribute('data-plan-id');
        document.getElementById('modal-prix').value = button.getAttribute('data-prix');
        document.getElementById('modal-interval').value = button.getAttribute('data-interval');
    });

    document.getElementById('subscribeForm').addEventListener('submit', e => {
        if (!document.getElementById('cguCheckbox').checked) {
            e.preventDefault();
            alert('Vous devez accepter les conditions générales.');
        }
    });
});
</script>

@endsection
