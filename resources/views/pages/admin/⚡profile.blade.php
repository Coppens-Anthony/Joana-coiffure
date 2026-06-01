<?php

use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Votre profil')]
class extends Component {
    public string $name;
    public string $email;
    public string $oldPassword;
    public string $password;

    #[Computed]
    public function authUser()
    {
        return auth()->user();
    }

    public function mount()
    {
        $this->name = $this->authUser->name;
        $this->email = $this->authUser->email;
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->authUser->id,
            'oldPassword' => 'required|min:8',
            'password' => 'nullable|min:8|different:oldPassword',
        ]);

        if (!Hash::check($validated['oldPassword'], $this->authUser->password)) {
            $this->addError('oldPassword', 'Mot de passe incorrect');
            return;
        }

        $this->authUser->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $this->authUser->password
        ]);

        return redirect(route('profile'))
            ->with('success', 'Profil modifié avec succès');

    }
};
?>

<div>
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form wire:submit="update">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <x-global.form.input name="name" wire:model="name" :placeholder="$this->authUser->name"
                                 :value="$this->authUser->name">
                Nom
            </x-global.form.input>
            <x-global.form.input name="email" wire:model="email" type="email" :placeholder="$this->authUser->email"
                                 :value="$this->authUser->email">
                Email
            </x-global.form.input>
            <x-global.form.input name="oldPassword" wire:model="oldPassword" type="password">
                Mot de passe actuel
            </x-global.form.input>
            <x-global.form.input name="password" wire:model="password" type="password" :isRequired="false">
                Nouveau mot de passe
            </x-global.form.input>
        </div>
        <x-global.link-button.button title="Enregistrer les modifications" class="mx-auto block mt-8">Enregistrer
        </x-global.link-button.button>
    </form>
</div>
