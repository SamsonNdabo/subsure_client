<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h1, h2, h4 { margin: 0; padding: 5px 0; }
        .content { font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1 style="text-align:center;">Contrat #{{ $contrat['reference'] ?? $contrat['id'] }}</h1>

    <div class="content">
        <p><strong>Client :</strong> {{ $contrat['id_client'] }}</p>
        <p><strong>Date de signature :</strong> {{ $contrat['date_signature'] ?? 'N/A' }}</p>
        <p><strong>Date de début :</strong> {{ $contrat['date_debut'] }}</p>
        <p><strong>Date de fin :</strong> {{ $contrat['date_fin'] }}</p>
        <p><strong>Prix :</strong> {{ $contrat['prix_contrat'] }} $</p>
        <p><strong>Statut :</strong> {{ $contrat['status'] }}</p>
    </div>

    <hr>

    <div class="content">
        <h4>Conditions générales</h4>
        <p>
            Ce contrat est valable pour la période indiquée ci-dessus.
            Il est automatiquement renouvelé sauf résiliation préalable.
        </p>
    </div>

    <div class="content">
        <p>Fait à Kinshasa, le {{ now()->format('d/m/Y') }}</p>
        <p>Signature du client : __________________________</p>
    </div>
</body>
</html>
