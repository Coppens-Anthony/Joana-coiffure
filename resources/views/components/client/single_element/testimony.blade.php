@props(['item' => []])

<article class="flex flex-col gap-8 h-full">
    <img src="{{ asset('assets/svg/testimony.svg') }}" alt="" class="w-16" width="72" height="56">
    <p class="border-b border-black pb-8 flex-1">{{ $item['content'] }}</p>
    <h3>{{ $item['person'] }}</h3>
</article>
