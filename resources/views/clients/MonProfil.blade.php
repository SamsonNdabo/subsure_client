@extends('Layouts.app')

@section('content')
    <!-- En-tête de page -->
    <div class="page-header text-center bg-light py-5 shadow-sm rounded-4">
        <div class="container">
            @php
                $client = Session::get('user');
            @endphp
            <h1 class="page-title mb-0">Bienvenue <strong class="text-primary">{{ $client['nom'] }}</strong></h1>
            <p class="text-muted">Mon Profil</p>
        </div>
    </div>
        <!-- Contenu principal -->
        <div class="container pt-5 pb-5">
                    @include('clients.navigation')
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <!-- Carte Profil -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">

                            <div class="d-flex align-items-center mb-4">
                                <img src="{{ asset('assets/images/home/bg.jpg') }}" alt="Avatar"
                                    class="rounded-circle mr-3" width="80" height="80">
                                <div>
                                    <h4 class="mb-0">{{ $profil['nom'] ?? 'Nom inconnu' }}
                                    </h4>
                                    <small class="text-muted">
                                        Client depuis :
                                        {{ \Carbon\Carbon::parse($profil['created_at'] ?? now())->translatedFormat('d M Y') }}
                                    </small>
                                </div>
                                <div class="ml-auto">
                                    <a href="#" class="btn btn-outline-primary btn-sm"><i class="icon-edit"></i>
                                        Modifier</a>
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Email :</strong>
                                    <p class="text-muted mb-0">{{ $profil['email'] ?? 'Non renseigné' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <strong>Téléphone :</strong>
                                    <p class="text-muted mb-0">{{ $profil['telephone'] ?? 'Non renseigné' }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>Adresse :</strong>
                                    <p class="text-muted mb-0">{{ $profil['adresse'] ?? 'Non renseignée' }}</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
@endsection