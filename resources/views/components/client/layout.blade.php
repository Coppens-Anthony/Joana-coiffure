<!doctype html>
<html lang="{!! App::getLocale() !!}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Joana Coiffure') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden max-w-350 mx-auto font-sans text-black">
<h1 class="sr-only">{{ $title }}</h1>
<x-client.partial.header/>
<main>
    @if(request()->routeIs('home'))
        <section
            class="bg-[url(../../public/assets/img/hero.png)] bg-no-repeat bg-cover max-w-screen h-[calc(100vh-120px)] mb-32 flex items-center justify-center">
            <div class="text-center flex flex-col gap-16 items-center">
                <h2 class="text-[4rem] font-bold text-white">Joana Coiffure</h2>
                <p class="text-[2rem] text-white">Coiffeuse & visagiste indépendante à Orp-Jauche</p>
                <div class="flex gap-4 mx-auto w-fit">
                    <x-global.link_button :route="route('contact')" title="Vers la page de contact">Contact
                    </x-global.link_button>
                    <x-global.link_button :route="route('appointment')" title="Prendre rendez-vous" :isSecondary="true">
                        Rendez-vous
                    </x-global.link_button>
                </div>
            </div>
        </section>
    @endif
    <div class="mx-8 md:mx-16 flex flex-col gap-32">
        {{$slot}}
    </div>

</main>
<x-client.partial.footer/>
</body>
</html>
