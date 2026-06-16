<?php

use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public Service $service;
    public string $name = '';
    public ?int $duration = null;
    public ?int $price = null;
    public ?string $desc = null;
    public ?string $model_id = null;
    public bool $isAppointment = false;

    public function mount(?string $model_id, ?array $params)
    {
        if ($model_id) {
            $this->service = Service::findOrFail($model_id);
            $this->name = $this->service->name;
            $this->duration = $this->service->duration;
            $this->price = $this->service->price;
            $this->desc = $this->service->desc ?? null;
        }
        if ($params){
            $this->isAppointment = $params['isAppointment'];
        }
    }

    public function store()
    {
        $validated = $this->validate([
            'name' => 'required|unique:services,name',
            'duration' => 'required|integer',
            'price' => 'required|integer',
            'desc' => 'nullable',
        ]);

        $service = Service::create($validated);
        if ($this->isAppointment) {
            $this->dispatch('service_created', id: $service->id, name: $service->name);
        } else {
            $this->dispatch('action_done', message: 'Prestation ajoutée avec succès !');
        }
        $this->dispatch('close_modal');
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required|unique:services,name,' . $this->service->id,
            'duration' => 'required|integer',
            'price' => 'required|integer',
            'desc' => 'nullable',
        ]);

        $this->service->update($validated);
        $this->dispatch('action_done', message: 'Prestation modifiée avec succès !');
        $this->dispatch('close_modal');
    }
};
?>


<livewire:admin.modal :modal_title="$this->model_id ? 'Modifier la prestation' : 'Ajouter une prestation'">
    <form wire:click.stop wire:submit="{{ $this->model_id ? 'update' : 'store' }}" class="flex flex-col gap-4">
        @csrf
        <x-global.form.input name="name" wire:model="name" placeholder="Permanente">
            Nom
        </x-global.form.input>

        <div class="flex flex-col md:flex-row gap-4 md:gap-8">
            <x-global.form.input class="w-full" type="number" name="duration" wire:model="duration" placeholder="60"
            >
                Durée moyenne (en min.)
            </x-global.form.input>
            <x-global.form.input class="w-full" type="number" name="price" wire:model="price" placeholder="50">
                Prix
            </x-global.form.input>
        </div>
        <x-global.form.textarea rows="3" name="desc" wire:model.live="desc" :isRequired="false">
            Description
        </x-global.form.textarea>

        <div class="ml-auto w-fit flex gap-6">
            <x-global.link-button.button type="button" title="Fermer la modale" :isSecondary="true"
                                        wire:click="dispatch('close_modal')">
                Annuler
            </x-global.link-button.button>
            <x-global.link-button.button :title="$this->model_id ? 'Enregistrer les modifications' : 'Ajouter la nouvelle prestation'">
                {{ $this->model_id ? 'Enregistrer' : 'Ajouter' }}
            </x-global.link-button.button>
        </div>
    </form>
</livewire:admin.modal>
