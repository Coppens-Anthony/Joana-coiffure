<!DOCTYPE html>
<html lang="{!! App::getLocale() !!}">
<head>
    <meta charset="UTF-8">
    <title>Modification du rendez-vous</title>
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

        a[href] {
            color: white;
        }

        .actions {
            margin: 16px auto 0;
            border-collapse: separate;
            border-spacing: 12px 0;
        }

        .actions td {
            padding: 0;
        }

        .cancel,
        .edit {
            display: inline-block;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 9999px;
            font-weight: 600;
        }

        .cancel {
            background: #AC2022;
            color: white !important;
        }

        .edit {
            background: #FD9BC2;
            color: black !important;
        }
    </style>

</head>
<body>

<div class="container">

    <h1>Modification de rendez-vous</h1>

    <p>Votre rendez-vous a été modifié. Voici un récapitulatif de votre rendez-vous :</p>
    <p>Le {{ $appointment->formatDate('start_at') . ' à ' . $appointment->start_at->format('H:i') }} à l'adresse
        suivante&nbsp;: Rue de Station 57, Orp-Jauche 1350</p>

    <table class="box">
        <tr>
            <td class="box-cell">
                <p class="label">Coiffeur.se&nbsp;:</p>
                <p>{!! $appointment->user->name !!}</p>
            </td>
        </tr>
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
    <table class="actions" role="presentation">
        <tr>
            <td>
                <a href="{{ route('appointment_edit', $appointment) }}" class="edit">
                    Modifier mon rendez-vous
                </a>
            </td>
            <td>
                <a href="{{ route('appointment_cancel.view', $appointment->uuid) }}" class="cancel">
                    Annuler mon rendez-vous
                </a>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
