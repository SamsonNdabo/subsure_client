<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; }
        .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Réinitialisation de mot de passe</h2>
    <p>Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe :</p>
    <a href="{{ route('password.reset', ['token' => $token]) }}" class="btn">Réinitialiser</a>
</div>
</body>
</html>
