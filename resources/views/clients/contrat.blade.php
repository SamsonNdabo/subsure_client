<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #000; }
        h1 { text-align: center; font-size: 22px; color: #2c3e50; margin-bottom: 20px; }
        h2 { font-size: 16px; color: #34495e; margin-top: 20px; margin-bottom: 10px; }
        .section { margin-bottom: 20px; }
        .row { display: flex; margin-bottom: 6px; }
        .label { width: 200px; font-weight: bold; color: #2c3e50; }
        .value { flex: 1; }
        .clause { margin-bottom: 12px; }
        .clause-title { font-weight: bold; font-size: 14px; margin-bottom: 4px; }
        .signature { width: 45%; text-align: center; display: inline-block; margin-top: 40px; }
        hr { margin: 20px 0; border: none; border-top: 1px solid #ccc; }
        .footer { margin-top: 30px; font-size: 12px; }
        pre { white-space: pre-wrap; font-family: DejaVu Sans, sans-serif; }
    </style>
</head>
@php
    $client = Session::get('user');
@endphp

<body>
    {{-- Titre principal --}}
    <h1>CONTRAT D'ABONNEMENT</h1>

    {{-- Partie Entreprise / Client --}}
    <div class="section">
        <h2>ENTRE :</h2>
        <p>L'entreprise {{ $contrat[0]['nom_entreprise'] ?? 'Entreprise inconnue' }}</p>

        <h2>ET LE CLIENT :</h2>
        <div class="row"><div class="label">Nom / Raison sociale</div><div class="value">{{ $client['nom'] ?? 'N/A' }}</div></div>
        <div class="row"><div class="label">Adresse</div><div class="value">{{ $client['adresse'] ?? 'N/A' }}</div></div>
        <div class="row"><div class="label">Téléphone</div><div class="value">{{ $client['telephone'] ?? 'N/A' }}</div></div>
        <div class="row"><div class="label">Email</div><div class="value">{{ $client['email'] ?? 'N/A' }}</div></div>
    </div>

    {{-- Informations du contrat --}}
    <div class="section">
        <h2>INFORMATIONS DU CONTRAT</h2>
        <div class="row"><div class="label">Référence</div><div class="value">{{ $contrat[0]['reference'] ?? 'Aucune' }}</div></div>
        {{-- <div class="row"><div class="label">Type de contrat</div><div class="value">{{ $contrat[0]['id_typeContrat'] ?? 'Standard' }}</div></div> --}}
        <div class="row"><div class="label">Date de signature</div><div class="value">{{ $contrat[0]['date_signature'] ?? 'Non définie' }}</div></div>
        <div class="row"><div class="label">Date de début</div><div class="value">{{ $contrat[0]['date_debut'] ?? 'Inconnue' }}</div></div>
        <div class="row"><div class="label">Date de fin</div><div class="value">{{ $contrat[0]['date_fin'] ?? 'Inconnue' }}</div></div>
        <div class="row"><div class="label">Prix</div><div class="value">{{ $contrat[0]['prix_contrat'] ?? '0' }} $</div></div>
        <div class="row"><div class="label">Statut</div><div class="value">{{ ucfirst($contrat[0]['status'] ?? 'N/A') }}</div></div>
    </div>

    {{-- Clauses --}}
    <div class="section">
        <h2>CLAUSES DU CONTRAT</h2>
        @if(isset($contrat[0]['clauses']) && is_array($contrat[0]['clauses']))
            @foreach($contrat[0]['clauses'] as $i => $clause)
                <div class="clause">
                    <div class="clause-title">{{ $i+1 }}. {{ $clause['design'] }}</div>
                    <pre>{{ $clause['clause'] }}</pre>
                </div>
            @endforeach
        @else
            <p>Aucune clause enregistrée.</p>
        @endif
    </div>

    {{-- Signatures --}}
    <div class="section">
        <div class="signature">
            <p>LE CLIENT</p>
            <hr>
        </div>
        <div class="signature" style="float: right;">
            <p>L'ENTREPRISE</p>
            <hr>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Fait à {{ $entreprise['adresse'] ?? 'Kinshasa' }}, le {{ now()->format('d/m/Y') }}</p>
        <p>Mention « Lu et approuvé » à faire précéder chaque signature.</p>
    </div>
</body>
</html>
