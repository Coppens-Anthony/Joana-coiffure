<?php

use App\Models\Unavailability;
use Carbon\Carbon;
use Livewire\Component;

new class extends Component {
    public array $unavailability;

    public function mount(array $params)
    {
        $this->unavailability = $params;
    }

    public function destroy()
    {
        Unavailability::find($this->unavailability['id'])->delete();
        $this->dispatch('action_done', message: 'Période off supprimée avec succès !', isDeleted: true);
        $this->dispatch('close_modal');
    }
};
?>

<livewire:admin.modal modal_title="Supprimer la période off">
    @php
        $start = Carbon::parse($this->unavailability['start_at']);
        $end = Carbon::parse($this->unavailability['end_at']);
        $sameDay = $start->toDateString() === $end->toDateString();
        $isFullDay = $start->timezone('Europe/Brussels')->format('H:i') === '09:00' && $end->timezone('Europe/Brussels')->format('H:i') === '18:00';
    @endphp

    <p class="mb-8">
        Êtes-vous sûr(e) de vouloir supprimer
        @if($sameDay && !$isFullDay)
            la période off de {{ $start->timezone('Europe/Brussels')->format('H\hi') }}
            à {{ $end->timezone('Europe/Brussels')->format('H\hi') }}
        @elseif($sameDay && $isFullDay)
            la journée off
        @else
            la période off  du {{ $start->translatedFormat('d F Y') }} au {{ $end->translatedFormat('d F Y') }}
        @endif
        ?
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

            <x-global.link-button.button title="Supprimer la période off">
                Supprimer
            </x-global.link-button.button>
        </div>
    </form>
</livewire:admin.modal>
