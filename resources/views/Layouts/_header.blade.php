<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-left"></div>

            <div class="header-right">
                <ul class="top-menu">
                    <li>
                        <ul>
                            <li><a href="tel:#"><i class="icon-phone"></i>Call: +0123 456 789</a></li>
                            <li><a href="{{ url('/about') }}">About Us</a></li>
                            <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                            @if (Session::has('user'))
                                <li>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                        <i class="icon-user"></i>Logout
                                    </a>
                                </li>
                            @else
                                <li><a href="{{ url('/logReg') }}"><i class="icon-user"></i>Login</a></li>
                            @endif

                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="header-middle sticky-header">
        <div class="container">
            <div class="header-left">
                <button class="mobile-menu-toggler">
                    <span class="sr-only">Toggle mobile menu</span>
                    <i class="icon-bars"></i>
                </button>

                <a href="{{ url('/') }}" class="logo">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Molla">
                </a>
            </div>

            <div class="header-center d-none d-lg-block">
                <nav class="main-nav">
                    <ul class="menu sf-arrows">
                        <li class="active"><a href="{{ url('/') }}">Accueil</a></li>
                        <li><a href="#services_pages">Nos Services</a></li>
                        <li><a href="#offres_page">Nos Offres</a></li>
                        <li><a href="#offres_page">Actualites</a></li>
                        <li><a href="#">Mon Compte</a></li>


                    </ul>
                </nav>
            </div>

            <div class="header-right">
                <div class="header-search">
                    <a href="#" class="search-toggle" role="button" title="Search"><i class="icon-search"></i></a>
                    <form action="#" method="get">
                        <div class="header-search-wrapper">
                            <label for="q" class="sr-only">Search</label>
                            <input type="search" class="form-control" name="q" id="q" placeholder="Search in..."
                                required>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Modal confirmation logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
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
