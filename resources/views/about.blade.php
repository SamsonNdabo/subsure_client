@extends('Layouts.app')

@section('content')
<main class="main">
    <div class="page-header text-center bg-light py-5 shadow-sm rounded-4">
        <div class="container">
            <h1 class="page-title mb-0">A propos de Nous<strong class="text-primary"></strong></h1>
            <p class="text-muted">Qui sommes-nous</p>
        </div>
    </div>

    <div class="page-content">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="assets/images/home/ab_4.jpeg" class="img-fluid rounded shadow" alt="Notre entreprise">
                </div><!-- End .col-lg-6 -->

                <div class="col-lg-6">
                    <h2 class="title mb-3">Notre Mission</h2>
                    <p class="lead text-dark">
                        Chez <strong>SubSure</strong>, nous croyons en un avenir où la technologie de l’abonnement est simple, sécurisée et accessible à tous. 
                        Notre objectif est de vous offrir une plateforme moderne pour gérer vos abonnements numériques avec transparence et efficacité.
                    </p>
                    <p>
                        Nous combinons expertise technique et passion du service pour créer une expérience fluide et fiable, adaptée aux besoins des particuliers comme des entreprises.
                    </p>
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->

            <hr class="my-5">

            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <img src="assets/images/about.png" class="img-fluid rounded shadow" alt="Notre équipe">
                </div><!-- End .col-lg-6 -->

                <div class="col-lg-6 order-lg-1">
                    <h2 class="title mb-3">Notre Équipe</h2>
                    <p>
                        Notre équipe est composée de développeurs, designers et experts en cybersécurité qui partagent une vision commune : 
                        mettre la technologie au service de la simplicité et de la confiance.
                    </p>
                    <p>
                        Grâce à notre engagement et notre créativité, nous faisons évoluer continuellement notre plateforme pour répondre à vos attentes.
                    </p>
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->

            <hr class="my-5">

            <div class="text-center">
                <h2 class="title">Nos Valeurs</h2>
                <p class="lead text-dark">Innovation, transparence, sécurité et satisfaction client sont au cœur de tout ce que nous faisons.</p>
            </div>
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main><!-- End .main -->
@endsection
