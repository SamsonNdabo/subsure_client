<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Confirmation d'abonnement - SubSure</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            background-color: #fff;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            border: 1px solid #e1e4e8;
            overflow: hidden;
        }

        .header {
            background-color: #2a9d8f;
            padding: 30px 20px 20px 20px;
            text-align: center;
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .header img {
            max-width: 120px;
            margin-bottom: 15px;
        }

        .content {
            padding: 30px 40px;
            font-size: 16px;
            line-height: 1.6;
        }

        .content h1 {
            color: #264653;
            margin-top: 0;
            font-weight: 700;
            font-size: 24px;
        }

        .details-list {
            background: #f9f9f9;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
            list-style: none;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);
        }

        .details-list li {
            margin-bottom: 10px;
            font-weight: 600;
            color: #2a9d8f;
        }

        a.btn-dashboard {
            display: inline-block;
            background-color: #e76f51;
            color: white !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 6px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(231, 111, 81, 0.5);
            transition: background-color 0.3s ease;
            margin-top: 20px;
            font-size: 16px;
        }

        a.btn-dashboard:hover {
            background-color: #d65a3a;
        }

        .footer {
            background-color: #f1f1f1;
            padding: 20px 40px;
            font-size: 13px;
            color: #777;
            text-align: center;
            margin-top: 30px;
            letter-spacing: 0.02em;
        }

        @media (max-width: 480px) {
            .email-container {
                width: 90% !important;
                margin: 20px auto;
                padding: 0;
            }

            .content {
                padding: 20px;
            }

            .header {
                font-size: 22px;
                padding: 20px 15px 15px 15px;
            }

            .header img {
                max-width: 100px;
            }

            a.btn-dashboard {
                padding: 12px 20px;
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container" role="main" aria-label="Confirmation d'abonnement SubSure">
        <div class="header"
            style="background-color: #2a9d8f; padding: 30px 20px 20px 20px; text-align: center; color: #fff; font-size: 28px; font-weight: 700; letter-spacing: 1px;">
            <img src="http://localhost/subsure.com/assets/images/logo_message.png" alt="Logo SubSure"
                style="max-width: 120px; margin-bottom: 15px;" />
            En attente de paiement de l'abonnement
        </div>

        <div class="content">
            <h1>Bonjour {{ $nomClient }},</h1>
            <p>Merci pour votre abonnement au plan <strong>#{{ $planId }}</strong>.</p>
            <p>Voici les détails :</p>
            <ul class="details-list" role="list">
                <li>Prix : {{ number_format($prix, 2) }} USD</li>
                <li>Période : du {{ $dateDebut }} au {{ $dateFin }}</li>
                <li>Statut : {{ ucfirst($statut) }}</li>
            </ul>
            <p>Vous pouvez consulter vos abonnements et gérer vos services en cliquant sur le bouton ci-dessous :</p>
            <a href="{{ $lienAbonnements }}" class="btn-dashboard" target="_blank" rel="noopener">Tableau de Bord</a>
            <p style="margin-top: 30px;">Merci de votre confiance,<br>L’équipe <strong>SubSure</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SubSure. Tous droits réservés.
        </div>
    </div>
</body>

</html>