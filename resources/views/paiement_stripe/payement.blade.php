@extends('Layouts.app')
@section('content')
<div class="page-header text-center">
  <div class="container">
    <h1 class="page-title">Paiement <span>Confirmer votre abonnement</span></h1>
  </div>
</div>

<div class="container pt-5 pb-5">
  <div class="row">
    <div class="col-lg-6">
      <!-- Détail de l’abonnement -->
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title">Votre plan sélectionné</h5>
          <ul class="list-unstyled">
            <li><strong>Nom du plan :</strong> Kaspersky Premium</li>
            <li><strong>Durée :</strong> 12 mois</li>
            <li><strong>Prix :</strong> <span class="text-primary">6000 FCFA</span></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Paiement sécurisé par carte</h5>
          <div id="card-element" class="form-control mb-3"></div>
          <div id="card-errors" class="text-danger mb-3"></div>
          <button id="submit-button" class="btn btn-primary btn-block">Payer maintenant</button>
          <div id="payment-success" class="alert alert-success mt-3 d-none">✅ Paiement réussi !</div>
          <div id="payment-failure" class="alert alert-danger mt-3 d-none">❌ Paiement échoué.</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');
    card.on('change', e => {
      document.getElementById('card-errors').textContent = e.error?.message || '';
    });

    document.getElementById('submit-button').addEventListener('click', async () => {
      const montant = 6000;
      const numero_abonnement = "ABO123";
      try {
        const res = await fetch('/api/initiate-payment', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ montant, numero_abonnement })
        });
        const data = await res.json();
        if (data.status !== 'success') throw new Error(data.message || 'initialisation échouée');

        const result = await stripe.confirmCardPayment(data.client_secret, { payment_method: { card } });
        if (result.error) {
          document.getElementById('card-errors').textContent = result.error.message;
          document.getElementById('payment-failure').classList.remove('d-none');
        } else if (result.paymentIntent.status === 'succeeded') {
          document.getElementById('payment-success').classList.remove('d-none');
          await fetch('/api/save-payment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              abonnement: { id: 2, price: montant },
              paiement: {
                transaction_id: result.paymentIntent.id,
                type_paiement: 'stripe'
              }
            })
          });
        }
      } catch (err) {
        alert("Erreur : " + err.message);
      }
    });
  });
</script>
@endsection
