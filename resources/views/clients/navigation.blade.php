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
    <a class="nav-link" href="{{ url('/clients/MonProfil/' . $client['ID_'])  }}">
        <i class="icon-user"></i> Mon Profil
    </a>
</nav>