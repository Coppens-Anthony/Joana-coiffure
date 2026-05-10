@props(['name'])

<div class="flex gap-2 items-center">
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes }}
        class="w-6 h-6 border-2 border-primary accent-primary appearance-none rounded-sm checked:bg-primary transition relative checked:after:content-['✓'] checked:after:absolute checked:after:inset-0 checked:after:flex checked:after:items-center checked:after:justify-center checked:after:text-black">

    <label for="{{ $name }}">
        {{ $slot }}
    </label>
</div>
