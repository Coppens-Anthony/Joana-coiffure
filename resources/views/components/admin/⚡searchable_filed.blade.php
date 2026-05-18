<?php

use Livewire\Attributes\Modelable;
use Livewire\Component;

new class extends Component {
    #[Modelable]
    public ?int $value = null;
    public string $label;
    public array $items;
};
?>

<div
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
    class="relative"
>
    <x-global.form.input
        name="search"
        x-model="search"
        placeholder="Sélectionner"
        @click="open = true"
        @click.outside="open = false"
        @input="open = true; if (search === '') clear()"
    >
        {{ $label }}
    </x-global.form.input>

    <ul x-show="open"
        class="absolute max-h-75 top-24 left-0 border border-black rounded-2xl w-full flex flex-col bg-white overflow-x-hidden overflow-y-scroll"
        x-cloak>
        <template x-for="item in filteredItems" :key="item.id">
            <li class="cursor-pointer hover:bg-primary" @click="select(item)">
                <button type="button" x-text="item.label" class="focus:bg-primary focus:outline-none p-4 w-full text-start"></button>
            </li>
        </template>
    </ul>
</div>
