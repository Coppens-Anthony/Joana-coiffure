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
    <li class="pb-8 border-b border-b-black flex flex-col gap-4 sm:flex-row sm:justify-between sm:gap-8 sm:items-center">
        <div class="flex text-2xl flex-row sm:flex-col sm:items-center">
            <p>{{ Carbon::parse($appointment->start_at)->format('H\hi') }}</p>
            <span class="sm:hidden">&nbsp;-&nbsp;</span>
            <p>{{ Carbon::parse($appointment->end_at)->format('H\hi') }}</p>
        </div>
        <div
            class="{{ $this->isDashboard ? 'bg-primary' : '' }} relative w-full p-4 rounded-2xl flex flex-col gap-2 md:flex-row md:justify-between md:gap-8 md:items-center">
            @if(!$this->isDashboard)
                <div class="absolute left-0 top-0 h-full w-1 rounded-l-2xl" style="background-color: {{ $appointment->user->color }}"></div>
            @endif
            <div class="flex-1">
                <p class="flex items-center gap-2">
                    {!! $appointment->services->pluck('name')->implode('
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-black"></span>') !!}
                </p>
                <p class="flex mt-2 items-center gap-2">{{ $appointment->client->name }}
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-black"></span>
                    {{$appointment->services->sum('price')}}€
                </p>
            </div>
            <div class="flex gap-4 items-center">
                <button class="cursor-pointer hover:scale-120 duration-200" wire:click="show({{ $appointment->id }})">
                    <img src="{{ asset('assets/svg/eye.svg') }}" alt="Voir le rendez-vous en détail"
                         class="w-7 h-7">
                </button>
                <button class="cursor-pointer hover:scale-120 duration-200" wire:click="delete({{ $appointment->id }})">
                    <img src="{{ asset('assets/svg/delete.svg') }}" alt="Annuler le rendez-vous"
                         class="w-6 h-6">
                </button>
            </div>
        </div>
    </li>
</div>
