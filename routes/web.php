<?php

use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\Dashboardcontroller;
use App\Http\Controllers\Admin\Admincontroller;
use App\Http\Controllers\Admin\Userscontroller;
use App\Http\Controllers\Admin\Customerscontroller;
use App\Http\Controllers\Admin\Categorycontroller;
use App\Http\Controllers\Admin\Productcontroller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DetailsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\PayementController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\CheckoutController;

use App\Http\Controllers\SabonnerController;
use Illuminate\Support\Facades\Session;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/checkout/{abonnement_id}', [StripeController::class, 'checkout'])->name('checkout');
Route::post('/session', [StripeController::class, 'stripesession'])->name('stripe.session');


Route::post('/initiate-payment', [StripeController::class, 'createPaymentIntent']);
Route::post('/save-payment', [StripeController::class, 'savePayment']);

Route::get('admin', [AuthController::class, 'login_admin']);
Route::get('admin/logout', [AuthController::class, 'logout_admin']);
Route::get('/details/{id}', [HomeController::class, 'detailsService']);
// Routes accessibles SANS être connecté
Route::get('/logReg', function () {
    return view('logReg');
});
Route::get('/actualites', function () {
    return view('actualites');
});
Route::get('/nos_offres', function () {
    return view('nos_offres');
});
Route::get('/nos_offres', function () {
    return view('nos_offres');
});

Route::post('/logReg', [AuthController::class, 'login'])->name('Login');
Route::get('/logout', function () {
    Session::forget('user');
    Session::flush();
    return redirect('/logReg')->with('success', 'Déconnexion réussie.');
})->name('logout');
Route::post('/details/{id}', [HomeController::class, 'handlePost'])->name('details.post');
Route::get('/details/{id}', [HomeController::class, 'detailsService'])->name('details');
Route::post('/abonnement/creer', [SabonnerController::class, 'creerAbonnement'])->name('abonnement.creer');
Route::get('/abonnement/success', [SabonnerController::class, 'success'])->name('abonnements.success');

//session client
Route::middleware(['check.session'])->group(function () {
   

    Route::get('/clients/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/clients/contrat/pdf/{id}', [ContratController::class, 'telechargerPDF'])->name('contrat.pdf');

    Route::get('/clients/MesServices/{id}', [AbonnementController::class, 'listabonnement'])->name('abonnement');
    Route::get('/clients/MesTransact/{id}', [PaiementController::class, 'listpaiement']);
    Route::get('/clients/MesContrat/{id}', [ContratController::class, 'listcontrat']);
    Route::get('/clients/MonProfil/{id}', [ProfilController::class, 'profil']);

    Route::get('/clients/facture/{id}', [PaiementController::class, 'facture'])->name('paiement.facture');
    
    //stripe renouvellement 
    Route::get('/paiement_stripe/checkout_success', [CheckoutController::class, 'success'])->name('stripe.success');
    Route::get('/paiement_stripe/checkout_cancel', [CheckoutController::class, 'cancel'])->name('stripe.cancel');
    Route::get('/paiement_stripe/checkout/{client_id}/{plan_id}/{abonnement_id}/{service_id}/{prix}/{email}', [CheckoutController::class, 'checkout'])->name('stripe.checkout');
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/paiement_success', function () {
    return view('paiement_success');
})->name('paiement_success');



Route::get('/about', function () {
    return view('about');
});
Route::get('/contact', function () {
    return view('contact');
});
Route::get('/nos_services', [ServicesController::class, 'services']);
Route::get('/nos_services/{id}', [HomeController::class, 'detailsService'])->name('nos_services.details');

Route::get('/', [HomeController::class, 'home']);
