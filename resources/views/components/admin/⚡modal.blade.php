<?php

use Livewire\Component;

new class extends Component
{
    public string $modal_title;
};
?>

<div
    wire:click="dispatch('close_modal')"
    @keydown.escape.window="$wire.dispatch('close_modal')"
    class="fixed inset-0 z-50 bg-black/50">
    <div
        wire:click.stop
        x-trap.inert.noscroll="true"
        class="p-8 w-[80vw] md:w-[70vw] lg:w-[50vw] fixed top-1/2 left-1/2 bg-white transform -translate-x-1/2 -translate-y-1/2 rounded-3xl shadow-[0_0_10px_rgba(0,0,0,0.25)]">
        <div class="flex justify-between gap-8 items-center mb-8">
            <p class="text-[2rem] w-3/4">{{ $this->modal_title }}</p>
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
        {{ $slot }}
    </div>
</div>
