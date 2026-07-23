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
            width: 100%;
            border-collapse: separate;
        }

        .box-cell {
            padding: 0 0 16px 0;
            vertical-align: top;
        }

        .box-cell:last-child {
            padding-bottom: 0;
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

    <p>Le {{ $appointment->formatDate('start_at') . ' à ' . $appointment->start_at->format('H:i') }} à l'adresse
        suivante&nbsp;: Rue de Station 57, Orp-Jauche 1350</p>
    <p>Je vous remerci d'avoir choisi mes services. Le paiement se fera sur place et en trquide. Voici un récapitulatif
        de votre rendez-vous.</p>

    <table class="box">
        <tr>
            <td class="box-cell">
                <p class="label">Prestation(s)&nbsp;:</p>
                <p>{!! $appointment->services->pluck('name')->implode(', ') !!}</p>
            </td>
        </tr>
        <tr>
            <td class="box-cell">
                <p class="label">Durée moyenne&nbsp;:</p>
                <p>{{ $appointment->durationFormat($appointment->services->sum('duration')) }}</p>
            </td>
        </tr>
        <tr>
            <td class="box-cell">
                <p class="label">Prix&nbsp;:</p>
                <p>{{ $appointment->services->sum('price') }}€</p>
            </td>
        </tr>
        @if($appointment->message)
            <tr>
                <td class="box-cell">
                    <p class="label">Message&nbsp;:</p>
                    <p>{{ $appointment->message }}</p>
                </td>
            </tr>
        @endif
    </table>
    <a href="{{route('appointment_cancel.view', $appointment->uuid)}}">Annuler</a>
</div>

</body>
</html>
