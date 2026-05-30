<?php

use App\Models\Appointment;
use Livewire\Component;
use Carbon\Carbon;

new class extends Component {
    public bool $isDashboard = true;
    public Appointment $appointment;

    public function show(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::appointments.show', 'model_id' => $id]);
    }

    public function delete(string $id)
    {
        $this->dispatch('open_modal', ['modal' => 'modals::appointments.delete', 'model_id' => $id]);
    }
};
?>

<div>
    <li class="pb-8 border-b border-b-black flex flex-col gap-4 {{ $this->isDashboard ? 'sm:flex-row sm:justify-between sm:gap-8 sm:items-center' : ''}}">
        <div class="flex text-2xl {{ $isDashboard ? 'flex-row sm:flex-col' : 'flex-row items-center' }}">
            <p>{{ Carbon::parse($appointment->start_at)->format('H\hi') }}</p>
            <span class="{{ $this->isDashboard ? 'sm:hidden' : 'block' }}">&nbsp;-&nbsp;</span>
            <p>{{ Carbon::parse($appointment->end_at)->format('H\hi') }}</p>
        </div>
        <div
            class="bg-primary w-full p-4 rounded-2xl flex flex-col sm:flex-row sm:justify-between sm:gap-8 sm:items-center">
            <div class="flex-1">
                <p class="flex flex-col md:flex-row md:items-center gap-2">
                    {!! $appointment->services->pluck('name')->implode('
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-black"></span>') !!}
                </p>
                <p class="flex mt-2 flex-col md:flex-row md:items-center gap-2">{{ $appointment->client->name }}
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-black"></span>
                    {{$appointment->services->sum('price')}}€
                </p>
            </div>
            <div class="flex gap-4 items-center">
                <button class="cursor-pointer" wire:click="show({{ $appointment->id }})">
                    <img src="{{ asset('assets/svg/eye.svg') }}" alt="Voir le rendez-vous en détail"
                         class="w-5 h-5 lg:w-8 lg:h-8">
                </button>
                <button class="cursor-pointer" wire:click="delete({{ $appointment->id }})">
                    <img src="{{ asset('assets/svg/delete.svg') }}" alt="Annuler le rendez-vous"
                         class="w-4 h-4 lg:w-6 lg:h-6">
                </button>
            </div>
        </div>
    </li>
</div>
