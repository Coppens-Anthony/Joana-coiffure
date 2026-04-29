@props(['icon_path', 'icon_alt', 'route', 'title'])

<div class="flex gap-2 items-center">
    <img src="{{ asset($icon_path) }}" alt="{{ $icon_alt }}" class="w-8 h-8">
    <x-global.link :route="$route" :title="$title">{{ $slot }}</x-global.link>
</div>
