<?php

use App\Models\Note;
use App\Models\RecurringUnavailability;
use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public RecurringUnavailability $recurringUnavailability;

    public function mount(string $model_id)
    {
        if ($model_id) {
            $this->recurringUnavailability = RecurringUnavailability::findOrFail($model_id);
        }
    }

    public function destroy()
    {
        $this->recurringUnavailability->delete();
        $this->dispatch('action_done', message: 'Période récurente supprimée avec succès !', isDeleted: true);
        $this->dispatch('close_modal');
    }
};
?>

<livewire:admin.modal modal_title="Supprimer la période récurente">
    <p class="mb-8">
        Êtes-vous sûr(e) de vouloir supprimer la période récurent ?
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

            <x-global.link-button.button title="Supprimer la période récurente">
                Supprimer
            </x-global.link-button.button>
        </div>
    </form>
</livewire:admin.modal>
