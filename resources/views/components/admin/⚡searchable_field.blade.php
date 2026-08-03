<?php

use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    #[Modelable]
    public ?int $value = null;
    public bool $isClientAdding = false;
    public string $label;
    public array $items;

    #[On('client_created')]
    public function addClient($id, $name)
    {
        $this->items[] = [
            'id' => $id,
            'label' => $name,
        ];

        $this->value = $id;

        $this->dispatch('client_added', id: $id, name: $name);
    }

    public function create()
    {
        $this->dispatch('open_modal', ['modal' => 'modals::clients.create_edit']);
    }
};
?>

<div
    x-on:client_added.window="items.push({ id: $event.detail.id, label: $event.detail.name }); search = $event.detail.name;"

    x-data="{
        open: false,
        search: '',

        items: @js($items),

        get filteredItems() {
            return this.items.filter(
                item => item.label.toLowerCase().startsWith(this.search.toLowerCase())
            )
        },

        select(item) {
            $wire.value = item.id;
            this.search = item.label;
            this.open = false;
        },

        clear() {
            $wire.value = null;
            this.search = '';
        }
    }"
    class="relative" @focusout="if (!$el.contains($event.relatedTarget)) open = false"
>
    <div class="relative">
        <x-global.form.input
            autocomplete="off"
            name="search"
            x-model="search"
            placeholder="Sélectionner"
            @focus="open = true"
            @input="if (search === '') clear()"
        >
            {{ $label }}
        </x-global.form.input>
        <svg class="w-4 h-4 opacity-50 transition-transform absolute bottom-6 right-4"
             :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>

    <ul x-show="open"
        class="absolute max-h-75 top-24 left-0 border border-black rounded-2xl w-full flex flex-col z-50 bg-white overflow-x-hidden overflow-y-scroll"
        x-cloak>
        @if($isClientAdding)
            <li class="hover:bg-primary">
                <button type="button" class="focus:bg-primary cursor-pointer focus:outline-none p-4 w-full text-start"
                        wire:click="create">+ Ajouter un client
                </button>
            </li>
        @endif
        <template x-for="item in filteredItems" :key="item.id">
            <li class="cursor-pointer hover:bg-primary" @click="select(item)">
                <button type="button" x-text="item.label"
                        class="focus:bg-primary focus:outline-none p-4 w-full text-start"></button>
            </li>
        </template>
        <template x-if="filteredItems.length === 0">
            <li class="p-4 text-center">Aucun résultat</li>
        </template>
    </ul>
</div>
