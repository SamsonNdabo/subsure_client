<footer class="footer footer-dark">
	<div class="footer-middle">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-lg-3">
					<div class="widget widget-about">
						<img src={{ asset('assets/images/logo_footer_.png') }} class="footer-logo" alt="Footer Logo"
							width="105" height="30">
						<p>Praesent dapibus, neque id cursus ucibus, tortor neque egestas augue, eu vulputate magna eros
							eu erat. </p>

						<div class="social-icons">
							<a href="#" class="social-icon" title="Facebook" target="_blank"><i
									class="icon-facebook-f"></i></a>
							<a href="#" class="social-icon" title="Twitter" target="_blank"><i
									class="icon-twitter"></i></a>
							<a href="#" class="social-icon" title="Instagram" target="_blank"><i
									class="icon-instagram"></i></a>
							<a href="#" class="social-icon" title="Youtube" target="_blank"><i
									class="icon-youtube"></i></a>
							<a href="#" class="social-icon" title="Pinterest" target="_blank"><i
									class="icon-pinterest"></i></a>
						</div><!-- End .soial-icons -->
					</div><!-- End .widget about-widget -->
				</div><!-- End .col-sm-6 col-lg-3 -->

				<div class="col-sm-6 col-lg-3">
					<div class="widget">
						<h4 class="widget-title">A savoir sur SubSure</h4><!-- End .widget-title -->
						<ul class="widget-list">
							<li><a href="{{ url('/about') }}">A propos de Nous</a></li>
							<li><a href="{{ url('/') }}">Comment suscrire a SubSure</a></li>
							<li><a href="{{ url('contact') }}">Contactez-Nous</a></li>
						</ul><!-- End .widget-list -->
					</div><!-- End .widget -->
				</div><!-- End .col-sm-6 col-lg-3 -->

				<div class="col-sm-6 col-lg-3">
					<div class="widget">
						<h4 class="widget-title">Mes services</h4><!-- End .widget-title -->

						<ul class="widget-list">
							@if (Session::has('user'))
								<li><a href="{{ url('/clients/dashboard') }}">Tableau de Bord</a></li>
							@else
								<li><a href="{{ url('/logReg') }}">Tableau de Bord</a></li>
							@endif
							@if (Session::has('user'))
							@php
								$client=Session::get('user');
							@endphp
							
								<li><a href="{{ url('/clients/MesServices/'.$client['ID_']) }}">Mes Abonnements</a></li>
							@else
								<li><a href="{{ url('/logReg') }}">Mes Abonnements</a></li>
							@endif
							@if (Session::has('user'))
								<li><a href="{{ url('/clients/MesContrat/'.$client['ID_']) }}">Mes Contrats</a></li>
							@else
								<li><a href="{{ url('/logReg') }}">Mes Contrats</a></li>
							@endif
						</ul><!-- End .widget-list -->
					</div><!-- End .widget -->
				</div><!-- End .col-sm-6 col-lg-3 -->

				<div class="col-sm-6 col-lg-3">
					<div class="widget">
						<h4 class="widget-title">Mon Compte</h4><!-- End .widget-title -->

						<ul class="widget-list">
							@if (Session::has('user'))
								<li><a href="{{ url('/clients/dashboard') }}">Mon Compte</a></li>
							@else
								<li><a href="{{ url('/logReg') }}">Login</a></li>
							@endif
							
							<li><a href="{{ url('/register') }}">S'inscrire</a></li>
							<li><a href="#">Aide</a></li>
						</ul><!-- End .widget-list -->
					</div><!-- End .widget -->
				</div><!-- End .col-sm-6 col-lg-3 -->
			</div><!-- End .row -->
		</div><!-- End .container -->
	</div><!-- End .footer-middle -->

	<div class="footer-bottom">
		<div class="container">
			<p class="footer-copyright">Copyright © 2025 SubSure. All Rights Reserved.</p><!-- End .footer-copyright -->
			<figure class="footer-payments">
				<img src={{ asset('assets/images/payments.png') }} alt="Payment methods" width="272" height="20">
			</figure><!-- End .footer-payments -->
		</div><!-- End .container -->
	</div><!-- End .footer-bottom -->
</footer><!-- End .footer -->