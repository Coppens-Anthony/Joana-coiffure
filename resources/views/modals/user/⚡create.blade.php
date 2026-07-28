<?php

use App\Mails\NewUser;
use Livewire\Component;

new class extends Component {
    public string $email;

    public function store()
    {
        $validated = $this->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        Mail::send(
            new NewUser($validated['email'])
        );

        $this->dispatch('action_done', message: 'Invitation envoyée avec succès !');
        $this->dispatch('close_modal');
    }
};
?>

<livewire:admin.modal modal_title="Ajout d'un membre">
    <form method="post" wire:submit="store">
        <x-global.form.input name="email" wire:model="email" type="email" placeholder="john@doe.com">
            Email
        </x-global.form.input>

        <small class="mt-2 text-[.85rem] block">Un mail sera envoyé à l'adresse renseignée afin d'inviter l'utilisateur
            à remplir ses informations et activer son comtpe.</small>

        <x-global.link-button.button title="Envoyer le mail d'invitation" class="ml-auto w-fit block mt-4">
            Envoyer
        </x-global.link-button.button>
    </form>
</livewire:admin.modal>
