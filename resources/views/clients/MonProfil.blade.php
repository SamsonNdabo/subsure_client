@extends('Layouts.app')

@section('content')
<!-- En-tête de page -->
<div class="page-header text-center">
    <div class="container">
        <h1 class="page-title">Mon Profil</h1>
    </div>
</div>

<!-- Contenu principal -->
<div class="container pt-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Carte Profil -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-4">
                        <img src="assets/images/users/default-avatar.png" alt="Avatar"
                            class="rounded-circle mr-3" width="80" height="80">
                        <div>
                            <h4 class="mb-0">John Doe</h4>
                            <small class="text-muted">Client depuis : 12 Janv. 2024</small>
                        </div>
                        <div class="ml-auto">
                            <a href="#" class="btn btn-outline-primary btn-sm"><i class="icon-edit"></i> Modifier</a>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Email :</strong>
                            <p class="text-muted mb-0">johndoe@example.com</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>Téléphone :</strong>
                            <p class="text-muted mb-0">+243 89 123 4567</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Date de naissance :</strong>
                            <p class="text-muted mb-0">10 Février 1995</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>Sexe :</strong>
                            <p class="text-muted mb-0">Masculin</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Adresse :</strong>
                            <p class="text-muted mb-0">123 Rue de l'Exemple, Kinshasa</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>Pays :</strong>
                            <p class="text-muted mb-0">RDC</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
