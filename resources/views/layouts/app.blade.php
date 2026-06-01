<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/svg/logo.svg') }}">
    <title>{{ $title ?? config('app.name') . ' --- Administration' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="overflow-x-hidden mx-auto font-sans text-black">
<main class="md:flex md:flex-row-reverse md:gap-8">
    <div class="md:flex-1 md:mr-8 py-8 mx-8 md:mx-auto">
        <h1 class="text-[2rem] mb-8">{{ $title }}</h1>
        {{ $slot }}
    </div>
    <livewire:admin.sidebar/>
</main>
<livewire:widgets::modal/>
</body>
</html>
