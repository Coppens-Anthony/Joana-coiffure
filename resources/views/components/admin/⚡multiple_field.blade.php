<?php

use Livewire\Attributes\Modelable;
use Livewire\Component;

new class extends Component {
    #[Modelable]
    public array $value = [];
    public string $label;
    public array $items;

    public string $search = '';

    public function getFilteredItemsProperty(): array
    {
        if (empty($this->search)) return $this->items;

        return array_values(array_filter(
            $this->items,
            fn($item) => str_starts_with(strtolower($item['label']), strtolower($this->search))
        ));
    }

    public function getSelectedLabelsProperty(): string
    {
        return collect($this->items)
            ->whereIn('id', $this->value)
            ->pluck('label')
            ->join(', ');
    }
};
?>

<div class="relative" x-data="{ open: false }" @focusout="if (!$el.contains($event.relatedTarget)) open = false">
    <div class="flex flex-col gap-2">
        <p>{{ $label }} <span class="text-error">*</span></p>
        <div @click="open = !open"
             class="border-2 w-full border-primary p-4 rounded-2xl focus:border-primary-2 focus:outline-none relative">
            <span class="{{ empty($value) ? 'opacity-50' : '' }}">
                {{ empty($value) ? 'Sélectionner' : $this->selectedLabels }}
            </span>
            <svg class="w-4 h-4 opacity-50 transition-transform absolute -translate-y-1/2 top-1/2 right-4"
                 :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

    <div x-show="open" x-cloak
         class="absolute max-h-75 top-24 left-0 border border-black rounded-2xl w-full flex flex-col z-50 bg-white overflow-x-hidden">

        <div class="p-4">
            <input
                type="text"
                wire:model.live.debounce.150ms="search"
                placeholder="Rechercher..."
                class="border-2 w-full border-primary p-2 rounded-2xl focus:border-primary-2 focus:outline-none"
                x-ref="searchInput"
                x-init="$watch('open', val => val && $nextTick(() => $refs.searchInput.focus()))"
            >
        </div>

        <ul class="overflow-y-scroll flex items-center gap-4 md:grid md:grid-cols-2 p-4">
            @forelse($this->filteredItems as $item)
                <li wire:key="item-{{ $item['id'] }}">
                    <div class="flex gap-2 items-center md:w-1/2">
                        <input
                            type="checkbox"
                            id="cb-{{ $item['id'] }}"
                            wire:model.live="value"
                            value="{{ $item['id'] }}"
                            class="accent-primary w-4 h-4 cursor-pointer"
                        >
                        <label for="cb-{{ $item['id'] }}" tabindex="-1">
                            {{ $item['label'] }}
                        </label>
                    </div>
                </li>
            @empty
                <li class="p-4 text-center md:col-span-2">Aucun résultat</li>
            @endforelse
        </ul>
    </div>
</div>
