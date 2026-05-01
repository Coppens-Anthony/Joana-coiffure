@props(['name', 'duration', 'price', 'desc', 'isAppointment' => false])

<div class="flex flex-col gap-4 w-full">
    <div class="flex justify-between">
        <p>{{ $name }}</p>
        <p>{{ $duration }} / {{ $price }}€</p>
    </div>
    <small class="text-[0.75rem] italic">{{ $desc }}</small>
</div>
@if($isAppointment)
    <x-global.button type="button" title="Sélectionner la prestation">Sélectionner</x-global.button>
@endif
