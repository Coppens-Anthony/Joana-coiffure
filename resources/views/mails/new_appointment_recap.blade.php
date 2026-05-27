<!DOCTYPE html>
<html lang="{!! App::getLocale() !!}">
<head>
    <meta charset="UTF-8">
    <title>Confirmation du rendez-vous</title>
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

    <h1>Confirmation rendez-vous</h1>

    <p>Le {{ $appointment->formatDate('start_at') . ' à ' . $appointment->start_at->format('H:i') }} à l'adresse suivante : Rue de Station 57, Orp-Jauche 1350</p>
    <p>Je vous remerci d'avoir choisi mes services. Le paiement se fera sur place et en liquide. Voici un récapitulatif de votre rendez-vous.</p>

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
