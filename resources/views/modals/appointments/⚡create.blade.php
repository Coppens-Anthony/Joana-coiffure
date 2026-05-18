<?php

use App\Models\Client;
use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $client;
    public string $service;
    public string $hour;

    #[Computed]
    public function clients()
    {
        return Client::pluck('name', 'id');
    }

    #[Computed]
    public function services()
    {
        return Service::pluck('name', 'id');
    }
};
?>

<livewire:admin.modal modal_title="Ajout d'un rendez-vous">
    <form class="flex flex-col gap-4">
        <x-global.form.select name="client" wire:model="client" :options="$this->clients" :isRequired="true">
            Client
        </x-global.form.select>
        <x-global.form.select name="service" wire:model="service" :options="$this->services" :isRequired="true">
            Prestations
        </x-global.form.select>
        <x-global.form.select name="hour" wire:model="hour" :options="[]" :isRequired="true">
            Heures disponibles
        </x-global.form.select>
        <div class="ml-auto w-fit flex gap-6">
            <x-global.linkButton.button
                type="button"
                title="Fermer la modale"
                :isSecondary="true"
                wire:click="dispatch('close_modal')"
            >
                Annuler
            </x-global.linkButton.button>

            <x-global.linkButton.button title="Ajouter le rendez-vous">
                Ajouter
            </x-global.linkButton.button>
        </div>
    </form>
</livewire:admin.modal>
