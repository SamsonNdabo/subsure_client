{{-- Toast de bienvenue --}}
<style>
    #welcomeToast {
        min-width: 320px;
        font-size: 1.2rem;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.25);
        cursor: default;
    }
    #welcomeToast .toast-body {
        font-weight: 600;
    }

    /* Style du chat modal */
    #chatModal .modal-dialog {
        max-width: 400px;
    }
    #chatModal .modal-content {
        border-radius: 1rem;
        overflow: hidden;
    }
    #chatMessages {
        height: 350px;
        overflow-y: auto;
        padding: 15px;
        background-color: #f8f9fa;
    }
    .message {
        padding: 10px;
        border-radius: 12px;
        margin-bottom: 10px;
        max-width: 85%;
        word-wrap: break-word;
    }
    .user-message {
        background-color: #d1e7dd;
        align-self: flex-end;
        text-align: right;
    }
    .ai-message {
        background-color: #e2e3e5;
        align-self: flex-start;
    }
    #chatForm input {
        border-radius: 50px;
    }
    #chatForm button {
        border-radius: 50px;
    }
</style>

<nav class="nav nav-pills nav-fill mb-5 shadow-sm rounded-4 overflow-hidden">
    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/clients/dashboard') }}">
        <i class="icon-home"></i> Dashboard
    </a>
    <a class="nav-link" href="{{ url('/clients/MesServices/' . $client['ID_']) }}">
        <i class="icon-briefcase"></i> Mes Abonnements
    </a>
    <a class="nav-link" href="{{ url('/clients/MesContrat/' . $client['ID_']) }}">
        <i class="icon-file-text"></i> Mes Contrats
    </a>
    <a class="nav-link" href="{{ url('/clients/MesTransact/' . $client['ID_']) }}">
        <i class="icon-wallet"></i> Mes Transactions
    </a>
    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#chatModal">
                    <i></i> Service Client
                </a>
<a class="nav-link" href="{{ url('/clients/MonProfil')  }}">
            <i class="icon-user"></i> Mon Profil
    </a>
</nav>
@include('chat');