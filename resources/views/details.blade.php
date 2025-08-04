@extends('Layouts.app')

@section('content')

    <!-- Page Header -->
    <div class="page-header text-center" style="background-image: url('{{ asset('assets/images/about-header.jpg') }}')">
        <div class="container">
            <h1 class="page-title">À propos du service <span>{{ $service[0]['designation'] }}</span></h1>
        </div>
    </div>
    <!-- Fin Header -->

    <!-- Contenu principal -->
    <div class="page-content pb-5">
        <div class="container">
            <!-- À propos du service -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/home/bg.jpg') }}" class="img-fluid rounded shadow"
                        style="width: 100%; height: auto; object-fit: cover;" alt="Image Service">
                </div>
                <div class="col-lg-6">
                    <h2 class="mb-3">Description du service</h2>
                    <p class="text-muted">{{ $service[0]['description'] }}</p>
                </div>
            </div>

            <!-- Plans d'abonnement -->
            <h2 class="text-center mb-5">Choisissez un plan</h2>
            <div class="d-flex flex-row flex-wrap justify-content-center gap-4">
                <div class="d-flex flex-row flex-wrap justify-content-center gap-4">
                    @forelse ($plansForService as $item)
                        <div class="col-md-4" style="flex: 0 0 300px; max-width: 300px;">
                            <div class="card h-100 border-0 shadow-sm d-flex flex-column justify-content-between"
                                style="height: 100%;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold text-center">{{ $item['designation'] }}</h5>
                                    <p class="text-muted text-center">Durée : {{ $item['intervalle'] }} jours</p>
                                    <h4 class="text-success text-center mb-3">{{ $item['prix'] }} $</h4>

                                    <ul class="list-unstyled small text-start mb-4 flex-grow-1"
                                        style="max-height: 120px; overflow-y: auto;">
                                        @if(isset($avantagesParPlan[$item['id_plan']]))
                                            @foreach($avantagesParPlan[$item['id_plan']] as $avantage)
                                                <li>✔️ {{ $avantage }}</li>
                                            @endforeach
                                        @else
                                            <li class="text-muted">Aucun avantage spécifié</li>
                                        @endif
                                    </ul>

                                    <div class="text-center mt-auto">
                                        @if (Session::has('user'))
                                            <a href="{{ url('paiement_stripe/payement') }}" class="btn btn-outline-success w-100">S'abonner</a>
                                        @else
                                            <a href="{{ url('/logReg') }}" class="btn btn-outline-primary w-100">
                                                <i class="icon-user"></i> Se connecter
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">Aucun plan disponible pour ce service.</p>
                    @endforelse
                </div>

            </div>

            <!-- Pourquoi choisir ce service -->
            <div class="mt-5 pt-4 border-top">
                <h2 class="text-center mb-4">Pourquoi choisir ce service ?</h2>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">✅ Paiement mensuel sans engagement</li>
                            <li class="list-group-item">✅ Support client 24/7</li>
                            <li class="list-group-item">✅ Mises à jour automatiques</li>
                            <li class="list-group-item">✅ Compatible tous supports</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted">
                            En choisissant ce service, vous bénéficiez d'une protection fiable et complète pour tous vos
                            appareils et données.
                            <strong>Essayez gratuitement pendant 7 jours</strong> avant de vous engager !
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection