<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #1e3a8a;
            padding: 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
        }
        .content p {
            margin: 0 0 15px;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #3498db;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #1e3a8a;
        }
        .footer {
            background-color: #eef2ff;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Réinitialisation de mot de passe</h1>
    </div>
    <div class="content">
        <p>Bonjour,</p>
        <p>Vous avez demandé à réinitialiser votre mot de passe. Cliquez sur le bouton ci-dessous pour continuer :</p>
        <p style="text-align: center;">
            <a href="{{ $url }}" class="button">Réinitialiser mon mot de passe</a>
        </p>
        <p>Ce lien expire dans 60 minutes. Si vous n’avez pas fait cette demande, vous pouvez ignorer cet email en toute sécurité.</p>
        <p>Cordialement,<br>L’équipe de Gestion des Cours</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Gestion des Cours. Tous droits réservés.</p>
    </div>
</div>
</body>
</html>
