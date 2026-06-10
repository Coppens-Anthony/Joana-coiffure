<?php

use App\Models\Client;
use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public Client $client;
    public string $name = '';
    public string $email = '';
    public string $telephone = '';
    public ?string $model_id = null;

    public function mount(?string $model_id)
    {
        if ($model_id) {
            $this->client = Client::findOrFail($model_id);
            $this->name = $this->client->name;
            $this->email = $this->client->email;
            $this->telephone = $this->client->telephone;
        }
    }

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

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:clients,email,' . $this->client->id,
            'telephone' => 'required',
        ]);

        $this->client->update($validated);
        $this->dispatch('action_done', message: 'Client modifié avec succès !');
        $this->dispatch('close_modal');
    }
};
?>

<livewire:admin.modal :modal_title="$this->model_id ? 'Modifier le client' : 'Ajooute un client'">
    <form wire:click.stop wire:submit="{{ $this->model_id ? 'update' : 'store' }}" class="flex flex-col gap-4">
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


        <div class="ml-auto w-fit flex gap-6 mt-4">
            <x-global.link-button.button type="button" title="Fermer la modale" :isSecondary="true"
                                        wire:click="dispatch('close_modal')">
                Annuler
            </x-global.link-button.button>
            <x-global.link-button.button
                :title="$this->model_id ? 'Enregistrer les modifications' : 'Ajouter le nouveau client'">
                {{ $this->model_id ? 'Enregistrer' : 'Ajouter' }}
            </x-global.link-button.button>
        </div>
    </form>
</livewire:admin.modal>
