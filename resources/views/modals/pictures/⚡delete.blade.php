<?php

use App\Models\Photo;
use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public Photo $photo;

    public function mount(string $model_id)
    {
        if ($model_id) {
            $this->photo = Photo::findOrFail($model_id);
        }
    }

    public function destroy()
    {
        $this->photo->delete();
        $this->dispatch('action_done', message: 'Photo supprimée avec succès !', isDeleted: true);
        $this->dispatch('close_modal');
    }
};
?>

<livewire:admin.modal modal_title="Supprimer la prestation">
    <p class="mb-8">
        Êtes-vous sûr(e) de vouloir supprimer la photo ?
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

            <x-global.linkButton.button title="Supprimer la photo">
                Supprimer
            </x-global.linkButton.button>
        </div>
    </form>
</livewire:admin.modal>
