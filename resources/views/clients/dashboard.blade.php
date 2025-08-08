@extends('Layouts.app')

@section('content')

{{-- Toast de bienvenue amélioré --}}
<style>
    #welcomeToast {
        min-width: 320px;          /* Largeur minimum confortable */
        font-size: 1.2rem;         /* Texte un peu plus grand */
        padding: 1rem 1.5rem;      /* Padding plus important */
        border-radius: 0.5rem;     /* Bords arrondis */
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.25);
        cursor: default;
    }
    #welcomeToast .toast-body {
        font-weight: 600;
    }
</style>

<script>
    window.addEventListener('DOMContentLoaded', function () {
        const welcomeToast = document.getElementById('welcomeToast');
        const toast = new bootstrap.Toast(welcomeToast, {
            animation: true,
            autohide: true,
            delay: 9000  // 5 secondes d'affichage
        });
        toast.show();
    });
</script>

<div class="toast-container position-fixed top-0 end-0 p-4" style="z-index: 1080;">
    <div id="welcomeToast" class="toast bg-primary text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-primary text-white border-0">
            <strong class="me-auto fs-5">Bienvenue</strong>
            <small class="text-white-50">À l’instant</small>
            <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast" aria-label="Fermer"></button>
        </div>
        <div class="toast-body">
            Bonjour <strong>{{ $client['nom'] }}</strong> ! Ravi de vous revoir.
        </div>
    </div>
</div>

<main class="main">
    <div class="page-header text-center bg-light py-5 shadow-sm rounded-4">
        <div class="container">
            <h1 class="page-title mb-0">Bienvenue <strong class="text-primary">{{ $client['nom'] }}</strong></h1>
            <p class="text-muted">Tableau de bord</p>
        </div>
    </div>

    <div class="page-content">
        <div class="container py-5">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            {{-- Navigation --}}
            <nav class="nav nav-pills nav-fill mb-5 shadow-sm rounded-4 overflow-hidden">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/clients/dashboard') }}">
                    <i class="icon-home"></i> Dashboard
                </a>
                <a class="nav-link" href="{{ url('/clients/MesServices/'.$client['ID_']) }}">
                    <i class="icon-briefcase"></i> Mes Abonnements
                </a>
                <a class="nav-link" href="{{ url('/clients/MesContrat/'.$client['ID_']) }}">
                    <i class="icon-file-text"></i> Mes Contrats
                </a>
                <a class="nav-link" href="{{ url('/clients/MesTransact/'.$client['ID_']) }}">
                    <i class="icon-wallet"></i> Mes Transactions
                </a>
                
                <a class="nav-link" href="{{ url('/clients/MonProfil/'.$client['ID_'])  }}
">
                    <i class="icon-user"></i> Mon Profil
                </a>
            </nav>

            {{-- Statistiques --}}
            <div class="row g-4 mb-5">
                @php
                    $boxes = [
                        ['count' => $stats['actifs'] ?? 0, 'label' => 'Abonnements Actifs', 'color' => 'white'],
                        ['count' => $stats['expires'] ?? 0, 'label' => 'Abonnements Expirés', 'color' => 'white'],
                        ['count' => $stats['attente'] ?? 0, 'label' => 'En Attente', 'color' => 'white'],
                        ['count' => $stats['contrats'] ?? 0, 'label' => 'Contrats', 'color' => 'white'],
                    ];
                @endphp

                @foreach ($boxes as $box)
                    <div class="col-sm-6 col-lg-3">
                        <div class="card h-100 border-0 rounded-4 bg-{{ $box['color'] }} text-white shadow-lg transition-transform hover-translate">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $box['label'] }}</h5>
                                <p class="display-5 fw-bold">{{ $box['count'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>
@endsection
