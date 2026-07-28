<?php

use App\Models\Note;
use App\Models\Service;
use App\Models\User;
use Livewire\Component;

new class extends Component {
    public User $user;

    public function mount(string $model_id)
    {
        if ($model_id) {
            $this->user = User::findOrFail($model_id);
        }
    }

    public function destroy()
    {
        $this->user->delete();
        session()->flash('success', 'Membre supprimé avec succès !');
        return $this->redirect(route('members.index'));
    }
};
?>

<livewire:admin.modal modal_title="Supprimer le membre">
    <p class="mb-8">
        Êtes-vous sûr(e) de vouloir supprimer le membre "{{ $this->user->name }}" ?
    </p>

    <form wire:submit="destroy" class="flex flex-col gap-4">
        @csrf

        <div class="ml-auto w-fit flex gap-6">
            <x-global.link-button.button
                type="button"
                title="Fermer la modale"
                :isSecondary="true"
                wire:click="dispatch('close_modal')"
            >
                Annuler
            </x-global.link-button.button>

            <x-global.link-button.button title="Supprimer le membre">
                Supprimer
            </x-global.link-button.button>
        </div>
    </form>
</livewire:admin.modal>
