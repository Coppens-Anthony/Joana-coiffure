<?php

use App\Models\Note;
use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public Note $note;

    public function mount(string $model_id)
    {
        if ($model_id) {
            $this->note = Note::findOrFail($model_id);
        }
    }

    public function destroy()
    {
        $this->note->delete();
        $this->dispatch('action_done', message: 'Note supprimée avec succès !', isDeleted: true);
        $this->dispatch('close_modal');
    }
};
?>

<livewire:admin.modal modal_title="Supprimer la prestation">
    <p class="mb-8">
        Êtes-vous sûr(e) de vouloir supprimer la note "{{ $this->note->content }}" ?
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
</livewire:admin.modal>
