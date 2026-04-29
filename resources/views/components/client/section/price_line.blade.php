@props(['name', 'duration', 'price', 'desc'])

<div class="flex justify-between">
    <p>{{ $name }}</p>
    <p>{{ $duration }} minutes / {{ $price }}€</p>
</div>
<small class="text-[0.75rem] italic">{{ $desc }}</small>
