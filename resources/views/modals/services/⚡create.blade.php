<?php

use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public Service $service;
    public string $name = '';
    public ?int $duration = null;
    public ?int $price = null;
    public string $desc = '';

    public function mount(?string $model_id = null)
    {
        if ($model_id) {
            $this->service = Service::findOrFail($model_id);
            $this->name = $this->service->name;
        }
    }

    public function create()
    {
        $validated = $this->validate([
            'name' => 'required|unique:services,name',
            'duration' => 'required|integer',
            'price' => 'required|integer',
            'desc' => 'required',
        ]);

        Service::create($validated);
        $this->dispatch('action_done');
        $this->dispatch('close_modal');
    }
};
?>


<div wire:click="dispatch('close_modal')"
     @keydown.escape.window="$wire.dispatch('close_modal')"
     x-trap.inert.noscroll="true"
     class="p-8 w-fit mx-auto fixed top-1/2 left-1/2 z-50 bg-white -translate-1/2 rounded-3xl shadow-[0_0_10px_rgba(0,0,0,0.25)]"
>
    <div class="flex justify-between items-center mb-8">
        <p class="text-[2rem]">Ajouter une presation</p>
        <button type="button" class="cursor-pointer">
            <img src="{{ asset('assets/svg/close.svg') }}" class="hover:rotate-90" alt="Fermer la modale" wire:click="dispatch('close_modal')">
        </button>
    </div>
    <form wire:click.stop wire:submit="create" class="flex flex-col gap-4">
        @csrf
        <x-global.form.input name="name" wire:model="name" placeholder="Permanente" :isRequired="true">
            Nom
        </x-global.form.input>

        <div class="flex gap-8">
            <x-global.form.input type="number" name="duration" wire:model="duration" placeholder="60" :isRequired="true">
                Durée moyenne (en min.)
            </x-global.form.input>
            <x-global.form.input type="number" name="price" wire:model="price" placeholder="50" :isRequired="true">
                Prix
            </x-global.form.input>
        </div>
        <x-global.form.textarea rows="1" name="desc" wire:model.live="desc" :isRequired="true">
            Description
        </x-global.form.textarea>

        <div class="ml-auto w-fit flex gap-6">
            <x-global.linkButton.button type="button" title="Fermer la modale" :isSecondary="true" wire:click="dispatch('close_modal')">
                Annuler
            </x-global.linkButton.button>
            <x-global.linkButton.button title="Ajouter la nouvelle prestation">
                Ajouter
            </x-global.linkButton.button>
        </div>
    </form>
</div>
