<!DOCTYPE html>
<html lang="{!! App::getLocale() !!}">
<head>
    <meta charset="UTF-8">
    <title>Nouveau message de contact</title>
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

    <h1>Nouveau message de contact</h1>

    <p>Vous avez reçu un message depuis le formulaire de contact.</p>

    <table class="box">
        <tr>
            <td class="box-cell">
                <p class="label">Nom&nbsp;:</p>
                <p>{{ $validated['name'] }}</p>
            </td>
        </tr>
        <tr>
            <td class="box-cell">
                <p class="label">Email&nbsp;:</p>
                <p>{{ $validated['email'] }}</p>
            </td>
        </tr>
        <tr>
            <td class="box-cell">
                <p class="label">Téléphone&nbsp;:</p>
                <p>{{ $validated['telephone'] }}</p>
            </td>
        </tr>
        <tr>
            <td class="box-cell">
                <p class="label">Message&nbsp;:</p>
                <p>{{ $validated['message'] }}</p>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
