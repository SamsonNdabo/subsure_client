<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bienvenue sur SubSure</title>
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
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid #e1e4e8;
        }
        .header {
            background-color: #2a9d8f;
            padding: 20px;
            color: #fff;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 30px 40px;
            font-size: 16px;
            line-height: 1.5;
        }
        .content h2 {
            color: #264653;
            margin-top: 0;
        }
        .btn {
            display: inline-block;
            background-color: #e76f51;
            color: white !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 25px;
            box-shadow: 0 2px 6px rgba(231,111,81,0.5);
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #d65a3a;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 15px 40px;
            font-size: 12px;
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
            }
            .header {
                font-size: 20px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            SubSure - Bienvenue !
        </div>
        <div class="content">
            <h2>Bonjour {{ $name }},</h2>
            <p>Merci de vous être inscrit sur <strong>SubSure</strong>. Nous sommes ravis de vous accueillir parmi nous !</p>
            <p>Vous pouvez dès maintenant profiter pleinement de nos services et découvrir tout ce que nous avons à vous offrir.</p>
            
            <a href="{{ url('/logReg') }}" class="btn" target="_blank" rel="noopener">Connectez-Vous</a>

            <p style="margin-top: 30px; font-size: 14px; color: #555;">
                Si vous avez des questions ou besoin d’aide, n’hésitez pas à nous contacter.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SubSure. Tous droits réservés.
        </div>
    </div>
</body>
</html>
