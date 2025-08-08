@extends('Layouts.app')

@section('content')

<div class="page-header text-center bg-light py-5 shadow-sm rounded-4 mb-5">
    <div class="container">
        <h1 class="page-title mb-1 text-primary fw-bold" style="letter-spacing: 0.05em;">Détails du service</h1>
        <p class="text-dark fs-5 fst-normal fw-semibold">
            {{ $service['designation'] ?? $service[0]['designation'] ?? '' }}
        </p>
    </div>
</div>

<div class="container pb-5">

    {{-- Description --}}
    <section class="mb-5">
        <h3 class="section-title">
            <i class="bi bi-info-circle text-primary me-2"></i> Description
        </h3>
        <div class="p-5 bg-white rounded-4 shadow-sm mx-auto" style="max-width: 900px;">
            <p class="text-dark fs-5 mb-0" style="line-height: 1.9; font-size: 1.25rem; font-weight: 500;">
                {{ $service['description'] ?? $service[0]['description'] ?? 'Pas de description disponible' }}
            </p>
        </div>
    </section>

    {{-- Plans disponibles --}}
    <section class="mb-5">
        <h3 class="section-title">
            <i class="bi bi-box-seam text-primary me-2"></i> Plans Disponibles
        </h3>
        <div class="plans-grid">
            @foreach($plansForService as $plan)
                @php $pid = $plan['id_plan'] ?? $plan['id']; @endphp
                <div class="card pricing-card rounded-4 shadow border-0 d-flex flex-column">
                    <div class="card-body d-flex flex-column justify-content-between h-100 text-center p-4">
                        <div>
                            <h5 class="card-title text-primary fw-bold mb-3" style="letter-spacing: 0.04em;">
                                {{ $plan['designation'] ?? $plan['nom'] ?? 'Plan inconnu' }}
                            </h5>
                            <p class="text-muted small fst-italic mb-3" style="min-height: 3rem;">
                                {{ $plan['description'] ?? '' }}
                            </p>
                            <h4 class="text-success fw-bold mb-4 display-5" style="font-weight: 900;">
                                {{ number_format($plan['prix'], 2) }} $
                            </h4>
                            @if(isset($avantagesParPlan[$pid]) && count($avantagesParPlan[$pid]) > 0)
                                <ul class="list-unstyled text-start small text-dark mb-4" style="line-height: 1.5;">
                                    @foreach($avantagesParPlan[$pid] as $avantage)
                                        <li>✅ {{ $avantage }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted fst-italic mb-4">Aucun avantage spécifié.</p>
                            @endif
                        </div>
                        <button class="btn btn-primary rounded-pill px-4 mt-auto" 
                                data-bs-toggle="modal"
                                data-bs-target="#modal-abonnement"
                                data-plan-id="{{ $pid }}"
                                data-prix="{{ $plan['prix'] }}"
                                data-interval="{{ $plan['intervalle'] ?? 30 }}">
                            S'abonner
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Services liés --}}
    @if(!empty($servicesEntreprise))
    <section class="mb-5">
        <h3 class="section-title">
            <i class="bi bi-building text-primary me-2"></i> Autres services de cette entreprise
        </h3>
        <div class="row g-4">
            @foreach($servicesEntreprise as $s)
                @if(($s['id_service'] ?? $s['id']) != ($service['id_service'] ?? $service['id']))
                <div class="col-md-3">
                    <div class="card service-card border-0 rounded-4 shadow-sm h-100 hover-shadow" style="transition: box-shadow 0.3s ease;">
                        <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                            <h6 class="fw-semibold text-primary" style="letter-spacing: 0.03em;">{{ $s['designation'] }}</h6>
                            <p class="text-muted small mb-3" style="min-height: 4rem;">
                                {{ \Illuminate\Support\Str::limit($s['description'], 80) }}
                            </p>
                            <a href="{{ route('details', ['id' => $s['id_service'] ?? $s['id'], 'entreprise_id' => $s['entreprise_id']]) }}" 
                               class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-auto">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </section>
    @endif

    {{-- Conditions Générales --}}
    <section>
        <h3 class="section-title">
            <i class="bi bi-file-earmark-text text-primary me-2"></i> Conditions Générales
        </h3>
        <div class="accordion" id="accordionCGU">
            <div class="accordion-item rounded-4 shadow-sm border-0">
                <h2 class="accordion-header" id="headingCGU">
                    <button class="accordion-button fw-semibold rounded-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCGU" aria-expanded="true" aria-controls="collapseCGU">
                        Lire les conditions générales du service
                    </button>
                </h2>
                <div id="collapseCGU" class="accordion-collapse collapse show" aria-labelledby="headingCGU" data-bs-parent="#accordionCGU">
                    <div class="accordion-body text-secondary" style="line-height: 1.6;">
                        En vous abonnant, vous acceptez nos conditions générales de service. 
                        Celles-ci précisent vos droits et responsabilités en tant qu’utilisateur.
                        <br><br>
                        @if(!empty($articles))
                            @foreach($articles as $article)
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
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Confirmation d’abonnement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form id="subscribeForm" novalidate>
                    @csrf
                    <input type="hidden" id="modal-plan-id" name="plan_id">
                    <input type="hidden" id="abonnement_id" name="abonnement_id" value="0">
                    <input type="hidden" id="service_id" name="service_id" value="{{ $service['id'] ?? $service[0]['id'] }}">
                    <input type="hidden" id="modal-prix" name="prix">
                    <input type="hidden" id="modal-interval" name="interval" value="30">
                    <input type="hidden" id="entreprise_id" name="entreprise_id" value="{{ $service['entreprise_id'] ?? $service[0]['entreprise_id'] }}">
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="cguCheckbox" required>
                        <label class="form-check-label" for="cguCheckbox" style="font-weight: 500;">
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
        e.preventDefault();

        if (!document.getElementById('cguCheckbox').checked) {
            alert('Vous devez accepter les conditions générales.');
            return;
        }

        const plan_id = encodeURIComponent(document.getElementById('modal-plan-id').value);
        const abonnement_id = encodeURIComponent(document.getElementById('abonnement_id').value);
        const service_id = encodeURIComponent(document.getElementById('service_id').value);
        const prix = encodeURIComponent(document.getElementById('modal-prix').value);
        const interval = encodeURIComponent(document.getElementById('modal-interval').value);
        const entreprise_id = encodeURIComponent(document.getElementById('entreprise_id').value);

        const url = "{{ url('/stripe/checkout') }}/" + plan_id + "/" + abonnement_id + "/" + service_id + "/" + prix + "/" + interval + "/" + entreprise_id;
        window.location.href = url;
    });
});
</script>

<style>
    /* Uniformiser la taille des cards plans */
    .plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.75rem;
    }

    .pricing-card {
        display: flex;
        flex-direction: column;
        height: 500px; /* taille fixe pour toutes les cards */
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    .pricing-card:hover {
        box-shadow: 0 0.6rem 1.2rem rgba(13, 110, 253, 0.3);
        transform: translateY(-5px);
        cursor: pointer;
    }

    /* Contenu du card body */
    .pricing-card .card-body {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 2rem 1.5rem;
    }

    /* Bouton aligné en bas */
    .pricing-card button.btn {
        margin-top: auto;
        font-weight: 600;
        letter-spacing: 0.03em;
        padding: 0.6rem 2rem;
    }

    /* Titres sections style pro */
    .section-title {
        font-weight: 700;
        font-size: 1.6rem;
        letter-spacing: 0.06em;
        border-bottom: 3px solid #0d6efd;
        padding-bottom: 0.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Description text noir, visible */
    section > div.p-5 p {
        color: #222222 !important;
    }

    /* Services liés hover */
    .service-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, 0.25);
        cursor: pointer;
        transform: translateY(-4px);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
</style>

@endsection
