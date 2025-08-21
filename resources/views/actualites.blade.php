@extends('Layouts.app')
@section('content')
<section class="blog-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Actualités & Conseils</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">
                Découvrez les dernières informations, conseils pratiques et nouveautés autour de la gestion d’abonnements.
            </p>
        </div>

        <div class="row g-4">
            <!-- Article 1 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ asset('assets/images/home/offre.jpeg') }}" class="card-img-top" alt="Abonnements">
                    <div class="card-body">
                        <h5 class="card-title">Pourquoi gérer vos abonnements en ligne ?</h5>
                        <p class="card-text">
                            Les abonnements non suivis entraînent des pertes. Voici comment une bonne gestion peut vous faire économiser...
                        </p>
                        <a href="https://www.journaldunet.com/business/dictionnaire-du-marketing/1202966-gestion-des-abonnements-definition-traduction-et-synonymes/"
                           target="_blank" class="btn btn-link text-primary">Lire plus</a>
                    </div>
                </div>
            </div>

            <!-- Article 2 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ asset('assets/images/home/ab_7.jpeg') }}" class="card-img-top" alt="Alertes">
                    <div class="card-body">
                        <h5 class="card-title">Comment éviter les oublis d’échéance ?</h5>
                        <p class="card-text">
                            SubSure propose des rappels automatiques pour ne plus jamais oublier vos paiements de contrat ou de service...
                        </p>
                        <a href="https://www.service-public.fr/particuliers/actualites/A15894"
                           target="_blank" class="btn btn-link text-primary">Lire plus</a>
                    </div>
                </div>
            </div>

            <!-- Article 3 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ asset('assets/images/home/ab_5.jpeg') }}" class="card-img-top" alt="Erreurs à éviter">
                    <div class="card-body">
                        <h5 class="card-title">Top 5 des erreurs de gestion des contrats</h5>
                        <p class="card-text">
                            Découvrez les erreurs les plus fréquentes que font les entreprises et comment les éviter grâce à une plateforme dédiée...
                        </p>
                        <a href="https://www.lemonde.fr/economie/article/2023/05/10/les-erreurs-a-eviter-dans-la-gestion-des-contrats_6172803_3234.html"
                           target="_blank" class="btn btn-link text-primary">Lire plus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
