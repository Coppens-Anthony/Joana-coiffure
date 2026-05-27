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

    <h1>Nouveau message de contact</h1>

    <p>Vous avez reçu un message depuis le formulaire de contact.</p>

    <ol class="box">
        <li>
            <p class="label">Nom :</p>
            <p>{{ $validated['name'] }}</p>
        </li>
        <li>
            <p class="label">Email :</p>
            <p>{{ $validated['email'] }}</p>
        </li>
        <li>
            <p class="label">Téléphone :</p>
            <p>{{ $validated['telephone'] }}</p>
        </li>
        <li>
            <p class="label">Message :</p>
            <p>{{ $validated['message'] }}</p>
        </li>
    </ol>
</div>

</body>
</html>
