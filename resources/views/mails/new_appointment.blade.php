<!DOCTYPE html>
<html lang="{!! App::getLocale() !!}">
<head>
    <meta charset="UTF-8">
    <title>Nouveau rendez-vous</title>
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

    <h1>Nouveau rendez-vous</h1>

    <p>Vous avez un nouveau rendez-vous
        le {{ $appointment->formatDate('start_at') . ' à ' . $appointment->start_at->format('H:i') }}.</p>

    <ol class="box">
        <li>
            <p class="label">Nom du client :</p>
            <p>{{ $appointment->client->name }}</p>
        </li>
        <li>
            <p class="label">Email du client :</p>
            <p>{{ $appointment->client->email }}</p>
        </li>
        <li>
            <p class="label">Téléphone du client :</p>
            <p>{{ $appointment->client->telephone }}</p>
        </li>
    </ol>
    <ol class="box">
        <li>
            <p class="label">Prestation(s) :</p>
            <p>{!! $appointment->services->pluck('name')->implode(', ') !!}</p>
        </li>
        <li>
            <p class="label">Durée moyenne :</p>
            <p>{{ $appointment->durationFormat($appointment->services->sum('duration')) }}</p>
        </li>
        <li>
            <p class="label">Prix :</p>
            <p>{{ $appointment->services->sum('price') }}€</p>
        </li>
        @if($appointment->message)
            <li>
                <p class="label">Message :</p>
                <p>{{ $appointment->message }}</p>
            </li>
        @endif
    </ol>
</div>

</body>
</html>
