<?php

use App\Models\Client;
use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $telephone = '';

    public function store()
    {
        $validated = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:clients,email',
            'telephone' => 'required',
        ]);

        $client = Client::create($validated);
        $this->dispatch('client_created', id: $client->id, name: $client->name, message: 'Client ajouté avec succès !');
        $this->dispatch('close_modal');
    }
};
?>


<livewire:admin.modal modal_title="Ajouter un client">
    <form wire:click.stop wire:submit="store" class="flex flex-col gap-4">
        @csrf
        <x-global.form.input name="name" wire:model="name" placeholder="John Doe" :isRequired="true">
            Nom
        </x-global.form.input>
        <x-global.form.input name="email" wire:model="email" type="email" placeholder="john@doe.com" :isRequired="true">
            Email
        </x-global.form.input>
        <x-global.form.input name="telephone" wire:model="telephone" type="phone" placeholder="0123 45 67 89"
                             :isRequired="true">
            Téléphone
        </x-global.form.input>


        <div class="ml-auto w-fit flex gap-6">
            <x-global.linkButton.button type="button" title="Fermer la modale" :isSecondary="true"
                                        wire:click="dispatch('close_modal')">
                Annuler
            </x-global.linkButton.button>
            <x-global.linkButton.button
                title="Ajouter un client">
                Ajouter
            </x-global.linkButton.button>
        </div>
    </form>
</livewire:admin.modal>
