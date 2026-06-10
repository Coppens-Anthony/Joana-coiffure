<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de mot de passe</title>
    <style>
        body {
            font-family: Poppins, Arial, sans-serif;
            padding: 32px;
            color: black;
            background: #f9f9f9;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 32px;
            border-radius: 16px;
        }

        .header {
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 1.5rem;
        }

        .header p {
            margin: 0;
            color: #444;
        }

        .box p {
            margin: 0 0 16px;
        }

        .button {
            display: inline-block;
            padding: 12px 32px;
            background-color: #FD9BC2;
            color: black !important;
            text-decoration: none;
            border-radius: 9999px;
            font-weight: 600;
            border: 2px solid #FD9BC2;
        }

        .divider {
            border: none;
            border-top: 1px solid #eee;
            margin: 24px 0;
        }

        .footer {
            font-size: 0.875rem;
            color: #888;
        }

        .footer p {
            margin: 0 0 8px;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>Réinitialisation de mot de passe</h1>
        <p>Bonjour <strong>{{ $userName }}</strong>,</p>
    </div>

    <p>Vous recevez cet email car une demande de réinitialisation de mot de passe a été effectuée pour votre compte. Si vous êtes bien à l'origine de cette demande, cliquez sur le bouton ci-dessous.</p>

    <div class="box">
        <p>Ce lien est valable pendant <strong>{{ $expireMinutes }} minutes</strong>. Passé ce délai, vous devrez effectuer une nouvelle demande.</p>
        <a href="{{ $resetUrl }}" class="button">Réinitialiser mon mot de passe</a>
    </div>

    <hr class="divider">

    <div class="footer">
        <p>Si vous n'avez pas demandé de réinitialisation, ignorez simplement cet email — votre mot de passe restera inchangé.</p>
        <p>Pour des raisons de sécurité, ne partagez jamais ce lien avec quelqu'un d'autre.</p>
    </div>

</div>
</body>
</html>
