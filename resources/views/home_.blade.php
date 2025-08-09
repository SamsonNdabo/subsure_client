@extends('Layouts.app')
@section('content')

    <main class="main">
        <!-- SLIDER SECTION -->
        <div class="intro-section bg-lighter pt-5 pb-6">
             {{-- Messages flash --}}
        @foreach (['success', 'error', 'warning', 'info'] as $msg)
            @if(session($msg))
                <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show" role="alert">
                    {{ session($msg) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif
        @endforeach

            <div class="container">
                <div class="intro-slider owl-carousel owl-simple owl-light owl-nav-inside mb-5" data-toggle="owl"
                    data-owl-options='{
                    "nav": true,
                    "dots": true,
                    "loop": true,
                    "items": 1,
                    "autoplay": true,
                    "autoplayTimeout": 5000
                }'>

                    {{-- Slide 1 --}}
                    <div class="intro-slide position-relative" style="height: 600px; overflow: hidden;">
                        <img src="{{ asset('assets/images/home/chat-bg.jpg') }}" alt="Bienvenue"
                            style="object-fit: cover; width: 100%; height: 100%;">
                        <div
                            class="intro-content position-absolute top-50 start-50 translate-middle text-black text-center">
                            <h3 class="intro-subtitle fs-4 text-black mb-2">Bienvenue sur <span
                                    class="text-warning">SubSure</span></h3>
                            <h2 class="intro-title fs-1 fw-bold text-black ">Gérez vos abonnements, services<br>et contrats facilement
                            </h2>
                        </div>
                    </div>

                    {{-- Slide 2 --}}
                    <div class="intro-slide position-relative" style="height: 600px; overflow: hidden;">
                        <img src="{{ asset('assets/images/home/contact.jpg') }}" alt="Abonnements"
                            style="object-fit: cover; width: 100%; height: 100%;">
                        <div
                            class="intro-content position-absolute top-50 start-50 translate-middle text-white text-center">
                            <h3 class="intro-subtitle fs-4 text-light mb-2">Tout en un clic</h3>
                            <h2 class="intro-title fs-1 fw-bold">Abonnez-vous à nos services<br>en toute simplicité</h2>
                            <a href="#services" class="btn btn-primary mt-4 px-4 py-2">VOIR PLUS</a>
                        </div>
                    </div>

                    {{-- Slide 3 --}}
                    <div class="intro-slide position-relative" style="height: 600px; overflow: hidden;">
                        <img src="{{ asset('assets/images/home/img1.jpg') }}" alt="Nettoyage"
                            style="object-fit: cover; width: 100%; height: 100%;">
                        <div
                            class="intro-content position-absolute top-50 start-50 translate-middle text-black text-center">
                            <h3 class="intro-subtitle fs-4 text-white mb-2">Suivi des Contrats et Abonnements Clients</h3>
                            <h2 class="intro-title fs-1 fw-bold text-black">Pour entreprises<br>et particuliers</h2>
                            <a href="#offres" class="btn btn-outline-light mt-4 px-4 py-2">VOIR PLUS</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- SERVICES SECTION -->
        <section class="pt-5 pb-6 bg-white">
            <div class="container">
                <div class="heading heading-center mb-5" id="services">
                    <h2 class="title-lg text-dark fw-bold">Nos Services</h2>
                    <p class="text-muted mt-2">Découvrez une sélection variée de services adaptés à vos besoins.</p>
                </div>

                <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                    data-owl-options='{
                    "nav": true,
                    "dots": false,
                    "margin": 20,
                    "loop": false,
                    "responsive": {
                        "0": {"items":1},
                        "576": {"items":2},
                        "768": {"items":3},
                        "992": {"items":4}
                    }
                }'>

                    @foreach($services as $service)
                        <div class="product product-11 text-center shadow-sm p-3 bg-light rounded-3">
                            <figure class="product-media mb-3">
                                <a href="{{ url('/details/' . $service['id']) }}">
                                    <img src="{{ asset('assets/images/home/ab_2.jpg') }}" alt="Image du Service"
                                        class="img-fluid rounded">
                                </a>
                            </figure>
                            <div class="product-body">
                                <h3 class="product-title mb-1">
                                    <a href="{{ url('/details/' . $service['id']) }}"
                                        class="text-dark">{{ $service['designation'] }}</a>
                                </h3>
                                <p class="product-desc text-muted small">{{ $service['description'] }}</p>
                            </div>
                            <div class="product-action mt-3">
                                <a href="{{ url('/details/' . $service['id']) }}" class="btn btn-outline-primary btn-sm">
                                    <span>Détails</span>
                                </a>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>


        <!-- Nos Offres -->
        <section class="offers-section py-5 bg-white">
    <div class="container text-center"id="offres">
        <h3 class="mb-5 fs-2 fw-bold">Nos Offres</h3>
        <div class="row g-4">
            <!-- Offre Essentielle -->
            <div class="col-md-4">
                <div class="card shadow-lg border-0 h-100 p-4">
                    <div class="mb-3 text-primary">
                        <i class="icon-user fs-1"></i>
                    </div>
                    <h5 class="card-title">Offre Essentielle</h5>
                    <p class="card-text">Gérez jusqu’à 3 services avec alertes simples. Idéale pour les particuliers.</p>
                    <p class="text-success fs-5 fw-bold">Gratuit</p>
                </div>
            </div>

            <!-- Offre Pro -->
            <div class="col-md-4">
                <div class="card shadow-lg border-0 h-100 p-4">
                    <div class="mb-3 text-warning">
                        <i class="icon-briefcase fs-1"></i>
                    </div>
                    <h5 class="card-title">Offre Pro</h5>
                    <p class="card-text">Gérez jusqu’à 10 services avec rappels automatiques, PDF, et support prioritaire.</p>
                    <p class="text-primary fs-5 fw-bold">25.000 FC / Mois</p>
                </div>
            </div>

            <!-- Offre Entreprise -->
            <div class="col-md-4">
                <div class="card shadow-lg border-0 h-100 p-4">
                    <div class="mb-3 text-danger">
                        <i class="icon-building fs-1"></i>
                    </div>
                    <h5 class="card-title">Offre Entreprise</h5>
                    <p class="card-text">Nombre illimité de services, tableaux de bord, utilisateurs multiples, et plus.</p>
                    <p class="text-dark fs-5 fw-bold">Sur mesure</p>
                </div>
            </div>
        </div>
    </div>
</section>


        <!-- Témoignages -->
        <section class="bg-light py-5">
    <div class="container text-center">
        <h3 class="mb-5 fs-2 fw-bold">Ce que disent nos clients</h3>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 bg-white shadow-sm rounded">
                    <p class="fst-italic">"SubSure m’a évité plusieurs pénalités de retard. Simple et efficace !"</p>
                    <footer class="blockquote-footer mt-2">Jean M., Kinshasa</footer>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white shadow-sm rounded">
                    <p class="fst-italic">"Notre entreprise gère mieux ses abonnements grâce à leur tableau de bord."</p>
                    <footer class="blockquote-footer mt-2">Claire T., Lubumbashi</footer>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white shadow-sm rounded">
                    <p class="fst-italic">"Le suivi automatisé est vraiment un game changer. Bravo à l’équipe."</p>
                    <footer class="blockquote-footer mt-2">Pascal D., Goma</footer>
                </div>
            </div>
        </div>
    </div>
</section>

        <!-- Blog / Actualités -->
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
                                <a href="{{ url('/register') }}" class="btn btn-outline-white">
                                    <span>S'inscrire</span><i class="icon-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

@endsection