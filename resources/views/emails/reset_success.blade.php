<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mot de passe changé - SubSure</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            border: 1px solid #e1e4e8;
            overflow: hidden;
        }
        .header {
            background-color: #2dce89;
            padding: 25px 20px 15px 20px;
            text-align: center;
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
            position: relative;
        }
        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .content {
            padding: 35px 40px;
            font-size: 17px;
            line-height: 1.6;
            text-align: center;
        }
        .content h2 {
            color: #264653;
            margin-top: 0;
            font-weight: 700;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 20px 40px;
            font-size: 13px;
            color: #777;
            text-align: center;
        }
        @media (max-width: 480px) {
            .email-container {
                width: 90% !important;
                margin: 20px auto;
                padding: 0;
            }
            .content {
                padding: 20px;
                font-size: 16px;
            }
            .header {
                font-size: 22px;
                padding: 20px 15px 10px 15px;
            }
            .header img {
                max-width: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('assets/images/logo-footer.png') }}" alt="SubSure Logo" />
            <div>SubSure - Mot de passe changé</div>
        </div>
        <div class="content">
            <h2>Votre mot de passe a été réinitialisé avec succès.</h2>
            <p>Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SubSure. Tous droits réservés.
        </div>
    </div>
</body>
</html>
