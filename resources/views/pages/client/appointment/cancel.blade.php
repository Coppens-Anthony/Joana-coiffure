<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<main>
    <h1>Annuler le rendez-vous</h1>
    <form action="{{ route('appointment_cancel', $appointment) }}" method="post">
        @csrf
        <button>Annuler le rendez-vous</button>
    </form>
</main>
</body>
</html>
