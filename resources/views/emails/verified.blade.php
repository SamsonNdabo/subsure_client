{{-- resources/views/email/verified.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Email Vérifié - SubSure</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            padding: 40px;
            text-align: center;
        }
        .container {
            max-width: 500px;
            background: #fff;
            margin: 0 auto;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2a9d8f;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        a.btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #2a9d8f;
            color: white;
            text-decoration: none;
            font-weight: 600;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        a.btn:hover {
            background-color: #21867a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Votre email est vérifié ! 🎉</h1>
        <p>Merci d'avoir confirmé votre adresse email.<br>Vous pouvez maintenant vous connecter et profiter pleinement de SubSure.</p>
        <a href="{{ url('/logReg') }}" class="btn">Se connecter</a>
    </div>
</body>
</html>
