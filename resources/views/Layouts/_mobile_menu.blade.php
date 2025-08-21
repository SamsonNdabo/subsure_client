<div class="mobile-menu-container">
    <div class="mobile-menu-wrapper">
        <span class="mobile-menu-close"><i class="icon-close"></i></span>

        {{-- <form action="#" method="get" class="mobile-search">
            <label for="mobile-search" class="sr-only">Search</label>
            <input type="search" class="form-control" name="mobile-search" id="mobile-search" placeholder="Search..." required>
            <button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>
        </form> --}}

        <nav class="mobile-nav">
            <ul class="mobile-menu">
                <li class="active"><a href="{{ url('/') }}">Accueil</a></li>
                <li><a href="{{ url('/nos_services') }}">Nos Services</a></li>
                {{-- <li><a href="{{ url('/nos_offres') }}">Nos Offres</a></li> --}}
                <li><a href="{{ url('/actualites') }}">Actualites</a></li>
                @if (Session::has('user'))
                    <li><a href="{{ url('/clients/dashboard') }}">Mon Compte</a></li>
                @else
                    <li><a href="{{ url('/logReg') }}">Login</a></li>
                @endif
                <li><a href="{{ url('/about') }}">À propos</a></li>
                <li><a href="{{ url('/contact') }}">Contact</a></li>
                @if (Session::has('user'))
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#logoutModall"><i class="icon-user"></i> Déconnexion</a></li>
                @else
                    <li><a href="{{ url('/logReg') }}"><i class="icon-user"></i> Connexion</a></li>
                @endif
            </ul>
        </nav><!-- End .mobile-nav -->

        <div class="social-icons">
            <a href="#" class="social-icon" target="_blank" title="Facebook"><i class="icon-facebook-f"></i></a>
            <a href="#" class="social-icon" target="_blank" title="Twitter"><i class="icon-twitter"></i></a>
            <a href="#" class="social-icon" target="_blank" title="Instagram"><i class="icon-instagram"></i></a>
            <a href="#" class="social-icon" target="_blank" title="Youtube"><i class="icon-youtube"></i></a>
        </div><!-- End .social-icons -->
    </div><!-- End .mobile-menu-wrapper -->
</div><!-- End .mobile-menu-container -->
<div class="modal fade" id="logoutModall" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment vous déconnecter ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="{{ url('/logout') }}" class="btn btn-danger">Se déconnecter</a>
            </div>
        </div>
    </div>
</div>

