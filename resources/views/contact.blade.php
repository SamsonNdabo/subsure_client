@extends('Layouts.app')

@section('content')
<main class="main">
    <div class="page-header text-center">
        <div class="container">
            <h1 class="page-title">Contactez-nous<span>Nous sommes à votre écoute</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->

    <div class="page-content">
        <div class="container py-5">
            <div class="row">
                <!-- Formulaire de contact -->
                <div class="col-md-6">
                    <h2 class="title mb-3">Envoyez-nous un message</h2>
                    <form action="" method="" class="contact-form">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" name="name" class="form-control" required>
                        </div><!-- End .form-group -->

                        <div class="form-group">
                            <label for="email">Adresse Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div><!-- End .form-group -->

                        <div class="form-group">
                            <label for="subject">Sujet</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div><!-- End .form-group -->

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" class="form-control" rows="6" required></textarea>
                        </div><!-- End .form-group -->

                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </form>
                </div><!-- End .col-md-6 -->

                <!-- Informations de contact -->
                <div class="col-md-6">
                    <h2 class="title mb-3">Nos coordonnées</h2>
                    <ul class="contact-info list-unstyled">
                        <li><i class="icon-map-marker"></i> 123 Avenue Technologique, Kinshasa, RDC</li>
                        <li><i class="icon-phone"></i> +243 822 123 456</li>
                        <li><i class="icon-envelope"></i> contact@subsure.cd</li>
                        <li><i class="icon-clock-o"></i> Lundi - Vendredi : 08h00 - 17h00</li>
                    </ul>

                    <div class="mt-4">
                        <h4 class="mb-2">Suivez-nous :</h4>
                        <a href="#" class="social-icon" title="Facebook"><i class="icon-facebook-f"></i></a>
                        <a href="#" class="social-icon" title="Twitter"><i class="icon-twitter"></i></a>
                        <a href="#" class="social-icon" title="LinkedIn"><i class="icon-linkedin"></i></a>
                    </div>
                </div><!-- End .col-md-6 -->
            </div><!-- End .row -->
        </div><!-- End .container -->

        <!-- Optionnel : Google Maps -->
        <div class="container-fluid px-0 mt-5">
            <div style="width: 100%; height: 200px;">
                <iframe class="w-100 h-100 border-0" src="https://www.google.com/maps/embed?pb=!1m18...v" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div><!-- End .page-content -->
</main><!-- End .main -->
@endsection
