<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation Abonnement</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f9fb; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center;">
        <img src="{{ asset('assets/images/logo-footer.png') }}" alt="Logo SubSure" width="150" style="margin-bottom: 20px;">
        <h2 style="color: #2c3e50;">Merci pour votre abonnement, {{ $nom }} !</h2>
        <p style="font-size: 16px;">Votre souscription au plan <strong>#{{ $plan_id }}</strong> a été activée avec succès.</p>
        <table style="margin: 20px auto; border-collapse: collapse;">
            <tr><td style="padding: 8px;">Prix :</td><td><strong>{{ $prix }} USD</strong></td></tr>
            <tr><td style="padding: 8px;">Début :</td><td><strong>{{ $date_debut }}</strong></td></tr>
            <tr><td style="padding: 8px;">Fin :</td><td><strong>{{ $date_fin }}</strong></td></tr>
            <tr><td style="padding: 8px;">Statut :</td><td><strong style="color: green;">{{ ucfirst($statut) }}</strong></td></tr>
        </table>
        <a href="{{ route('dashboard') }}" style="display: inline-block; margin-top: 20px; background: #007bff; color: #fff; padding: 12px 24px; border-radius: 5px; text-decoration: none;">Voir mes abonnements</a>
    </div>
</body>
</html>
