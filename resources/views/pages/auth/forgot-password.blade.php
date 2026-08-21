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
        <div class="mb-8">
            <h1 class="text-[2rem]">Mot de passe oublié</h1>
            <p>Recevez un lien de réinitialisation par email.</p>
        </div>
        @if (session('status'))
            <div class="m-auto w-full rounded-3xl p-8 h-fit text-center">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h2 class="text-2xl font-bold  mb-2">
                    Votre demande a bien été envoyé&nbsp;!
                </h2>
                <p>
                    Consultez vos mails afin de pouvoir réinitialiser votre mot de passe
                </p>
            </div>
        @else
            <form action="{{ route('password.email') }}" method="post" class="flex flex-col gap-4">
                @csrf
                <x-global.form.input name="email" type="email" placeholder="john@doe.com">
                    Email
                </x-global.form.input>
                <small class="text-[.875rem] mb-8">
                    <span class="text-error">*</span>
                    Champs obligatoires
                </small>
                <x-global.link-button.button title="Envoyer le lien">Envoyer le lien</x-global.link-button.button>
            </form>
        @endif

    </div>
</main>
</body>
</html>
