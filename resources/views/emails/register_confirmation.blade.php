<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Vérification de votre email</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; color: #333; padding: 20px; }
        .container { max-width: 600px; background: #fff; margin: 0 auto; padding: 30px; border-radius: 8px; }
        a.btn { display: inline-block; padding: 12px 24px; background: #2a9d8f; color: white; text-decoration: none; border-radius: 6px; }
        a.btn:hover { background: #21867a; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bonjour {{ $user['nom'] ?? 'utilisateur' }},</h1>
        <p>Merci de vous être inscrit sur SubSure.</p>
        <p>Veuillez confirmer votre adresse email en cliquant sur le bouton ci-dessous :</p>
        <p>
            <a href="{{ $verificationUrl }}" class="btn" target="_blank" rel="noopener">Vérifier mon email</a>
        </p>
        <p>Si vous n'avez pas créé de compte, ignorez ce message.</p>
        <p>Merci,<br>L'équipe SubSure</p>
    </div>
</body>
</html>
