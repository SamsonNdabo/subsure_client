@extends('Layouts.app')

@section('content')
<section class="bg-light">
    <div class="page-header text-center">
        <div class="container">
            <h1 class="page-title">Connexion / Inscription <span>Bienvenue sur notre plateforme</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->

    <div class="container py-5">
        {{-- Messages flash --}}
        @foreach (['success', 'error', 'warning', 'info'] as $msg)
            @if(session($msg))
                <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show" role="alert">
                    {{ session($msg) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif
        @endforeach

        <div class="row justify-content-center">
            <!-- Formulaire Connexion -->
            <div class="col-md-5 mb-4">
                <div class="card shadow rounded-4 border-0">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Se connecter</h4>
                        <form method="POST" action="{{route('Login')}}"> {{-- Remplace par ta route --}}
                            @csrf
                            <div class="form-group mb-3">
                                <label for="login_email">Adresse email</label>
                                <input type="email" name="email" id="login_email" class="form-control" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="login_password">Mot de passe</label>
                                <input type="password" name="password" id="login_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill">Connexion</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Formulaire Inscription -->
            <div class="col-md-5 mb-4">
                <div class="card shadow rounded-4 border-0">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Créer un compte</h4>
                        <form method="POST" action=""> {{-- Remplace par ta route --}}
                            @csrf
                            <div class="form-group mb-3">
                                <label for="register_email">Adresse email</label>
                                <input type="email" name="email" id="register_email" class="form-control" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="register_password">Mot de passe</label>
                                <input type="password" name="password" id="register_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100 rounded-pill">S'inscrire</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
