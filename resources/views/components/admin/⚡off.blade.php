<?php

use App\Models\Unavailability;
use Carbon\Carbon;
use Livewire\Component;

new class extends Component {
    public array $unavailability;

    public function edit()
    {
        $this->dispatch('open_modal', [
            'modal' => 'modals::unavailabilities.create',
            'params' => [
                'start_date' => Carbon::parse($this->unavailability['start_at'])->toDateString(),
                'end_date' => Carbon::parse($this->unavailability['end_at'])->toDateString(),
                'start_at' => Carbon::parse($this->unavailability['start_at'])->format('H:i'),
                'end_at' => Carbon::parse($this->unavailability['end_at'])->format('H:i'),
                'id' => $this->unavailability['id'],
            ]
        ]);
    }

    public function delete()
    {
        $this->dispatch('open_modal', [
            'modal' => 'modals::unavailabilities.delete',
            'params' => $this->unavailability
        ]);
    }
};
?>

<div>
    <li class="{{ $this->unavailability['allDay'] === false ? 'border-b border-b-black pb-8' : '' }} flex flex-col gap-4">
        @if($this->unavailability['allDay'] === false)
            <div class="flex text-2xl flex-row items-center">
                <p>{{ Carbon::parse($this->unavailability['start_at'])->format('H\hi') }}</p>
                <span>&nbsp;-&nbsp;</span>
                <p>{{ Carbon::parse($this->unavailability['end_at'])->format('H\hi') }}</p>
            </div>
        @endif

        <div class="{{ $this->unavailability['type'] === 'unavailability' ? 'bg-unavailability text-black' : 'bg-error text-white' }} w-full p-4 rounded-2xl flex flex-col gap-2">
            <p class="flex-1">
                {{ $this->unavailability['type'] === 'unavailability' ? 'Période off' : ($this->unavailability['allDay'] ? 'Jour de congé récurrent' : 'Créneau off récurrent') }}
            </p>
            @if($this->unavailability['type'] === 'unavailability')
                <div class="flex gap-4 items-center">
                    <button class="cursor-pointer" wire:click="edit({{ $this->unavailability['id'] }})">
                        <img src="{{ asset('assets/svg/edit.svg') }}" alt="Modifier"
                             class="w-5 h-5 lg:w-8 lg:h-8">
                    </button>
                    <button class="cursor-pointer" wire:click="delete({{ $this->unavailability['id'] }})">
                        <img src="{{ asset('assets/svg/delete.svg') }}" alt="Annuler"
                             class="w-4 h-4 lg:w-6 lg:h-6">
                    </button>
                </div>
            @endif

        </div>
    </li>
</div>
