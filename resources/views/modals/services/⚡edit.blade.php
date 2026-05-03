<?php

use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public Service $service;
    public string $name = '';
    public ?int $duration = null;
    public ?int $price = null;
    public string $desc = '';


    public function mount(string $model_id)
    {
        if ($model_id) {
            $this->service = Service::findOrFail($model_id);
            $this->name = $this->service->name;
            $this->duration = $this->service->duration;
            $this->price = $this->service->price;
            $this->desc = $this->service->desc;
        }
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required|unique:services,name,' . $this->service->id,
            'duration' => 'required|integer',
            'price' => 'required|integer',
            'desc' => 'required',
        ]);

        $this->service->update($validated);
        $this->dispatch('action_done');
        $this->dispatch('close_modal');
    }
};
?>


<div
    wire:click="dispatch('close_modal')"
    @keydown.escape.window="$wire.dispatch('close_modal')"
    class="fixed inset-0 z-50 bg-black/50"
>
    <div
        wire:click.stop
        x-trap.inert.noscroll="true"
        class="p-8 w-fit fixed top-1/2 left-1/2 bg-white transform -translate-x-1/2 -translate-y-1/2 rounded-3xl shadow-[0_0_10px_rgba(0,0,0,0.25)]"
    >
        <div class="flex justify-between items-center mb-8">
            <p class="text-[2rem]">Modifier la presation</p>
            <button
                type="button"
                class="cursor-pointer group"
                wire:click="dispatch('close_modal')">
                <img
                    src="{{ asset('assets/svg/close.svg') }}"
                    class="block transition-transform duration-200 group-hover:rotate-90"
                    alt="Fermer la modale">
            </button>
        </div>
        <form wire:click.stop wire:submit="update" class="flex flex-col gap-4">
            @csrf
            <x-global.form.input name="name" wire:model="name" placeholder="Permanente"
                                 :isRequired="true">
                Nom
            </x-global.form.input>

            <div class="flex gap-8">
                <x-global.form.input type="number" name="duration" wire:model="duration"
                                     placeholder="60" :isRequired="true">
                    Durée moyenne (en min.)
                </x-global.form.input>
                <x-global.form.input type="number" name="price" wire:model="price" placeholder="50"
                                     :isRequired="true">
                    Prix
                </x-global.form.input>
            </div>
            <x-global.form.textarea rows="1" name="desc" wire:model.live="desc" :isRequired="true">
                Description
            </x-global.form.textarea>

            <div class="ml-auto w-fit flex gap-6">
                <x-global.linkButton.button type="button" title="Fermer la modale" :isSecondary="true"
                                            wire:click="dispatch('close_modal')">
                    Annuler
                </x-global.linkButton.button>
                <x-global.linkButton.button title="Enregistrer les modifications">
                    Enregistrer
                </x-global.linkButton.button>
            </div>
        </form>
    </div>
</div>
