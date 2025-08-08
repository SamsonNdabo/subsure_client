@extends('layouts.app')

@section('content')
<section class="bg-light">
    <div class="page-header text-center bg-light py-5 shadow-sm rounded-4">
        <div class="container">
            <h1 class="page-title mb-0">Réinitialiser le mot de passe</h1>
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
            <div class="col-md-5">
                <div class="card shadow rounded-4 border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="mb-3">
                                <label for="email">Adresse email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password">Nouveau mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill">Réinitialiser</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</section>
@endsection
