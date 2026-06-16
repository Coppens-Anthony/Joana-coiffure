<?php

use App\Models\Appointment;
use App\Models\RecurringUnavailability;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Congés récurrents')]
class extends Component {

    #[On('action_done')]
    public function refresh(string $message = '', bool $isDeleted = false)
    {
        if ($message) {
            session()->flash($isDeleted ? 'delete' : 'success', $message);
        }
    }

    #[Computed]
    public function recurringUnavailabilities()
    {
        return RecurringUnavailability::orderByDesc('updated_at')->get();
    }

    public function create()
    {
        $this->dispatch('open_modal', ['modal' => 'modals::recurring_unavailabilities.create_edit']);
    }

    public function edit(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::recurring_unavailabilities.create_edit', 'model_id' => $id]);
    }

    public function delete(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::recurring_unavailabilities.delete', 'model_id' => $id]);
    }

};
?>

<div>
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('delete'))
        <div class="alert-delete">
            {{ session('delete') }}
        </div>
    @endif

    <section class="flex flex-col gap-8">
        <h2 class="sr-only">Tableau des congés récurrents</h2>
        <x-global.link-button.button-link class="ml-auto" title="" wire:click="create">
            + Ajouter un congé récurrent
        </x-global.link-button.button-link>
        <x-global.table :titles="['Jour(s)', 'Heure de début', 'Heure de fin', 'Actions']">
            @if(count($this->recurringUnavailabilities) > 0)
                @foreach($this->recurringUnavailabilities as $unavailability)
                    <tr class="table__tr">
                        <td class="text_td">
                            <span class="title_td">Jour(s)</span>
                            {{ implode(', ', $unavailability->getDaysOfWeekLabels()) }}
                        </td>
                        <td class="text_td">
                            <span class="title_td">Heure de début</span>
                            {{ Carbon::parse($unavailability->start_time)->format('H\hi') }}
                        </td>
                        <td class="text_td">
                            <span class="title_td">Heure de fin</span>
                            {{ Carbon::parse($unavailability->end_time)->format('H\hi') }}
                        </td>
                        <td class="text_td">
                            <span class="title_td">Actions</span>
                            <div class="flex gap-2 items-center w-fit ml-auto lg:mx-auto">
                                <button type="button" wire:click="edit({{ $unavailability->id }})" class="hover:scale-120 duration-200">
                                    <img src="{{ asset('assets/svg/edit.svg') }}" alt="Modifier la prestation"
                                         class="w-7 h-7 cursor-pointer">
                                </button>
                                <button type="button" wire:click="delete({{ $unavailability->id }})" class="hover:scale-120 duration-200">
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
    </section>
</div>
