       @extends('Layouts.app')
       @section('content')
       <section class="offers-section py-5 bg-white " id="offres">
    <div class="container text-center">
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
@endsection