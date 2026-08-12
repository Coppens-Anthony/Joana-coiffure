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
    </style>
</head>
<body>

<div class="container">

    <h1>Annulation rendez-vous</h1>

    <p>Le rendez-vous de {{ $appointment->client->name }} du {{ $appointment->formatDate('start_at') . ' à ' . $appointment->start_at->format('H:i') }} vient d'être annulé.</p>
</div>

</body>
</html>
