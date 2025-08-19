@extends('Layouts.app')

@section('content')
    <section class="bg-light">
        <div class="page-header text-center bg-light py-5 shadow-sm rounded-4">
            <div class="container">
                <h1 class="page-title mb-0">Inscription</h1>
                <p class="text-muted">Créez votre compte gratuitement</p>
            </div>
        </div>

        <div class="container py-5">
            @foreach (['success', 'error', 'warning', 'info'] as $msg)
                @if(session($msg))
                    <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show">
                        {{ session($msg) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            @endforeach

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow rounded-4 border-0">
                        <div class="card-body p-4">
                            <h4 class="text-center mb-4">Créer un compte</h4>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="nom">Nom complet</label>
                                    <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}"
                                        required>
                                    @error('nom')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email">Adresse email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="telephone">Téléphone</label>
                                    <input type="text" name="telephone" id="telephone" class="form-control"
                                        value="{{ old('telephone') }}">
                                    @error('telephone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="adresse">Adresse</label>
                                    <input type="text" name="adresse" id="adresse" class="form-control"
                                        value="{{ old('adresse') }}">
                                    @error('adresse')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password">Mot de passe</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation">Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-success w-100 rounded-pill">S'inscrire</button>
                            </form>

                            <div class="text-center mt-3">
                                <a href="{{ route('Login') }}">Déjà un compte ? Connexion</a>
                            </div>

                            {{-- Bloc renvoyer le mail de vérification --}}
                            @if(Session::has('registration_user') && empty(Session::get('registration_user.email_verified_at')))
                                <div class="text-center mt-3">
                                    <form method="GET" action="{{ route('verification.resend') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0">Renvoyer le mail de vérification</button>
                                    </form>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection