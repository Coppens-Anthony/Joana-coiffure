<?php

use App\Models\Note;
use Livewire\Component;

new class extends Component {
    public Note $note;
    public string $content = '';
    public ?string $model_id = null;
    public ?string $client_id = null;

    public function mount(?string $model_id, ?string $model_type)
    {
        $this->client_id = $model_type;

        if ($model_id) {
            $this->note = Note::findOrFail($model_id);
            $this->content = $this->note->content;
        }
    }

    public function store()
    {
        $validated = $this->validate([
            'content' => 'required',
        ]);

        Note::create([
            'client_id' => $this->client_id,
            'content' => $validated['content'],
            'user_id' => auth()->id()
        ]);
        $this->dispatch('action_done', message: 'Note ajoutée avec succès !');
        $this->dispatch('close_modal');
    }

    public function update()
    {
        $validated = $this->validate([
            'content' => 'required'
        ]);

        $this->note->update([
            'content' => $validated['content'],
            'updated_at' => now(),
            'user_id' => auth()->id()
        ]);
        $this->dispatch('action_done', message: 'Note modifiée avec succès !');
        $this->dispatch('close_modal');
    }
};
?>

<livewire:admin.modal :modal_title="$model_id ? 'Modifier la note' : 'Ajouter une note'">
    <form wire:submit="{{ $model_id ? 'update' : 'store' }}" class="flex flex-col gap-4">
        <x-global.form.textarea name="content" wire:model="content" placeholder="Elle est allergique au lait">
            Contenu
        </x-global.form.textarea>
        <div class="ml-auto w-fit flex gap-6 mt-4">
            <x-global.link-button.button type="button" title="Fermer la modale" :isSecondary="true"
                                         wire:click="dispatch('close_modal')">
                Annuler
            </x-global.link-button.button>
            <x-global.link-button.button
                :title="$this->model_id ? 'Enregistrer les modifications' : 'Ajouter la nouvelle prestation'">
                {{ $this->model_id ? 'Enregistrer' : 'Ajouter' }}
            </x-global.link-button.button>
        </div>
    </form>
</livewire:admin.modal>
