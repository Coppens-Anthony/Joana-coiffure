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
        <h1 class="text-[2rem] mb-8">Réinitialiser votre mot de passe</h1>
        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-4">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <x-global.form.input type="email" name="email" value="{{ request('email') }}">
                Email
            </x-global.form.input>
            <x-global.form.input type="password" name="password">
                Mot de passe
            </x-global.form.input>
            <x-global.form.input type="password" name="password_confirmation">
                Confirmer le mot de passe
            </x-global.form.input>

            <small class="text-[.875rem] mb-8">
                <span class="text-error">*</span>
                Champs obligatoires
            </small>
            <x-global.link-button.button title="Réinitialiser votre mot de passe">
                Réinitialiser
            </x-global.link-button.button>
        </form>
    </div>
</main>
</body>
</html>
