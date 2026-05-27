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

        .box {
            background: #FEEDBF;
            padding: 16px;
            border-radius: 16px;
            margin-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            list-style: none;
        }

        .label {
            font-weight: bold;
            margin: 0 0 4px;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>Annulation rendez-vous</h1>

    <p>Je suis réellement désolée, mais pour des raisons personnelles, je suis dans l'obligation d'annuler votre rendez-vous du {{ $appointment->formatDate('start_at') . ' à ' . $appointment->start_at->format('H:i') }}.</p>
    <p>Je vous présente mes plus plates excuses.</p>

</div>

</body>
</html>
