<?php

use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public Service $service;

    public function mount(string $model_id)
    {
        if ($model_id) {
            $this->service = Service::findOrFail($model_id);
        }
    }

    public function destroy()
    {
        $this->service->delete();
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
            <p class="text-[2rem]">Supprimer la prestation</p>
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

        <p class="mb-8">
            Êtes-vous sûr(e) de vouloir supprimer la prestation "{{ $this->service->name }}" ?
        </p>

        <form wire:submit="destroy" class="flex flex-col gap-4">
            @csrf

            <div class="ml-auto w-fit flex gap-6">
                <x-global.linkButton.button
                    type="button"
                    title="Fermer la modale"
                    :isSecondary="true"
                    wire:click="dispatch('close_modal')"
                >
                    Annuler
                </x-global.linkButton.button>

                <x-global.linkButton.button title="Supprimer la prestation">
                    Supprimer
                </x-global.linkButton.button>
            </div>
        </form>
    </div>
</div>
