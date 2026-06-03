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

    <h1>Nouveau rendez-vous</h1>

    <p>Vous avez un nouveau rendez-vous
        le {{ $appointment->formatDate('start_at') . ' à ' . $appointment->start_at->format('H:i') }}.</p>

    <table class="box">
        <tr>
            <td class="box-cell">
                <p class="label">Nom du client&nbsp;:</p>
                <p>{{ $appointment->client->name }}</p>
            </td>
        </tr>
        <tr>
            <td class="box-cell">
                <p class="label">Email du client&nbsp;:</p>
                <p>{{ $appointment->client->email }}</p>
            </td>
        </tr>
        <tr>
            <td class="box-cell">
                <p class="label">Téléphone du client&nbsp;:</p>
                <p>{{ $appointment->client->telephone }}</p>
            </td>
        </tr>
    </table>
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
</div>
</body>
</html>
