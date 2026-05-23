@props(['title', 'duration', 'price', 'desc'])

<article class="p-8 rounded-3xl shadow-[0_0_10px_rgba(0,0,0,0.1)]">
    <div class="flex justify-between mb-2">
        <h3>{{ $title }}</h3>
        <p>{{ $duration }} / {{ $price }}€</p>
    </div>
    <small class="text-[.875rem] italic">{{ $desc }}</small>
</article>
