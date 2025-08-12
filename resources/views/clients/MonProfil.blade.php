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
    @foreach (['success', 'error', 'warning', 'info'] as $msg)
            @if(session($msg))
                <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show">
                    {{ session($msg) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Carte Profil -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset('assets/images/home/bg.jpg') }}" alt="Avatar"
                             class="rounded-circle mr-3" width="80" height="80">
                        <div>
                            <h4 class="mb-0">{{ $client['nom'] ?? 'Nom inconnu' }}</h4>
                            <small class="text-muted">
                                Client depuis : {{ \Carbon\Carbon::parse($client['created_at'] ?? now())->translatedFormat('d M Y') }}
                            </small>
                        </div>
                        <div class="ml-auto">
                            <!-- Bouton qui ouvre le modal -->
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="icon-edit"></i> Modifier
                            </button>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Email :</strong>
                            <p class="text-muted mb-0">{{ $client['email'] ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>Téléphone :</strong>
                            <p class="text-muted mb-0">{{ $client['telephone'] ?? 'Non renseigné' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- tu peux ajouter d'autres infos si besoin -->
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Adresse :</strong>
                            <p class="text-muted mb-0">{{ $client['adresse'] ?? 'Non renseignée' }}</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Bootstrap pour modifier le profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('client.update', $client['ID_']) }}">
        
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Modifier mon profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <!-- Nom -->
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $client['nom']) }}" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $client['email']) }}" required>
                </div>

                <!-- Téléphone -->
                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone', $client['telephone']) }}">
                </div>

                <!-- Adresse -->
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <textarea class="form-control" id="adresse" name="adresse">{{ old('adresse', $client['adresse']) }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </div>
        </div>
    </form>
  </div>
</div>

@endsection
