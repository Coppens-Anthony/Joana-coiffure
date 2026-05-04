<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') . ' --- Administration' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden mx-auto font-sans text-black">
<main class="flex gap-8">
    <livewire:admin.sidebar/>
    <div class="flex-1 mr-8 py-8">
        <h1 class="text-[2rem] mb-8">{{ $title }}</h1>
        {{ $slot }}
    </div>
</main>
<livewire:widgets::modal/>
</body>
</html>
