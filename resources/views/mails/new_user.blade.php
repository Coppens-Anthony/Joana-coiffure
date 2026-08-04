<!DOCTYPE html>
<html lang="{!! App::getLocale() !!}">
<head>
    <meta charset="UTF-8">
    <title>Annulation rendez-vous</title>
    <style>
        body {
            font-family: Poppins, Arial, sans-serif;
            padding: 32px;
            color: black;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        .button {
            margin-top: 32px;
            display: inline-block;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 9999px;
            font-weight: 600;
            background: #FD9BC2;
            color: black !important;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>Invitation à activer votre Compte</h1>

    <p>Bonjour, nous vous invitons à compléter vos informations personnelles via le bouton ci-dessous afin d'activer votre
        compte !</p>

    <small>Vous avez 7 jours afin d'activer votre compte sinon quoi, l'opération devra être recommencée.</small>

    <a href="{{ URL::temporarySignedRoute('invitation.create', now()->addDays(7), ['email' => $email]) }}" class="button">Remplir mes informations</a>
</div>

</body>
</html>
