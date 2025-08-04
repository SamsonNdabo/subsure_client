<?php

use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\PaiementController;
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

Route::post('/logReg', [AuthController::class, 'login'])->name('Login');
Route::get('/logout', function () {
    Session::forget('user');
    Session::flush();
    return redirect('/logReg')->with('success', 'Déconnexion réussie.');
})->name('logout');

//session client
Route::middleware(['check.session'])->group(function () {
    Route::get('/clients/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    // Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/contrat/pdf/{id}', [ContratController::class, 'telechargerPDF'])->name('contrat.pdf');
    Route::get('/clients/MonProfil', function () {
        return view('clients/MonProfil');
    });
    Route::get('/clients/MesServices/{id}', [AbonnementController::class, 'listabonnement'])->name('abonnement');
    Route::get('/clients/MesTransact/{id}', [PaiementController::class, 'listpaiement']);
    Route::get('/clients/MesContrat/{id}', [ContratController::class, 'listcontrat']);
    Route::get('/clients/MesTransact/{id}/facture', [PaiementController::class, 'facture'])->name('paiement.facture');
    //stripe renouvellement 
    Route::get('/paiement_stripe/checkout_success', [CheckoutController::class, 'success'])->name('stripe.success');
    Route::get('/paiement_stripe/checkout_cancel', [CheckoutController::class, 'cancel'])->name('stripe.cancel');
    Route::get('/paiement_stripe/checkout/{client_id}/{plan_id}/{abonnement_id}/{service_id}/{prix}/{email}', [CheckoutController::class, 'checkout'])->name('stripe.checkout');

    Route::get('paiement_stripe/payement', function () {
    return view('paiement_stripe/payement');
});
});






Route::get('/about', function () {
    return view('about');
});
Route::get('/contact', function () {
    return view('contact');
});


Route::get('/', [HomeController::class, 'home']);
