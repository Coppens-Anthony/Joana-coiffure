@props(['item' => []])

<article class="flex flex-col gap-8">
    <img src="{{ asset('assets/svg/testimony.svg') }}" alt="" class="w-16">
    <p class="border-b border-black pb-8">{{ $item['content'] }}</p>
    <h3>{{ $item['person'] }}</h3>
</article>
