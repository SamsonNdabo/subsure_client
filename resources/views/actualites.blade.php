@extends('Layouts.app')
@section('content')
<section class="blog-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Actualités & Conseils</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Découvrez les dernières informations, conseils pratiques et nouveautés autour de la gestion d’abonnements.</p>
        </div>

        <div class="row g-4">
            <!-- Article 1 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ asset('assets/images/home/offre.jpeg') }}" class="card-img-top" alt="Abonnements">
                    <div class="card-body">
                        <h5 class="card-title">Pourquoi gérer vos abonnements en ligne ?</h5>
                        <p class="card-text">Les abonnements non suivis entraînent des pertes. Voici comment une bonne gestion peut vous faire économiser...</p>
                        <a href="#" class="btn btn-link text-primary">Lire plus</a>
                    </div>
                </div>
            </div>

            <!-- Article 2 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ asset('assets/images/home/ab_7.jpeg') }}" class="card-img-top" alt="Alertes">
                    <div class="card-body">
                        <h5 class="card-title">Comment éviter les oublis d’échéance ?</h5>
                        <p class="card-text">SubSure propose des rappels automatiques pour ne plus jamais oublier vos paiements de contrat ou de service...</p>
                        <a href="#" class="btn btn-link text-primary">Lire plus</a>
                    </div>
                </div>
            </div>

            <!-- Article 3 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ asset('assets/images/home/ab_5.jpeg') }}" class="card-img-top" alt="Erreurs à éviter">
                    <div class="card-body">
                        <h5 class="card-title">Top 5 des erreurs de gestion des contrats</h5>
                        <p class="card-text">Découvrez les erreurs les plus fréquentes que font les entreprises et comment les éviter grâce à une plateforme dédiée...</p>
                        <a href="#" class="btn btn-link text-primary">Lire plus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


        <!-- Chiffres clés -->
        <section class="bg-white py-5 text-center">
    <div class="container">
        <h3 class="mb-5 fs-2 fw-bold">SubSure en quelques chiffres</h3>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="p-3">
                    <h2 class="display-5 text-primary fw-bold">2 500+</h2>
                    <p class="text-muted mb-0">Contrats suivis</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <h2 class="display-5 text-success fw-bold">1 200+</h2>
                    <p class="text-muted mb-0">Utilisateurs actifs</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <h2 class="display-5 text-warning fw-bold">3</h2>
                    <p class="text-muted mb-0">Formules d’abonnement</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <h2 class="display-5 text-danger fw-bold">100%</h2>
                    <p class="text-muted mb-0">Satisfaction client</p>
                </div>
            </div>
        </div>
    </div>
</section>


        <!-- Call to action -->
        <div class="cta cta-display bg-image pt-4 pb-4"
            style="background-image: url(assets/images/backgrounds/cta/bg-6.jpg);">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-9 col-xl-8">
                        <div class="row no-gutters flex-column flex-sm-row align-items-sm-center">
                            <div class="col">
                                <h3 class="cta-title text-white">Inscrivez-vous & Abonnez-vous</h3>
                                <p class="cta-desc text-white">SubSure vous permet de suivre l'évolution de vos abonnements
                                </p>
                            </div>
                            <div class="col-auto">
                                <a href="{{ url('/logReg') }}" class="btn btn-outline-white">
                                    <span>S'inscrire</span><i class="icon-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection