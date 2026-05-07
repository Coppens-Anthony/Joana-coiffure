<!doctype html>
<html lang="{!! App::getLocale() !!}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Anthony Coppens">
    <meta name="keywords"
          content="Joana Monteiro, Coiffeuse, Coiffure, Orp-Jauche, Indépendante, Visagiste, À domicile, Orp-Le-Grand, Coupes, Rendez-vous, Chaleureux, Joana Coiffure">
    <meta name="description" content="Joana Monteiro, coiffeuse et visagiste indépendante à Orp-Jauche">
    <title>{{ config('app.name', 'Joana Coiffure') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden mx-auto font-sans text-black">
<h1 class="sr-only">{{ $title }}</h1>
<x-client.partial.header/>
<main>
    @if(request()->routeIs('home'))
        <x-client.section.top_banner/>
    @endif
    <div class="mx-8 md:mx-16 flex flex-col gap-32">
        {{$slot}}
    </div>
    @if(!$isContactOrAppointment)
        <x-client.section.bottom_banner/>
    @endif
</main>
<x-client.partial.footer/>
</body>
</html>
