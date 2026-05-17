<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <livewire:admin.modal modal_title="Ajout d'une période off">
        <form wire:submit="store">
            @if($this->isMultipleDays)
                <div class="flex gap-4 my-8">
                    <x-global.form.input class="w-full" type="date" name="start" wire:model.live="start_date">
                        Date de début
                    </x-global.form.input>
                    <x-global.form.input class="w-full" type="date" name="end" wire:model.live="end_date">
                        Date de fin
                    </x-global.form.input>
                </div>
            @else
                <x-global.form.checkbox name="isFullDay" wire:model.live="isFullDay">
                    Journée entière off
                </x-global.form.checkbox>
                @if(!$this->isFullDay)
                    <div class="flex gap-4 my-8">
                        <x-global.form.input class="w-full" type="time" name="start_at" wire:model.live="start_at">
                            Heure de début
                        </x-global.form.input>
                        <x-global.form.input class="w-full" type="time" name="end_at" wire:model.live="end_at">
                            Heure de fin
                        </x-global.form.input>
                    </div>
                @endif
            @endif
            @if(($this->isMultipleDays || $this->isFullDay || ($this->start_at != null && $this->end_at != null)) && $this->conflictingAppointments->isNotEmpty())
                <div class="my-8">
                    <p class="mb-8"><span class="text-error">Attention</span>, il y
                        a {{ $this->conflictingAppointments->count() }}
                        rendez-vous durant cette période. Si vous
                        confirmez, {{ $this->conflictingAppointments->count() > 1 ? 'ils seront annulés' : 'il sera annulé'}}
                        .</p>
                    <x-global.form.checkbox name="contactClient" wire:model.live="contactClient">Prévenir les clients
                        par
                        mail
                    </x-global.form.checkbox>
                </div>
            @endif
            <div class="ml-auto w-fit flex gap-6">
                <x-global.linkButton.button type="button" title="Fermer la modale" :isSecondary="true"
                                            wire:click="dispatch('close_modal')">
                    Annuler
                </x-global.linkButton.button>
                <x-global.linkButton.button title="Ajouter la période off">
                    Ajouter
                </x-global.linkButton.button>
            </div>
        </form>
    </livewire:admin.modal>
</div>
