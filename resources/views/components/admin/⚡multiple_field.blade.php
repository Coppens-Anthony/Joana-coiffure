<?php

use App\Models\Service;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    #[Modelable]
    public array $value = [];
    public string $label;
    public array $items;

    public function create()
    {
        $this->dispatch('open_modal', ['modal' => 'modals::services.create_edit', 'params' => ['isAppointment' => true]]);
    }

    #[On('service_created')]
    public function addService($id, $name)
    {
        $this->value[] = $id;

        $this->items = Service::orderBy('name')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'label' => $s->name,
            ])
            ->toArray();

        $this->dispatch('services_updated', items: $this->items);
    }
};
?>

<div class="relative"
     x-on:services_updated.window="items = $event.detail.items"
     x-data="{
        open: false,
        search: '',

        items: @entangle('items'),
        value: @entangle('value'),

        get filteredItems() {
            return this.items.filter(
                item => item.label.toLowerCase().startsWith(this.search.toLowerCase())
            )
        },

        get selectedLabels() {
            return this.items
                .filter(item => this.value.includes(item.id))
                .map(item => item.label)
                .join(', ')
        },

        toggle(id) {
            if (this.value.includes(id)) {
                this.value = this.value.filter(v => v !== id);
            } else {
                this.value = [...this.value, id];
            }
        }
    }"
     @focusout="if (!$el.contains($event.relatedTarget)) open = false"
>
    <div class="flex flex-col gap-2">
        <p>{{ $label }} <span class="text-error">*</span></p>
        <div @click="open = true"
             @focus="open = true"
             tabindex="0"
             class="border-2 w-full border-primary p-4 rounded-2xl focus:border-primary focus:outline-none relative cursor-pointer">
            <span :class="value.length === 0 ? 'opacity-50' : ''"
                  x-text="value.length === 0 ? 'Sélectionner' : selectedLabels">
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
                x-model="search"
                placeholder="Rechercher..."
                class="border-2 w-full border-primary p-2 rounded-2xl focus:border-primary focus:outline-none"
                x-ref="searchInput"
                x-init="$watch('open', val => val && $nextTick(() => $refs.searchInput.focus()))"
            >
        </div>

        <ul class="overflow-y-scroll flex items-center gap-4 md:grid md:grid-cols-2 p-4">
            <li class="hover:bg-primary rounded-xl">
                <button type="button" class="focus:bg-primary cursor-pointer focus:outline-none p-2 w-full text-start"
                        wire:click="create">+ Ajouter un service
                </button>
            </li>
            <template x-for="item in filteredItems" :key="item.id">
                <li>
                    <div class="flex gap-2 items-center w-full">
                        <input
                            type="checkbox"
                            :id="'cb-' + item.id"
                            :checked="value.includes(item.id)"
                            @change="toggle(item.id)"
                            class="accent-primary w-4 h-4 cursor-pointer"
                        >
                        <label :for="'cb-' + item.id" x-text="item.label" tabindex="-1"></label>
                    </div>
                </li>
            </template>
            <template x-if="filteredItems.length === 0">
                <li class="p-4 text-center md:col-span-2">Aucun résultat</li>
            </template>
        </ul>
    </div>
</div>
