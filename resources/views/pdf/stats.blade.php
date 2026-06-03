<!doctype html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>Joana-coiffure --- Statistiques</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        h1 {
            text-align: center;
        }

        .container {
            width: 100%;
            margin-top: 32px;
            text-align: center;
        }

        .card {
            page-break-inside: avoid;
            width: 50%;
            display: inline-block;
            vertical-align: top;
            margin-bottom: 32px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            border: 1px solid black;
            border-radius: 16px;
        }

        .card-header {
            font-size: 24px;
            background-color: #FEEDBF;
            border: 1px solid #FEEDBF;
            padding: 16px;
            text-align: center;
        }

        .card-body {
            background: white;
            padding: 16px;
        }

        .card-body ul {
            padding-left: 16px;
        }

        .card-body li {
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

<h1>Statistiques sur la prériode
    de {{ $month == 0 ? $year : Carbon\Carbon::create($year, $month)->locale(App::getLocale())->translatedFormat('F Y')}} </h1>
<main class="container">

    <div class="card">
        <div class="card-header">Rendez-vous</div>
        <div class="card-body">
            <ul>
                <li>{{ $appointments->count() }} rendez-vous</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Clients</div>
        <div class="card-body">
            <ul>
                <li>{{ $totalClients->count() }} clients</li>
                <li>{{ $recurringClients->count() }} clients récurrents</li>
                <li>{{ $newClients->count() }} nouveaux clients</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Revenus</div>
        <div class="card-body">
            <ul>
                <li>{{ number_format($totalRevenue, 0, '', ' ') }}€ de revenu total</li>
                <li>{{ number_format($averageRevenue, 2, ',', ' ') }}€ de revenu moyen par rendez-vous</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Prestations</div>
        <div class="card-body">
            <ul>
                <li>
                    @if($mostRequestedService)
                        {{ $mostRequestedService['name'] }} est la prestation la plus demandée
                    @else
                        Aucune prestation durant cette période
                    @endif
                </li>
            </ul>
        </div>
    </div>
</main>
</body>
</html>
