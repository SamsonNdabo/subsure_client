<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture - {{ $paiement['transaction_id'] }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header, .footer {
            text-align: center;
            padding: 10px 0;
        }
        .company-info {
            text-align: right;
        }
        .client-info, .facture-details {
            margin-bottom: 20px;
        }
        .bordered {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
        }
        .title {
            font-size: 22px;
            margin-bottom: 10px;
        }
        .row {
            display: flex;
            justify-content: space-between;
        }
        .text-right {
            text-align: right;
        }
        .mt-4 {
            margin-top: 20px;
        }
        .mb-2 {
            margin-bottom: 10px;
        }
        .bold {
            font-weight: bold;
        }
        .gray {
            color: #888;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" height="50" alt="Logo">
        <h2 class="title">FACTURE</h2>
    </div>

    <div class="row">
        <div class="client-info bordered">
            <p class="bold">Facturé à :</p>
            <p>{{ $paiement['nom'] }}</p>
            <p>{{ $paiement['email'] }}</p>
            <p>{{ $paiement['telephone'] }}</p>
            <p>{{ $paiement['adresse'] }}</p>
        </div>

        <div class="company-info bordered">
            <p class="bold">Émis par :</p>
            <p>SubSure SARL</p>
            <p>Goma, RDC</p>
            <p>support@subsure.com</p>
        </div>
    </div>

    <div class="facture-details mt-4 bordered">
        <p><span class="bold">N° de facture :</span> {{ $paiement['transaction_id'] }}</p>
        <p><span class="bold">Date de paiement :</span> {{ \Carbon\Carbon::parse($paiement['date_paiement'])->format('d/m/Y H:i') }}</p>
        <p><span class="bold">Méthode de paiement :</span> {{ ucfirst($paiement['type_paiement']) }}</p>
        <p><span class="bold">ID Abonnement :</span> {{ $paiement['id_abonnement'] }}</p>
    </div>

    <div class="bordered mt-4">
        <p class="bold mb-2">Détails du montant</p>
        <table width="100%">
            <tr>
                <td>Description</td>
                <td class="text-right bold">Montant</td>
            </tr>
            <tr>
                <td>Service souscrit</td>
                <td class="text-right">{{ number_format($paiement['montant'], 2, ',', ' ') }} $</td>
            </tr>
            <tr>
                <td class="bold">Total</td>
                <td class="text-right bold">{{ number_format($paiement['montant'], 2, ',', ' ') }} $</td>
            </tr>
        </table>
    </div>

    <div class="footer mt-4 gray">
        Merci d'avoir utilisé SubSure. <br>
        Cette facture est générée automatiquement et n’a pas besoin de signature.
    </div>
</body>
</html>
