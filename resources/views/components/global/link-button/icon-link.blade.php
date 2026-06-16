@props(['icon_path', 'icon_alt' => '', 'route', 'title', 'isActive' => false, 'class' => '', 'tabindex' => '0', 'noSvg' => false])

<div class="flex gap-2 items-center {{ $class }}">
    <img src="{{ asset($icon_path) }}" alt="{{ $icon_alt }}" class="w-8 h-8">
    <x-global.link-button.link :noSvg="$noSvg" :route="$route" :title="$title" :isActive="$isActive" tabindex="{{ $tabindex }}">{{ $slot }}</x-global.link-button.link>
</div>
