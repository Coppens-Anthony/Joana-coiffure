<!doctype html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Joana-coiffure --- Statistiques</title>
</head>
<body>
    <h1>Stats</h1>
    <main>
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-8">
            <h2 class="sr-only">Statistques pour le bilan</h2>
            <article class="w-full shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl">
                <h3 class="text-2xl rounded-t-2xl bg-tertiary py-6 w-full text-center">Rendez-vous</h3>
                <div class="rounded-b-2xl bg-white my-6 mx-4">
                    <ul class="flex flex-col gap-4">
                        <li>{{ $appointments->count() }} rendez-vous</li>
                    </ul>
                </div>
            </article>
            <article class="w-full shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl">
                <h3 class="text-2xl rounded-t-2xl bg-tertiary py-6 w-full text-center">Clients</h3>
                <div class="rounded-b-2xl bg-white my-6 mx-4">
                    <ul class="flex flex-col gap-4">
                        <li>{{ $totalClients->count() }} clients</li>
                        <li>{{ $recurringClients->count() }} clients réccurents</li>
                        <li>{{ $newClients->count() }} nouveaux clients</li>
                    </ul>
                </div>
            </article>
            <article class="w-full shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl">
                <h3 class="text-2xl rounded-t-2xl bg-tertiary py-6 w-full text-center">Revenus</h3>
                <div class="rounded-b-2xl bg-white my-6 mx-4">
                    <ul class="flex flex-col gap-4">
                        <li>{{ number_format($totalRevenue, 0, '', ' ') }}€ de revenu total</li>
                        <li>{{ number_format($averageRevenue, 2, ',', ' ') }}€ de revenu moyen par rendez-vous</li>
                    </ul>
                </div>
            </article>
            <article class="w-full shadow-[0_0_10px_rgba(0,0,0,0.25)] rounded-2xl">
                <h3 class="text-2xl rounded-t-2xl bg-tertiary py-6 w-full text-center">Prestations</h3>
                <div class="rounded-b-2xl bg-white my-6 mx-4">
                    <ul class="flex flex-col gap-4">
                        <li>
                            @if($mostRequestedService)
                                {{ $mostRequestedService['name'] }} est la prestation la plus demandée
                            @else
                                Aucune prestation ce mois-ci
                            @endif
                        </li>
                    </ul>
                </div>
            </article>

        </section>
    </main>
</body>
</html>
