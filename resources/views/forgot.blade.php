@extends('layouts.app')

@section('content')
    <section class="bg-light">
        <div class="page-header text-center bg-light py-5 shadow-sm rounded-4">
            <div class="container">
                <h1 class="page-title mb-0">Mot de passe oublié</h1>
                <p class="text-muted">Recevez un lien de réinitialisation</p>
            </div>
        </div>

        <div class="container py-5">
            @foreach (['success', 'error', 'warning', 'info'] as $msg)
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

            @endforeach
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow rounded-4 border-0">
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email">Adresse email</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-warning w-100 rounded-pill">Envoyer le lien</button>
                            </form>

                            <div class="text-center mt-3">
                                <a href="{{ route('Login') }}">Retour à la connexion</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection