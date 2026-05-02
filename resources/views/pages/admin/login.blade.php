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
<main class="min-h-screen w-full flex items-center justify-center">
    <div class="p-8 md:w-1/3 mx-auto md:shadow-[0_0_10px_rgba(0,0,0,0.25)] md:rounded-2xl">
        <h1 class="text-[2rem] mb-16">Se connecter</h1>
        <form action="{{ route('login.store') }}" method="post" class="flex flex-col gap-4">
            @csrf
            <x-global.form.input name="email" type="email" placeholder="john@doe.com">
                Email
            </x-global.form.input>
            <x-global.form.input name="password" type="password">
                Mot de passe
            </x-global.form.input>
            <x-global.form.checkbox name="remember">
                Rester connecté(e)
            </x-global.form.checkbox>
            <small class="text-[0.75rem] mb-8">
                <span class="text-error">*</span>
                Champs obligatoires
            </small>
            <x-global.linkbutton.button title="Connectez-vous">Se connecter</x-global.linkbutton.button>
        </form>
    </div>
</main>
</body>
</html>
