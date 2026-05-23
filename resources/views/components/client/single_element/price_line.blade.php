@props(['isAppointment' => false, 'service', 'selectedServices' => []])

<div class="flex flex-col gap-4 w-full">
    <div class="flex justify-between">
        <p>{{ $service->name }}</p>
        <p>{{ $service->durationFormat($service->duration) }} / {{ $service->price }}€</p>
    </div>
    <small class="text-[.875rem] italic">{{ $service->desc }}</small>
</div>
@if($isAppointment)
    <div class="flex flex-row gap-2 items-center">
        <label for="{{ $service->id }}" tabindex="0"
               class="service-label px-8 py-4 duration-200 w-fit rounded-full cursor-pointer bg-primary hover:bg-primary-2">
            Sélectionner
        </label>
        <input type="checkbox" name="services[]" id="{{ $service->id }}" value="{{ $service->id }}"
               data-name="{{ $service->name }}" data-price="{{ $service->price }}"
               data-duration="{{ $service->duration }}"
               {{ in_array($service->id, $selectedServices) ? 'checked' : '' }}
               class="service-checkbox js:hidden w-4 h-4 border-2 cursor-pointer border-primary accent-primary appearance-none rounded-sm checked:bg-primary transition relative checked:after:content-['✓'] checked:after:absolute checked:after:inset-0 checked:after:flex checked:after:items-center checked:after:justify-center checked:after:text-black">
    </div>
@endif
