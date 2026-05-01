@props(['icon_path', 'icon_alt' => '', 'route', 'title', 'isActive' => false, 'class' => ''])

<div class="flex gap-2 items-center {{ $class }}">
    <img src="{{ asset($icon_path) }}" alt="{{ $icon_alt }}" class="w-8 h-8">
    <x-global.link :route="$route" :title="$title" :isActive="$isActive">{{ $slot }}</x-global.link>
</div>
