<?php

use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Données')]
class extends Component {

    public string $term = '';

    #[On('action_done')]
    public function refresh()
    {
    }

    #[Computed]
    public function services()
    {
        return Service::when($this->term, function ($query) {
            $query->where('name', 'like', '%' . $this->term . '%');
        })
            ->orderByDesc('updated_at')
            ->paginate(10);
    }

    public function create()
    {
        $this->dispatch('open_modal', ['modal' => 'modals::services.create']);
    }
    public function edit(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::services.edit', 'model_id' => $id]);
    }
    public function delete(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::services.delete', 'model_id' => $id]);
    }
};
?>

<div>
    <section class="flex flex-col gap-8">
        <h3 class="sr-only">Tableau des prestations</h3>
        <div class="flex justify-between items-end">
            <form class="w-fit">
                <x-global.form.input name="name"
                                     placeholder="Permanente"
                                     type="search"
                                     wire:model.live.debounce="term"
                                     :isRequired="false">
                    Rechercher une prestation
                </x-global.form.input>
            </form>
            <x-global.linkButton.button_link title="Ajouter une prestation" wire:click="create">
                + Ajouter une prestation
            </x-global.linkButton.button_link>
        </div>
        <x-global.table :titles="['Nom', 'Durée', 'Prix', 'Actions']">
            @if(count($this->services) > 0)
                @foreach($this->services as $service)
                    <tr class="table__tr">
                        <td class="text_td">
                            <span class="title_td">Nom</span>
                            {{ $service->name }}
                        </td>
                        <td class="text_td">
                            <span class="title_td">Durée</span>
                            {{ $service->durationFormat($service->duration) }}
                        </td>
                        <td class="text_td">
                            <span class="title_td">Prix</span>
                            {{ $service->price }}€
                        </td>
                        <td class="text_td">
                            <span class="title_td">Actions</span>
                            <div class="flex gap-2 items-center w-fit mx-auto">
                                <button type="button" wire:click="edit({{ $service->id }})">
                                    <img src="{{ asset('assets/svg/edit.svg') }}" alt="Modifier la prestation"
                                         class="w-7 h-7 cursor-pointer">
                                </button>
                                <button type="button" wire:click="delete({{ $service->id }})">
                                    <img src="{{ asset('assets/svg/delete.svg') }}" alt="Supprimer la prestation"
                                         class="w-6 h-6 cursor-pointer">
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="py-2" colspan="4">Aucun résultat</td>
                </tr>
            @endif
        </x-global.table>
        {{ $this->services->links() }}
    </section>
</div>
