<?php

use App\Models\Client;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Fiche du client')]
class extends Component {
    public Client $client;

    #[On('action_done')]
    public function refresh(string $message = '', bool $isDeleted = false)
    {
        if ($message) {
            session()->flash($isDeleted ? 'delete' : 'success', $message);
        }
    }

    #[Computed]
    public function notes()
    {
        return $this->client->notes()->orderByDesc('updated_at')->get();
    }

    public function mount(Client $client): void
    {
        $this->client = $client->load(['appointments.services']);
    }

    public function create()
    {
        $this->dispatch('open_modal', ['modal' => 'modals::notes.create_edit', 'model_type' => $this->client->id]);
    }

    public function clientEdit(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::clients.create_edit', 'model_id' => $id]);
    }

    public function edit(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::notes.create_edit', 'model_id' => $id]);
    }

    public function delete(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::notes.delete', 'model_id' => $id]);
    }
};
?>

<div>
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('delete'))
        <div class="alert-delete">
            {{ session('delete') }}
        </div>
    @endif
    <section>
        <div class="flex gap-4 items-center wi-full">
            <h2 class="text-2xl">{{ $client->name }}</h2>
            <button wire:click="clientEdit({{ $client->id }})" class="cursor-pointer">
                <img src="{{ asset('assets/svg/edit.svg') }}" alt="Modifier les informations du client">
            </button>
        </div>
        <ul class="flex flex-col md:flex-row gap-2 md:items-center mt-2">
            <li>{{ $client->email }}</li>
            <span class="hidden md:inline-block w-1 h-1 rounded-full bg-black"></span>
            <li>{{ $client->telephone }}</li>
            <span class="hidden md:inline-block w-1 h-1 rounded-full bg-black"></span>
            <li>{{ $client->appointments->count() }} rendez-vous</li>
        </ul>
    </section>
    <section class="mt-8 mb-16 bg-tertiary p-6 rounded-2xl" x-data="{expanded: false}">
        <div class="flex items-center justify-between cursor-pointer"
             tabindex="0"
             @click="expanded = !expanded"
             @keydown.enter="expanded = !expanded"
             @keydown.space.prevent="expanded = !expanded">
            <h2 class="text-2xl">Notes personnelles</h2>
            <img src="{{ asset('assets/svg/chevron.svg') }}" alt="" class="transition-transform duration-200"
                 :class="{'rotate-180': expanded}">
        </div>
        <div x-show="expanded" class="mt-4">
            @if($this->notes->count() > 0)
                <ol class="flex flex-col gap-2">
                    @foreach($this->notes as $note)
                        <li class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:items-center">
                            <p class="w-3/4">{{ $note->formatDate('updated_at') . ' : ' . $note->content }}</p>
                            <div class="flex gap-2 w-fit">
                                <button wire:click="edit({{ $note->id }})" class="cursor-pointer">
                                    <img src="{{ asset('assets/svg/edit.svg') }}" alt="Modifier la note">
                                </button>
                                <button wire:click="delete({{ $note->id }})" class="cursor-pointer">
                                    <img src="{{ asset('assets/svg/delete.svg') }}" alt="Supprimer la note">
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @else
                <p>Pas encore de note pour {{ $client->name }}</p>
            @endif
            <x-global.linkbutton.button_link class="mt-4" title="Ajouter une note" wire:click="create">
                + Ajouter une note
            </x-global.linkbutton.button_link>
        </div>
    </section>
    <section>
        <h2 class="text-2xl mb-4">Historique des rendez-vous</h2>
        <x-global.table :titles="['Date', 'Prestation(s)', 'Durée', 'Prix', 'Informations supplémentaires']">
            @if($client->appointments->count() > 0)
                @foreach($client->appointments as $appointment)
                    <tr class="table__tr">
                        <td class="text_td">
                            <span class="title_td">Date</span>
                            {{ $appointment->formatDate('start_at') }}
                        </td>
                        <td class="text_td">
                            <span class="title_td">Prestation(s)</span>
                            {!! $appointment->services->map(fn($service) => $service->trashed()
                                ? $service->name . ' <small class="text-[.875rem]">(Supprimé)</small>'
                                : $service->name
                            )->implode(' / ') !!}
                        </td>
                        <td class="text_td">
                            <span class="title_td">Durée</span>
                            {{ $appointment->durationFormat($appointment->services->sum('duration')) }}
                        </td>
                        <td class="text_td">
                            <span class="title_td">Prix</span>
                            {{ $appointment->services->sum('price') }}€
                        </td>
                        <td class="text_td">
                            <span class="title_td">Informations supplémentaires</span>
                            <small class="text-[.875rem] italic">{{ $appointment->message ?? '/' }}</small>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="py-2" colspan="5">Aucun résultat</td>
                </tr>
            @endif
        </x-global.table>
    </section>
</div>
